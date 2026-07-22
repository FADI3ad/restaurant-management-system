<?php

use App\Models\Reservation;
use App\Models\Section;
use App\Models\Order;
use App\Services\Order\CreateOrUpdateOrderAction;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {

    // --- State ---
    public ?int $reservationId = null;
    public ?Reservation $reservation = null;
    public ?Order $existingOrder = null;

    // Cart: [ item_id => [ 'id', 'name', 'price', 'qty' ] ]
    public array $cart = [];

    public string $orderNotes = '';
    public bool $saving = false;
    public bool $saved = false;

    // Menu data (loaded once when reservation is set)
    public array $sections = [];

    #[On('load-order')]
    public function loadOrder(int $reservationId): void
    {
        $this->reservationId = $reservationId;
        $this->cart = [];
        $this->orderNotes = '';
        $this->saved = false;

        // Load reservation with table relation
        $this->reservation = Reservation::with('table', 'order.items.item')->findOrFail($reservationId);

        // Load existing order if any
        $this->existingOrder = $this->reservation->order;
        if ($this->existingOrder) {
            $this->orderNotes = $this->existingOrder->notes ?? '';
            foreach ($this->existingOrder->items as $orderItem) {
                $item = $orderItem->item;
                if (!$item) continue;
                $this->cart[$item->id] = [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'price' => (float) $item->price,
                    'qty'   => $orderItem->quantity,
                ];
            }
        }

        // Load real menu (sections → categories → subcategories → items, all active)
        $sectionsRaw = Section::where('status', true)
            ->with(['categories' => fn($q) => $q->where('status', true)
                ->orderBy('display_order')
                ->with(['subcategories' => fn($sq) => $sq->where('status', true)
                    ->orderBy('display_order')
                    ->with(['items' => fn($iq) => $iq->where('status', true)->orderBy('display_order')])
                ])
            ])
            ->orderBy('display_order')
            ->get();

        // Flatten into a serializable array so Alpine can use it
        $this->sections = $sectionsRaw->map(function ($section) {
            $items = [];
            foreach ($section->categories as $cat) {
                foreach ($cat->subcategories as $sub) {
                    foreach ($sub->items as $item) {
                        $items[] = [
                            'id'          => $item->id,
                            'name'        => $item->name,
                            'description' => $item->description ?? '',
                            'price'       => (float) $item->price,
                            'image'       => $item->image ? asset('storage/' . $item->image) : null,
                            'category'    => $cat->name,
                            'subcategory' => $sub->name,
                        ];
                    }
                }
            }
            return [
                'id'    => $section->id,
                'name'  => $section->name,
                'items' => $items,
            ];
        })->values()->toArray();
    }

    public function addItem(int $itemId, string $name, float $price): void
    {
        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['qty']++;
        } else {
            $this->cart[$itemId] = [
                'id'    => $itemId,
                'name'  => $name,
                'price' => $price,
                'qty'   => 1,
            ];
        }
    }

    public function removeItem(int $itemId): void
    {
        if (!isset($this->cart[$itemId])) return;

        if ($this->cart[$itemId]['qty'] > 1) {
            $this->cart[$itemId]['qty']--;
        } else {
            unset($this->cart[$itemId]);
        }
    }

    public function clearCart(): void
    {
        $this->cart = [];
    }

    public function getCartTotal(): float
    {
        return array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $this->cart));
    }

    public function getCartCount(): int
    {
        return array_sum(array_column($this->cart, 'qty'));
    }

    public function confirmOrder(): void
    {
        if (empty($this->cart) || !$this->reservationId) return;

        $this->saving = true;

        $cartItems = array_values(array_map(fn($item) => [
            'item_id' => $item['id'],
            'qty'     => $item['qty'],
        ], $this->cart));

        $action = new CreateOrUpdateOrderAction();
        $this->existingOrder = $action($this->reservationId, $cartItems, $this->orderNotes);

        $this->saving = false;
        $this->saved  = true;

        $this->dispatch('order-saved', reservationId: $this->reservationId);
    }
};
?>

