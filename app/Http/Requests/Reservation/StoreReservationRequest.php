<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public static function rulesArray(): array
    {
        return [
            'customer_name' => 'required|string|max:255|min:3',
            'customer_phone' => 'required|string|max:255',
            'number_of_guests' => 'required|integer|min:1',
            'code' => 'required|string|unique:reservations,code',
            'start_time' => 'required',
            'duration' => 'required|in:1,2,3,4,5,6',
            'date' => 'required|date',
            'status' => 'required|in:Confirmed,Arrived,Cancelled,Completed,No_Show',
            'table_id' => 'required|exists:tables,id',
        ];
    }

    public function rules(): array
    {
        return self::rulesArray();
    }
}
