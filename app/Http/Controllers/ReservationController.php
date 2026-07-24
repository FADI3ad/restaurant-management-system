<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Services\Reservation\CreateReservationAction;
use App\Services\Reservation\UpdateReservationAction;
use App\Services\Reservation\DeleteReservationAction;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('table')->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $reservations,
            'message' => 'Operation successful'
        ]);
    }

    public function store(StoreReservationRequest $request, CreateReservationAction $action)
    {
        $reservation = $action($request->validated());

        return response()->json([
            'success' => true,
            'data' => $reservation,
            'message' => 'تم إضافة الحجز بنجاح'
        ], 201);
    }

    public function show(Reservation $reservation)
    {
        return response()->json([
            'success' => true,
            'data' => $reservation->load(['table', 'order.items.item']),
            'message' => 'Operation successful'
        ]);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation, UpdateReservationAction $action)
    {
        $updated = $action($reservation, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'تم تعديل الحجز بنجاح'
        ]);
    }

    public function destroy(Reservation $reservation, DeleteReservationAction $action)
    {
        $action($reservation);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'تم حذف الحجز بنجاح'
        ]);
    }
}