<div id="modal-order" class="modal-overlay is-active" x-show="orderOpen" x-cloak
    @click.self="orderOpen = false"
    x-transition.opacity.duration.200ms>
    <div class="modal-content modal-order-lg">
        <x-modal-head-component title="طلب وجبات" />

        <div class="modal-body order-modal-body">

            {{-- Reservation Summary Bar --}}
            <div class="order-rsv-summary">
                <div class="order-rsv-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span class="order-rsv-name">
                        {{ $this->reservation?->customer_name ?? '...' }}
                    </span>
                    @if($this->existingOrder)
                        <span class="order-rsv-badge" style="background: var(--success-soft); color: var(--success); border-color: var(--success); margin-right: 8px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12" /></svg>
                            {{ $this->existingOrder->number }}
                        </span>
                    @endif
                </div>
                <div class="order-rsv-badges">
                    @if($this->reservation?->table)
                    <span class="order-rsv-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="3" rx="1" />
                            <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                        </svg>
                        طاولة {{ $this->reservation->table->number }}
                    </span>
                    @endif
                    @if($this->reservation?->start_time)
                    <span class="order-rsv-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ $this->reservation->start_time }} – {{ $this->reservation->end_time }}
                    </span>
                    @endif
                    @if($this->reservation?->number_of_guests)
                    <span class="order-rsv-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        {{ $this->reservation->number_of_guests }} أشخاص
                    </span>
                    @endif
                </div>
            </div>

            {{-- Order Layout: Menu + Cart --}}
            <div class="order-layout" x-data="{
                activeSection: 'all',
                searchQuery: '',
                sections: @js($this->sections),
                cart: @js(array_values($this->cart)),

                get allItems() {
                    let items = [];
                    for (const sec of this.sections) {
                        for (const item of sec.items) {
                            items.push({ ...item, sectionId: sec.id });
                        }
                    }
                    return items;
                },

                get filteredItems() {
                    const q = this.searchQuery.trim().toLowerCase();
                    return this.allItems.filter(item => {
                        const matchSec = this.activeSection === 'all' || item.sectionId == this.activeSection;
                        const matchSearch = !q ||
                            item.name.toLowerCase().includes(q) ||
                            (item.description && item.description.toLowerCase().includes(q)) ||
                            item.category.toLowerCase().includes(q) ||
                            item.subcategory.toLowerCase().includes(q);
                        return matchSec && matchSearch;
                    });
                },

                get cartTotal() {
                    return this.cart.reduce((sum, i) => sum + i.price * i.qty, 0);
                },
                get cartCount() {
                    return this.cart.reduce((sum, i) => sum + i.qty, 0);
                },

                getQty(itemId) {
                    const found = this.cart.find(c => c.id === itemId);
                    return found ? found.qty : 0;
                },

                addItem(item) {
                    const found = this.cart.find(c => c.id === item.id);
                    if (found) { found.qty++; }
                    else { this.cart.push({ id: item.id, name: item.name, price: item.price, qty: 1 }); }
                    $wire.addItem(item.id, item.name, item.price);
                },

                removeItem(item) {
                    const found = this.cart.find(c => c.id === item.id);
                    if (found && found.qty > 1) { found.qty--; }
                    else { this.cart = this.cart.filter(c => c.id !== item.id); }
                    $wire.removeItem(item.id);
                },

                clearCart() {
                    this.cart = [];
                    $wire.clearCart();
                }
            }"
            x-init="
                $watch('$wire.cart', val => {
                    cart = Object.values(val).map(i => ({...i, qty: i.qty}));
                });
                $watch('$wire.sections', val => { sections = val; });
            ">

                {{-- Left: Menu Browser --}}
                <div class="order-menu-panel">

                    {{-- Search --}}
                    <div class="order-search-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        <input type="text" class="order-search-input" placeholder="ابحث عن وجبة..."
                            x-model="searchQuery">
                    </div>

                    {{-- Section Tabs --}}
                    <div class="order-cat-tabs">
                        <button type="button" class="order-cat-tab"
                            :class="{ 'is-active': activeSection === 'all' }"
                            @click="activeSection = 'all'">
                            الكل
                        </button>
                        <template x-for="sec in sections" :key="sec.id">
                            <button type="button" class="order-cat-tab"
                                :class="{ 'is-active': activeSection == sec.id }"
                                @click="activeSection = sec.id"
                                x-text="sec.name">
                            </button>
                        </template>
                    </div>

                    {{-- Menu Grid --}}
                    <div class="order-menu-grid">
                        <template x-for="item in filteredItems" :key="item.id">
                            <div class="order-menu-card">
                                {{-- Item image if exists --}}
                                <template x-if="item.image">
                                    <div class="order-menu-card-img">
                                        <img :src="item.image" :alt="item.name">
                                    </div>
                                </template>
                                <div class="order-menu-card-body">
                                    <div class="order-menu-card-info">
                                        <span class="order-menu-card-name" x-text="item.name"></span>
                                        <span class="order-menu-card-cat" x-text="item.category + ' · ' + item.subcategory"></span>
                                        <span class="order-menu-card-desc" x-text="item.description"></span>
                                        <span class="order-menu-card-price" x-text="item.price.toFixed(2) + ' ر.س'"></span>
                                    </div>
                                    <div class="order-menu-card-actions">
                                        <template x-if="getQty(item.id) === 0">
                                            <button type="button" class="order-add-btn" @click="addItem(item)">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M12 5v14M5 12h14" />
                                                </svg>
                                            </button>
                                        </template>
                                        <template x-if="getQty(item.id) > 0">
                                            <div class="order-qty-ctrl">
                                                <button type="button" class="order-qty-btn" @click="removeItem(item)">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M5 12h14" />
                                                    </svg>
                                                </button>
                                                <span class="order-qty-val" x-text="getQty(item.id)"></span>
                                                <button type="button" class="order-qty-btn" @click="addItem(item)">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M12 5v14M5 12h14" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Empty state --}}
                        <div class="order-empty" x-show="filteredItems.length === 0" style="grid-column: 1/-1;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                            <span>لا توجد وجبات مطابقة</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Cart / Order Summary --}}
                <div class="order-cart-panel">
                    <div class="order-cart-header">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                        <span>الطلب</span>
                        <span class="order-cart-count" x-show="cartCount > 0" x-text="cartCount"></span>
                    </div>

                    {{-- Cart Empty --}}
                    <div class="order-cart-empty" x-show="cart.length === 0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                        <span>لا توجد وجبات في الطلب بعد</span>
                    </div>

                    {{-- Cart Items --}}
                    <div class="order-cart-list" x-show="cart.length > 0">
                        <template x-for="item in cart" :key="item.id">
                            <div class="order-cart-item">
                                <div class="order-cart-item-info">
                                    <span class="order-cart-item-name" x-text="item.name"></span>
                                    <span class="order-cart-item-price"
                                        x-text="(item.price * item.qty).toFixed(2) + ' ر.س'"></span>
                                </div>
                                <div class="order-qty-ctrl order-qty-ctrl--sm">
                                    <button type="button" class="order-qty-btn" @click="removeItem(item)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                        </svg>
                                    </button>
                                    <span class="order-qty-val" x-text="item.qty"></span>
                                    <button type="button" class="order-qty-btn" @click="addItem(item)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 5v14M5 12h14" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Cart Footer --}}
                    <div class="order-cart-foot" x-show="cart.length > 0">
                        <div class="order-cart-total-row">
                            <span class="order-cart-total-label">الإجمالي</span>
                            <span class="order-cart-total-val" x-text="cartTotal.toFixed(2) + ' ر.س'"></span>
                        </div>
                        <button type="button" class="order-clear-btn" @click="clearCart()">
                            مسح الطلب
                        </button>
                    </div>

                    {{-- Success feedback --}}
                    @if($this->saved)
                    <div class="order-saved-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0;">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        تم حفظ الطلب بنجاح!
                    </div>
                    @endif

                    {{-- Notes --}}
                    <div class="order-notes-wrap">
                        <label class="order-notes-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            ملاحظات الطلب
                        </label>
                        <textarea class="order-notes-input" rows="3" placeholder="ملاحظات خاصة بالطلب..."
                            wire:model="orderNotes"></textarea>
                    </div>
                </div>

            </div>{{-- end order-layout --}}

        </div>{{-- end modal-body --}}

        <div class="modal-foot">
            <button type="button" class="btn btn--ghost" @click="orderOpen = false">إلغاء</button>
            <button type="button" class="btn btn--primary"
                wire:click="confirmOrder"
                wire:loading.attr="disabled"
                wire:loading.class="btn--disabled"
                :disabled="$wire.cart.length === 0 || Object.keys($wire.cart).length === 0"
                :class="{ 'btn--disabled': Object.keys($wire.cart).length === 0 }">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span wire:loading.remove wire:target="confirmOrder">تأكيد الطلب</span>
                <span wire:loading wire:target="confirmOrder">جاري الحفظ...</span>
            </button>
        </div>

    </div>
</div>
