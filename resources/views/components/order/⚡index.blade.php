<?php

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Livewire\Attributes\On;

new class extends Component {
    use WithPagination;

    public string $selectedDate = '';
    public string $statusFilter = 'all';
    public string $searchQuery = '';

    public function mount(): void
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function setFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function setDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->resetPage();
    }

    public function previousDay(): void
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d');
        $this->resetPage();
    }

    public function nextDay(): void
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d');
        $this->resetPage();
    }

    public function today(): void
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->resetPage();
    }

    public function updateOrderStatus(int $orderId, string $newStatus): void
    {
        $validStatuses = ['pending', 'preparing', 'ready', 'completed', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) return;

        $order = Order::find($orderId);
        if ($order) {
            $order->status = $newStatus;
            $order->save();
        }
    }

    #[On('order-saved')]
    public function refreshOrders(): void
    {
        // Re-renders automatically
    }

    public function getOrdersProperty()
    {
        return Order::with(['reservation.table', 'items.item'])
            ->whereDate('created_at', $this->selectedDate)
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when(!empty($this->searchQuery), function ($q) {
                $term = '%' . trim($this->searchQuery) . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('number', 'like', $term)
                        ->orWhereHas('reservation', function ($r) use ($term) {
                            $r->where('customer_name', 'like', $term)
                              ->orWhere('customer_phone', 'like', $term)
                              ->orWhereHas('table', function ($t) use ($term) {
                                  $t->where('number', 'like', $term);
                              });
                        });
                });
            })
            ->latest()
            ->paginate(12);
    }

    public function getCountsProperty()
    {
        $base = Order::whereDate('created_at', $this->selectedDate);

        return [
            'all'       => (clone $base)->count(),
            'pending'   => (clone $base)->where('status', 'pending')->count(),
            'preparing' => (clone $base)->where('status', 'preparing')->count(),
            'ready'     => (clone $base)->where('status', 'ready')->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
        ];
    }
};
?>

<div>
    <div class="orders-container">
        <div class="orders-toolbar">
            <div class="orders-date-selector">
                <button type="button" class="orders-date-btn" wire:click="previousDay" title="اليوم السابق">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>
                <input type="date" class="orders-date-picker" wire:model.live="selectedDate">
                <button type="button" class="orders-date-btn" wire:click="nextDay" title="اليوم التالي">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
                <button type="button" class="orders-today-btn" wire:click="today">اليوم</button>
            </div>

            <div class="orders-filter-pills">
                <button type="button" class="orders-pill" :class="{ 'is-active': $wire.statusFilter === 'all' }" wire:click="setFilter('all')">
                    الكل ({{ $this->counts['all'] }})
                </button>
                <button type="button" class="orders-pill" :class="{ 'is-active': $wire.statusFilter === 'pending' }" wire:click="setFilter('pending')">
                    <span class="orders-pill-dot dot-pending"></span>
                    لم يبدأ ({{ $this->counts['pending'] }})
                </button>
                <button type="button" class="orders-pill" :class="{ 'is-active': $wire.statusFilter === 'preparing' }" wire:click="setFilter('preparing')">
                    <span class="orders-pill-dot dot-preparing"></span>
                    قيد التحضير ({{ $this->counts['preparing'] }})
                </button>
                <button type="button" class="orders-pill" :class="{ 'is-active': $wire.statusFilter === 'ready' }" wire:click="setFilter('ready')">
                    <span class="orders-pill-dot dot-ready"></span>
                    جاهز ({{ $this->counts['ready'] }})
                </button>
                <button type="button" class="orders-pill" :class="{ 'is-active': $wire.statusFilter === 'completed' }" wire:click="setFilter('completed')">
                    <span class="orders-pill-dot dot-completed"></span>
                    مكتمل ({{ $this->counts['completed'] }})
                </button>
            </div>

            <div class="orders-search-box">
                <div class="orders-search-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
                <input type="text" placeholder="بحث برقم الطلب، اسم العميل أو الطاولة..." wire:model.live.debounce.300ms="searchQuery">
            </div>
        </div>

        <div class="orders-grid">
            @forelse($this->orders as $order)
                <div class="order-card status-{{ $order->status }}">
                    
                    <div class="order-card-header">
                        <div class="order-num-wrap">
                            <div class="order-num">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                    <line x1="3" y1="6" x2="21" y2="6"/>
                                </svg>
                                {{ $order->number }}
                            </div>
                            <span class="order-time">{{ $order->created_at->format('h:i A') }} ({{ $order->created_at->diffForHumans() }})</span>
                        </div>

                        <span class="status-badge status-badge-{{ $order->status }}">
                            <span class="orders-pill-dot dot-{{ $order->status }}"></span>
                            {{ match($order->status) {
                                'pending'   => 'لم يبدأ',
                                'preparing' => 'قيد التحضير',
                                'ready'     => 'جاهز للتقديم',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغي',
                                default     => $order->status,
                            } }}
                        </span>
                    </div>

                    <div class="order-card-customer">
                        <div class="customer-meta">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span>{{ $order->reservation?->customer_name ?? 'طلب مباشر' }}</span>
                        </div>
                        @if($order->reservation?->table)
                            <span class="table-chip">طاولة {{ $order->reservation->table->number }}</span>
                        @endif
                    </div>

                    <div class="order-items-body">
                        @foreach($order->items as $orderItem)
                            <div class="order-item-row">
                                <div class="order-item-title-qty">
                                    <span class="item-qty-badge">{{ $orderItem->quantity }}x</span>
                                    <span>{{ $orderItem->item?->name ?? 'وجبة' }}</span>
                                </div>
                                <span class="order-item-subtotal">{{ number_format($orderItem->subtotal, 2) }} ج.م</span>
                            </div>
                        @endforeach

                        @if($order->notes)
                            <div class="order-notes-box">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                                <span>{{ $order->notes }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="order-card-footer">
                        <div class="order-total-bar">
                            <span class="order-total-lbl">الإجمالي الكلي:</span>
                            <span class="order-total-val">{{ number_format($order->total_amount, 2) }} ج.م</span>
                        </div>

                        <div class="kitchen-actions-grid">
                            <button type="button" 
                                class="k-btn k-btn-pending"
                                :class="{ 'is-active': '{{ $order->status }}' === 'pending' }"
                                wire:click="updateOrderStatus({{ $order->id }}, 'pending')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>لم يبدأ</span>
                            </button>

                            <button type="button" 
                                class="k-btn k-btn-preparing"
                                :class="{ 'is-active': '{{ $order->status }}' === 'preparing' }"
                                wire:click="updateOrderStatus({{ $order->id }}, 'preparing')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                <span>بيتعمل</span>
                            </button>

                            <button type="button" 
                                class="k-btn k-btn-ready"
                                :class="{ 'is-active': '{{ $order->status }}' === 'ready' }"
                                wire:click="updateOrderStatus({{ $order->id }}, 'ready')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span>جهز</span>
                            </button>
                        </div>
                    </div>

                </div>
            @empty
                <div class="orders-empty-state">
                    <div class="empty-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>
                    <h3 class="empty-title">لا توجد طلبات بهذا التاريخ / الفلتر</h3>
                    <p class="empty-desc">عندما يتم عمل أوردر من صفحة الحجوزات أو الميزان، سيظهر هنا مباشرة للمطبخ.</p>
                </div>
            @endforelse
        </div>

        @if($this->orders->hasPages())
            <div style="margin-top: 20px;">
                {{ $this->orders->links() }}
            </div>
        @endif

    </div>
</div>
