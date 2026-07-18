<?php

namespace App\Services\Reservation;

use App\Models\Reservation;

class CreateReservationAction
{
    public function __invoke(array $data): Reservation
    {
        return Reservation::create($data);
    }
}
