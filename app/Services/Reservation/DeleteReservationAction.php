<?php

namespace App\Services\Reservation;

use App\Models\Reservation;

class DeleteReservationAction
{
    public function __invoke(Reservation $modelInstance): ?bool
    {
        return $modelInstance->delete();
    }
}
