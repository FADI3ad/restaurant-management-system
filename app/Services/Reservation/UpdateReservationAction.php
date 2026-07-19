<?php

namespace App\Services\Reservation;

use App\Models\Reservation;

class UpdateReservationAction
{
    public function __invoke(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation->fresh();
    }
}
