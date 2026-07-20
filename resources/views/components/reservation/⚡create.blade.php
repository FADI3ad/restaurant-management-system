<?php

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Livewire\Forms\ReservationForm;
use App\Models\Table;
use App\Services\Reservation\CreateReservationAction;
use Carbon\Carbon;
use Livewire\Component;

new class extends Component {
    public ReservationForm $form;

    public function tables()
    {
        $date = $this->form->date;
        $start = $this->form->start_time;
        $end = Carbon::parse($start)->addMinutes((int) $this->form->duration)->format('H:i:s');

        return Table::query()
            ->where('status', 'Available')
            ->where('min_capacity', '<=', $this->form->number_of_guests)
            ->where('max_capacity', '>=', $this->form->number_of_guests)
            ->whereDoesntHave('reservations', function ($query) use ($date, $start, $end) {
                $query
                    ->whereDate('date', $date)
                    ->whereIn('status', ['Confirmed', 'Checked_In'])
                    ->whereRaw('start_time < ? AND ADDTIME(start_time, SEC_TO_TIME(duration * 60)) > ?', [$end, $start]);
            })
            ->get();
    }

    public function save(CreateReservationAction $createReservation)
    {
        $validated = $this->form->validate(StoreReservationRequest::rulesArray());

        $createReservation($validated);

        $this->dispatch('close-add-modal');

        $this->dispatch('reservation-changed');

        $this->form->reset();
    }
};
?>

<div id="modal-add" class="modal-overlay is-active" x-show="addOpen" x-cloak @click.self="addOpen = false;"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="إضافة حجز جديد" />
        <form id="form-add" wire:submit.prevent="save">
            <div class="modal-body modal-form-grid">

                <div class="field span-2">
                    <label class="field-label">اسم العميل <span class="req">*</span></label>
                    <input wire:model.live="form.customer_name" type="text" class="input"
                        placeholder="مثال: أحمد محمد...">
                    @error('form.customer_name')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">رقم الهاتف <span class="req">*</span></label>
                    <input wire:model.live="form.customer_phone" type="text" class="input"
                        placeholder="مثال: 01012345678">
                    @error('form.customer_phone')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">عدد الأشخاص <span class="req">*</span></label>
                    <input wire:model.live="form.number_of_guests" type="number" class="input" placeholder="0"
                        min="1">
                    @error('form.number_of_guests')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">تاريخ الحجز <span class="req">*</span></label>
                    <input wire:model.live="form.date" type="date" class="input">
                    @error('form.date')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">وقت البداية <span class="req">*</span></label>
                    <input wire:model.live="form.start_time" type="time" class="input">
                    @error('form.start_time')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="field-label">مدة الحجز <span class="req">*</span></label>
                    <select wire:model.live="form.duration" class="select">
                        <option value="">-- اختر المدة --</option>
                        <option value="30">30 دقيقة</option>
                        <option value="60">ساعة</option>
                        <option value="90">ساعة ونص</option>
                        <option value="120">ساعتين</option>
                        <option value="150">ساعتين ونص</option>
                        <option value="180">3 ساعات</option>
                    </select>
                    @error('form.duration')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                @if ($this->form->date && $this->form->start_time && $this->form->duration && $this->form->number_of_guests)
                    <div class="field span-2">
                        <label class="field-label">الطاولات المتاحة<span class="req">*</span></label>
                        <select wire:model.live="form.table_id" class="select">
                            <option value="">-- اختر الطاولة --</option>
                            @foreach ($this->tables() as $table)
                                <option value="{{ $table->id }}">
                                    طاولة {{ $table->number }}
                                    {{ $table->location ? '(' . $table->location . ')' : '' }} —
                                    {{ $table->min_capacity }}-{{ $table->max_capacity }} شخص
                                </option>
                            @endforeach
                        </select>
                        @error('form.table_id')
                            <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14"
                                    height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg> {{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="field span-2">
                    <label class="field-label">حالة الحجز</label>
                    <select wire:model.live="form.status" class="select">
                        <option value="Confirmed">مؤكد</option>
                        <option value="Checked_In">وصل</option>
                    </select>
                    @error('form.status')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field span-2">
                    <label class="field-label">ملاحظات</label>
                    <textarea wire:model.live="form.notes" class="textarea" placeholder="اكتب أي ملاحظات خاصة بهذا الحجز..."></textarea>
                    @error('form.notes')
                        <div class="field-error"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg> {{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="addOpen = false">
                    إلغاء
                </button>
                <button type="submit" class="btn btn--primary" @close-add-modal.window="addOpen = false">
                    حفظ
                </button>
            </div>
        </form>
    </div>
</div>
