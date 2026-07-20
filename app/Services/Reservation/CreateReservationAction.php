<?php

namespace App\Services\Reservation;

use App\Models\Reservation;

class CreateReservationAction
{
    public function __invoke(array $data): Reservation
    {
        $data['number'] = 'RES-' . str_pad(Reservation::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $reservation = Reservation::create($data);

        return $reservation;
    }
}
