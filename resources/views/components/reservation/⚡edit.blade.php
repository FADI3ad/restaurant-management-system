<?php

use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Livewire\Forms\ReservationForm;
use App\Models\Table;
use App\Services\Reservation\UpdateReservationAction;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ReservationForm $form;

    public function tables()
    {
        return Table::orderBy('table_number')->get();
    }

    #[On('load-reservation-edit')]
    public function loadReservation($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $this->form->setData($reservation);
    }

    public function save(UpdateReservationAction $updateReservation)
    {
        $validated = $this->form->validate(UpdateReservationRequest::rulesArray());

        $updateReservation($this->form->reservation, $validated);

        $this->dispatch('close-edit-modal');

        $this->dispatch('reservation-changed');

        $this->form->reset();
    }
};
?>

<div id="modal-edit" class="modal-overlay is-active" x-show="editOpen" x-cloak @click.self="editOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-md">
        <x-modal-head-component title="تعديل الحجز" />
        <form id="form-edit" wire:submit.prevent="save">
            <div class="modal-body modal-form-grid">

                <div class="field span-2">
                    <label class="field-label">اسم العميل <span class="req">*</span></label>
                    <input wire:model="form.customer_name" type="text" class="input"
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
                    <input wire:model="form.customer_phone" type="text" class="input"
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
                    <input wire:model="form.number_of_guests" type="number" class="input" placeholder="0" min="1">
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
                    <label class="field-label">الطاولة <span class="req">*</span></label>
                    <select wire:model="form.table_id" class="select">
                        <option value="">-- اختر الطاولة --</option>
                        @foreach ($this->tables() as $table)
                            <option value="{{ $table->id }}">
                                طاولة {{ $table->table_number }} ({{ $table->type }} —
                                {{ $table->min_capacity }}-{{ $table->max_capacity }} شخص)
                            </option>
                        @endforeach
                    </select>
                    @error('form.table_id')
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
                    <input wire:model="form.date" type="date" class="input">
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
                    <input wire:model="form.start_time" type="time" class="input">
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
                    <label class="field-label">مدة الحجز</label>
                    <select wire:model="form.duration" class="select">
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

                <div class="field span-2">
                    <label class="field-label">حالة الحجز</label>
                    <select wire:model="form.status" class="select">
                        <option value="Confirmed">مؤكد</option>
                        <option value="Arrived">وصل</option>
                        <option value="Cancelled">ملغي</option>
                        <option value="Completed">مكتمل</option>
                        <option value="No_Show">لم يحضر</option>
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

            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="editOpen = false">
                    إلغاء
                </button>
                <button type="submit" class="btn btn--primary" @close-edit-modal.window="editOpen = false">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
