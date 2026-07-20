<?php

namespace App\Livewire\Forms;

use App\Models\Reservation;
use Livewire\Form;

class ReservationForm extends Form
{

    public ?Reservation $reservation = null;

    public ?string $customer_name = null;

    public ?string $customer_phone = null;

    public ?int $number_of_guests = null;

    public ?string $start_time = null;

    public ?string $duration = null;

    public ?string $date = null;

    public ?string $notes = null;

    public string $status = 'Confirmed';

    public ?int $table_id = null;

    public function setData(Reservation $reservation)
    {
        $this->reservation = $reservation;

        $this->customer_name = $reservation->customer_name;

        $this->customer_phone = $reservation->customer_phone;

        $this->number_of_guests = $reservation->number_of_guests;

        $this->start_time = $reservation->start_time;

        $this->duration = $reservation->duration;

        $this->date = $reservation->date;

        $this->status = $reservation->status;

        $this->table_id = $reservation->table_id;

        $this->notes = $reservation->notes;
    }
}
