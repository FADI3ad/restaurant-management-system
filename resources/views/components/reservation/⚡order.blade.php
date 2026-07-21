<?php

use Livewire\Component;

new class extends Component {
    // No backend logic — pure front-end modal
};
?>

<div id="modal-order" class="modal-overlay is-active" x-show="orderOpen" x-cloak @click.self="orderOpen = false"
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
                    <span class="order-rsv-name">اسم العميل</span>
                </div>
                <div class="order-rsv-badges">
                    <span class="order-rsv-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="3" rx="1" />
                            <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                        </svg>
                        طاولة 5
                    </span>
                    <span class="order-rsv-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        08:00 – 10:00
                    </span>
                    <span class="order-rsv-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        4 أشخاص
                    </span>
                </div>
            </div>

            {{-- Order Layout: Menu + Cart --}}
            <div class="order-layout" x-data="{
                activeCategory: 'all',
                cart: [],
                searchQuery: '',
                categories: [
                    { id: 'all', name: 'الكل' },
                    { id: 'appetizers', name: 'المقبلات' },
                    { id: 'main', name: 'الرئيسية' },
                    { id: 'grills', name: 'المشويات' },
                    { id: 'seafood', name: 'المأكولات البحرية' },
                    { id: 'desserts', name: 'الحلويات' },
                    { id: 'drinks', name: 'المشروبات' },
                ],
                menuItems: [
                    { id: 1, name: 'شوربة العدس', category: 'appetizers', price: 18, description: 'شوربة عدس كريمية بالليمون والكمون' },
                    { id: 2, name: 'سلطة فتوش', category: 'appetizers', price: 22, description: 'خضار طازجة مع دبس الرمان والفتيت' },
                    { id: 3, name: 'حمص بالطحينة', category: 'appetizers', price: 20, description: 'حمص ناعم بالطحينة وزيت الزيتون' },
                    { id: 4, name: 'سمبوسك جبن', category: 'appetizers', price: 25, description: 'معجنات مقرمشة محشوة بالجبن والنعناع' },
                    { id: 5, name: 'دجاج مشوي', category: 'main', price: 75, description: 'نصف دجاجة مشوية بالأعشاب والليمون' },
                    { id: 6, name: 'كفتة مشوية', category: 'grills', price: 65, description: 'لحم مفروم متبل مشوي على الفحم' },
                    { id: 7, name: 'سمك فيليه', category: 'seafood', price: 95, description: 'فيليه سمك طازج محمر بالثوم والليمون' },
                    { id: 8, name: 'ريش خروف', category: 'grills', price: 110, description: 'ريش خروف متبلة مشوية على الفحم' },
                    { id: 9, name: 'برياني دجاج', category: 'main', price: 80, description: 'أرز بسمتي مع دجاج متبل بالبهارات الهندية' },
                    { id: 10, name: 'جمبري مقلي', category: 'seafood', price: 105, description: 'جمبري طازج مقلي بالثوم والفلفل الحار' },
                    { id: 11, name: 'كنافة', category: 'desserts', price: 35, description: 'كنافة بالجبن بالقطر والفستق' },
                    { id: 12, name: 'أم علي', category: 'desserts', price: 30, description: 'حلوى مصرية بالفواكه المجففة والقشدة' },
                    { id: 13, name: 'عصير برتقال', category: 'drinks', price: 20, description: 'عصير برتقال طازج معصور' },
                    { id: 14, name: 'ليمون بالنعناع', category: 'drinks', price: 18, description: 'ليمون طازج مع النعناع والثلج' },
                    { id: 15, name: 'شاهي بالحليب', category: 'drinks', price: 15, description: 'شاي كرك بالزعفران والهيل' },
                ],
                get filteredItems() {
                    return this.menuItems.filter(item => {
                        const matchCat = this.activeCategory === 'all' || item.category === this.activeCategory;
                        const matchSearch = !this.searchQuery || item.name.includes(this.searchQuery);
                        return matchCat && matchSearch;
                    });
                },
                get cartTotal() {
                    return this.cart.reduce((sum, i) => sum + i.price * i.qty, 0);
                },
                get cartCount() {
                    return this.cart.reduce((sum, i) => sum + i.qty, 0);
                },
                addItem(item) {
                    const found = this.cart.find(c => c.id === item.id);
                    if (found) { found.qty++; }
                    else { this.cart.push({ ...item, qty: 1 }); }
                },
                removeItem(item) {
                    const found = this.cart.find(c => c.id === item.id);
                    if (found && found.qty > 1) { found.qty--; }
                    else { this.cart = this.cart.filter(c => c.id !== item.id); }
                },
                getQty(itemId) {
                    const found = this.cart.find(c => c.id === itemId);
                    return found ? found.qty : 0;
                },
                clearCart() { this.cart = []; }
            }">

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

                    {{-- Category Tabs --}}
                    <div class="order-cat-tabs">
                        <template x-for="cat in categories" :key="cat.id">
                            <button type="button" class="order-cat-tab"
                                :class="{ 'is-active': activeCategory === cat.id }"
                                @click="activeCategory = cat.id"
                                x-text="cat.name">
                            </button>
                        </template>
                    </div>

                    {{-- Menu Grid --}}
                    <div class="order-menu-grid">
                        <template x-for="item in filteredItems" :key="item.id">
                            <div class="order-menu-card">
                                <div class="order-menu-card-body">
                                    <div class="order-menu-card-info">
                                        <span class="order-menu-card-name" x-text="item.name"></span>
                                        <span class="order-menu-card-desc" x-text="item.description"></span>
                                        <span class="order-menu-card-price" x-text="item.price + ' ج.م'"></span>
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
                        <div class="order-empty" x-show="filteredItems.length === 0">
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
                                        x-text="(item.price * item.qty).toFixed(0) + ' ج.م'"></span>
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
                            <span class="order-cart-total-val" x-text="cartTotal.toFixed(0) + ' ج.م'"></span>
                        </div>
                        <button type="button" class="order-clear-btn" @click="clearCart()">
                            مسح الطلب
                        </button>
                    </div>

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
                        <textarea class="order-notes-input" rows="3" placeholder="ملاحظات خاصة بالطلب..."></textarea>
                    </div>
                </div>

            </div>{{-- end order-layout --}}

        </div>{{-- end modal-body --}}

        <div class="modal-foot">
            <button type="button" class="btn btn--ghost" @click="orderOpen = false">إلغاء</button>
            <button type="button" class="btn btn--primary" :disabled="cart.length === 0"
                :class="{ 'btn--disabled': cart.length === 0 }">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                تأكيد الطلب
            </button>
        </div>

    </div>
</div>
