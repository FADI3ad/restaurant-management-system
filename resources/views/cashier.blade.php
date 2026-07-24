@extends('layouts.app')
@section('title', 'شاشة الكاشير - نقطة البيع')
@section('shell-class', 'shell')
@section('main-class', 'main')

@section('content')
<link href="{{ asset('assets/css/cashier.css') }}?v={{ time() }}" rel="stylesheet" />

<main class="content" x-data="cashierApp()" x-init="init()">

    <!-- Main Container -->
    <div class="pos-container anim-fade-up">

        <!-- RIGHT COLUMN: Menu & Items -->
        <div class="pos-menu-side">

            <!-- Search Bar -->
            <div class="pos-search-bar">
                <div class="pos-search-input">
                    <span class="search-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" placeholder="ابحث عن وجبة أو مشروب..." x-model="searchQuery">
                </div>
            </div>

            <!-- Sections Filter (Sections = top-level menu groupings) -->
            <div class="pos-categories-wrapper">
                <button class="category-btn" :class="{ 'is-active': selectedSection === 'all' }"
                    @click="selectedSection = 'all'; selectedSubcategory = 'all'">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>الكل</span>
                </button>
                <template x-for="section in sections" :key="section.id">
                    <button class="category-btn"
                        :class="{ 'is-active': selectedSection == section.id }"
                        @click="selectedSection = section.id; selectedSubcategory = 'all'">
                        <span x-text="section.name"></span>
                    </button>
                </template>
            </div>

            <!-- Subcategories Pills (derived from selected section's categories) -->
            <div class="pos-subcategories-wrapper" x-show="selectedSection !== 'all' && activeSubcategories.length > 0">
                <div class="subcategory-pill"
                    :class="{ 'is-active': selectedSubcategory === 'all' }"
                    @click="selectedSubcategory = 'all'">الكل</div>
                <template x-for="sub in activeSubcategories" :key="sub.id">
                    <div class="subcategory-pill"
                        :class="{ 'is-active': selectedSubcategory == sub.id }"
                        @click="selectedSubcategory = sub.id"
                        x-text="sub.name"></div>
                </template>
            </div>

            <!-- Items Grid -->
            <div class="pos-items-grid-scroll">
                <div class="pos-items-grid">
                    <template x-if="filteredItems.length === 0">
                        <div class="items-empty-state" style="grid-column: 1/-1; text-align:center; padding: 60px 20px; color: var(--t-light);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" style="width:52px;height:52px;opacity:.4;margin-bottom:12px;">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <p>لا توجد عناصر مطابقة للبحث</p>
                        </div>
                    </template>
                    <template x-for="item in filteredItems" :key="item.id">
                        <div class="item-card" @click="addToCart(item)">
                            <div class="item-card-image">
                                <span class="item-card-badge" x-text="'#' + item.id"></span>
                                <svg class="item-placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                                    <path d="M18.364 5.636A9 9 0 1 0 5.636 18.364 9 9 0 0 0 18.364 5.636z"/>
                                    <path d="M8 12h8M12 8v8"/>
                                </svg>
                            </div>
                            <div class="item-card-content">
                                <h3 class="item-card-title" x-text="item.name"></h3>
                                <p class="item-card-desc" x-text="item.description || ''"></p>
                                <div class="item-card-footer">
                                    <div class="item-card-price">
                                        <span x-text="parseFloat(item.price).toFixed(2)"></span>
                                        <span>ر.س</span>
                                    </div>
                                    <button class="item-card-add-btn" @click.stop="addToCart(item)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- LEFT COLUMN: Cart / Current Order -->
        <div class="pos-order-side">
            <div class="pos-order-header">
                <div class="order-info-row">
                    <div class="order-number">
                        الطلب الجديد
                        <span x-text="'#' + orderNumberPreview"></span>
                    </div>
                    <div class="cashier-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        تيك أوي
                    </div>
                </div>

                <!-- Customer Phone -->
                <div class="order-customer-row">
                    <div class="order-customer-field">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" style="color:var(--t-light); flex-shrink:0;">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <input type="text"
                            placeholder="رقم جوال العميل (اختياري)..."
                            x-model="customerPhone"
                            id="cashier-customer-phone"
                            maxlength="15">
                    </div>
                </div>
            </div>

            <!-- Cart Items List -->
            <div class="pos-cart-items">
                <template x-if="cart.length === 0">
                    <div class="cart-empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <p>السلة فارغة — اختر وجبة من القائمة</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <div class="cart-item">
                        <div class="cart-item-details">
                            <div class="cart-item-name" x-text="item.name"></div>
                            <div class="cart-item-price">
                                <span x-text="parseFloat(item.price).toFixed(2)"></span> ر.س × <span x-text="item.quantity"></span>
                                = <strong x-text="(item.price * item.quantity).toFixed(2)"></strong> ر.س
                            </div>
                        </div>
                        <div class="cart-item-qty-controls">
                            <button class="qty-btn" @click="updateQuantity(item.id, -1)">-</button>
                            <span class="qty-val" x-text="item.quantity"></span>
                            <button class="qty-btn" @click="updateQuantity(item.id, 1)">+</button>
                        </div>
                        <span class="cart-item-remove-btn" @click="removeFromCart(item.id)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </span>
                    </div>
                </template>
            </div>

            <!-- Cart Bill Summary -->
            <div class="pos-order-summary">
                <div class="summary-row">
                    <span>المجموع الفرعي:</span>
                    <span><strong x-text="subTotal.toFixed(2)"></strong> ر.س</span>
                </div>
                <div class="summary-row">
                    <span>ضريبة القيمة المضافة (15%):</span>
                    <span><strong x-text="taxAmount.toFixed(2)"></strong> ر.س</span>
                </div>
                <div class="summary-row" style="align-items: center;">
                    <span>خصم مباشر (ر.س):</span>
                    <input type="number" min="0" step="0.5"
                        style="width: 80px; padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border); text-align: center; font-size: 12px; background: var(--bg-card); color: var(--t-base);"
                        x-model="discount">
                </div>
                <div class="summary-row total-row">
                    <span>الإجمالي الكلي:</span>
                    <span class="total-amount"><strong x-text="totalAmount.toFixed(2)"></strong> ر.س</span>
                </div>

                <!-- Action Buttons -->
                <div class="pos-actions-grid">
                    <button class="pos-btn pos-btn-danger" @click="clearCart()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        إلغاء
                    </button>
                    <button class="pos-btn pos-btn-warning" @click="printKitchen()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        مطبخ
                    </button>
                    <button class="pos-btn pos-pay-btn" @click="openPayment()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        دفع الفاتورة
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- PAYMENT MODAL -->
    <div class="modal-overlay" :class="{ 'is-active': paymentModalOpen }"
        x-show="paymentModalOpen" x-cloak
        @click.self="paymentModalOpen = false" style="z-index: 110;">
        <div class="modal-content modal-md">

            <div class="modal-head">
                <h3 class="modal-title">إتمام عملية الدفع</h3>
                <span class="modal-close" @click="paymentModalOpen = false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            </div>

            <div class="modal-body">
                <div class="payment-modal-grid">

                    <!-- Right: Payment Options -->
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <label class="field-label" style="font-weight: 700;">طريقة الدفع</label>
                        <div class="payment-methods-grid">
                            <div class="payment-method-card" :class="{ 'is-active': paymentMethod === 'cash' }" @click="paymentMethod = 'cash'">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                                    <circle cx="12" cy="12" r="2"></circle>
                                    <path d="M6 12h.01M18 12h.01"></path>
                                </svg>
                                <span>نقدي</span>
                            </div>
                            <div class="payment-method-card" :class="{ 'is-active': paymentMethod === 'card' }" @click="paymentMethod = 'card'">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                                <span>مدى / بطاقة</span>
                            </div>
                        </div>

                        <div x-show="paymentMethod === 'cash'">
                            <label class="field-label">المبلغ المستلم من العميل</label>
                            <input type="number" class="input"
                                style="font-size: 18px; font-weight: 700; text-align: center; color: var(--success);"
                                x-model="receivedAmount">
                            <div class="cash-suggestions">
                                <button class="suggestion-chip" @click="setReceived(totalAmount)">بالضبط</button>
                                <button class="suggestion-chip" @click="setReceived(50)">50</button>
                                <button class="suggestion-chip" @click="setReceived(100)">100</button>
                                <button class="suggestion-chip" @click="setReceived(200)">200</button>
                            </div>
                        </div>

                        <div class="payment-calculator">
                            <div class="calc-display">
                                <div class="calc-box">
                                    <div class="calc-label">المطلوب سداده</div>
                                    <div class="calc-value"><span x-text="totalAmount.toFixed(2)"></span> ر.س</div>
                                </div>
                                <div class="calc-box" x-show="paymentMethod === 'cash'">
                                    <div class="calc-label">المتبقي (المسترجع)</div>
                                    <div class="calc-value change-positive"><span x-text="changeAmount.toFixed(2)"></span> ر.س</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Left: Invoice Summary -->
                    <div class="payment-summary-box">
                        <h4 style="margin: 0 0 12px 0; border-bottom: 1px solid var(--border); padding-bottom: 8px; font-size: 14px; font-weight: 700;">ملخص الطلب</h4>
                        <div style="max-height: 200px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                            <template x-for="item in cart" :key="item.id">
                                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                                    <div>
                                        <span x-text="item.name"></span>
                                        <span style="color: var(--t-light);" x-text="' (×' + item.quantity + ')'"></span>
                                    </div>
                                    <span x-text="(item.price * item.quantity).toFixed(2) + ' ر.س'"></span>
                                </div>
                            </template>
                        </div>

                        <div style="border-top: 1px solid var(--border); padding-top: 8px; display: flex; flex-direction: column; gap: 6px; font-size: 12.5px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>المجموع:</span>
                                <span x-text="subTotal.toFixed(2) + ' ر.س'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>الضريبة (15%):</span>
                                <span x-text="taxAmount.toFixed(2) + ' ر.س'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;" x-show="discount > 0">
                                <span>الخصم:</span>
                                <span style="color: var(--danger);" x-text="'-' + parseFloat(discount).toFixed(2) + ' ر.س'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 700; border-top: 1px dashed var(--border); padding-top: 6px; margin-top: 4px;">
                                <span>الإجمالي النهائي:</span>
                                <span style="color: var(--success);" x-text="totalAmount.toFixed(2) + ' ر.س'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--t-light); margin-top: 6px;" x-show="customerPhone">
                                <span>رقم العميل:</span>
                                <span x-text="customerPhone"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="paymentModalOpen = false" :disabled="submitting">رجوع</button>
                <button type="button" class="btn btn--primary" @click="submitPayment()" :disabled="submitting || cart.length === 0">
                    <span x-show="!submitting">تأكيد الدفع وحفظ الطلب</span>
                    <span x-show="submitting">جاري الحفظ...</span>
                </button>
            </div>

        </div>
    </div>

    <!-- SUCCESS MODAL -->
    <div class="modal-overlay" :class="{ 'is-active': successModalOpen }"
        x-show="successModalOpen" x-cloak style="z-index: 120;">
        <div class="modal-content" style="max-width: 400px; text-align: center; padding: 40px 30px;">
            <div style="width: 72px; height: 72px; background: var(--success-soft, rgba(16,185,129,0.12)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2.5" width="36" height="36">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 8px; color: var(--t-base);">تم حفظ الطلب بنجاح!</h3>
            <p style="color: var(--t-muted); font-size: 14px; margin-bottom: 16px;">رقم الطلب: <strong style="color: var(--primary); font-size: 16px;" x-text="lastOrderNumber"></strong></p>
            <p style="color: var(--success); font-size: 18px; font-weight: 700;" x-text="'الإجمالي: ' + lastOrderTotal + ' ر.س'"></p>
            <button class="btn btn--primary" style="margin-top: 24px; width: 100%;" @click="successModalOpen = false; clearCart()">
                طلب جديد
            </button>
        </div>
    </div>

</main>

<script>
function cashierApp() {
    return {
        // ===== Data from backend (PHP → JSON) =====
        sections:   @json($sections),
        allItems:   @json($allItems),

        // ===== Filter State =====
        selectedSection:    'all',
        selectedSubcategory: 'all',
        searchQuery:         '',

        // ===== Cart =====
        cart:         [],
        customerPhone: '',
        discount:      0,
        paymentMethod: 'cash',
        receivedAmount: 0,

        // ===== Modals =====
        paymentModalOpen: false,
        successModalOpen: false,
        submitting:       false,

        // ===== Success Result =====
        lastOrderNumber: '',
        lastOrderTotal:  '',

        // ===== Order number preview =====
        orderNumberPreview: '---',

        // ===== Init =====
        init() {
            // Generate a preview order number for display
            const d = new Date();
            const yy = String(d.getFullYear()).slice(-2);
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            this.orderNumberPreview = `ORD-${yy}${mm}${dd}-???`;
        },

        // ===== Computed: Subcategories from selected section =====
        get activeSubcategories() {
            if (this.selectedSection === 'all') return [];
            const section = this.sections.find(s => s.id == this.selectedSection);
            if (!section) return [];
            const subs = [];
            (section.categories || []).forEach(cat => {
                (cat.subcategories || []).forEach(sub => subs.push(sub));
            });
            return subs;
        },

        // ===== Computed: Filtered items =====
        get filteredItems() {
            let items = [];

            if (this.searchQuery.trim()) {
                // Search across all items
                const q = this.searchQuery.trim().toLowerCase();
                items = this.allItems.filter(item =>
                    (item.name && item.name.toLowerCase().includes(q)) ||
                    (item.description && item.description.toLowerCase().includes(q))
                );
            } else if (this.selectedSection === 'all') {
                // All items from all sections
                this.sections.forEach(section => {
                    (section.categories || []).forEach(cat => {
                        (cat.subcategories || []).forEach(sub => {
                            (sub.items || []).forEach(item => items.push(item));
                        });
                    });
                });
            } else if (this.selectedSubcategory !== 'all') {
                // Items from specific subcategory
                const section = this.sections.find(s => s.id == this.selectedSection);
                if (section) {
                    (section.categories || []).forEach(cat => {
                        (cat.subcategories || []).forEach(sub => {
                            if (sub.id == this.selectedSubcategory) {
                                (sub.items || []).forEach(item => items.push(item));
                            }
                        });
                    });
                }
            } else {
                // All items from selected section
                const section = this.sections.find(s => s.id == this.selectedSection);
                if (section) {
                    (section.categories || []).forEach(cat => {
                        (cat.subcategories || []).forEach(sub => {
                            (sub.items || []).forEach(item => items.push(item));
                        });
                    });
                }
            }

            return items;
        },

        // ===== Computed: Totals =====
        get subTotal() {
            return this.cart.reduce((sum, i) => sum + (parseFloat(i.price) * i.quantity), 0);
        },
        get taxAmount() {
            return this.subTotal * 0.15;
        },
        get totalAmount() {
            const disc = parseFloat(this.discount) || 0;
            return Math.max(0, this.subTotal + this.taxAmount - disc);
        },
        get changeAmount() {
            const recv = parseFloat(this.receivedAmount) || 0;
            return Math.max(0, recv - this.totalAmount);
        },

        // ===== Cart Methods =====
        addToCart(item) {
            const existing = this.cart.find(i => i.id === item.id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({ ...item, quantity: 1 });
            }
        },
        updateQuantity(itemId, delta) {
            const item = this.cart.find(i => i.id === itemId);
            if (!item) return;
            item.quantity += delta;
            if (item.quantity <= 0) this.removeFromCart(itemId);
        },
        removeFromCart(itemId) {
            this.cart = this.cart.filter(i => i.id !== itemId);
        },
        clearCart() {
            this.cart          = [];
            this.customerPhone = '';
            this.discount      = 0;
            this.receivedAmount = 0;
        },

        // ===== Payment =====
        setReceived(amount) {
            this.receivedAmount = parseFloat(amount).toFixed(2);
        },
        openPayment() {
            if (this.cart.length === 0) {
                alert('السلة فارغة! أضف عناصر أولاً.');
                return;
            }
            this.receivedAmount  = this.totalAmount.toFixed(2);
            this.paymentModalOpen = true;
        },
        async submitPayment() {
            if (this.submitting) return;
            this.submitting = true;

            const payload = {
                cart: this.cart.map(i => ({ id: i.id, qty: i.quantity })),
                customer_phone: this.customerPhone,
                payment_method: this.paymentMethod,
                discount:       parseFloat(this.discount) || 0,
                _token:         document.querySelector('meta[name="csrf-token"]').content,
            };

            try {
                const res = await fetch('{{ route("cashier.store") }}', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body:    JSON.stringify(payload),
                });
                const data = await res.json();

                if (data.success) {
                    this.lastOrderNumber  = data.order_number;
                    this.lastOrderTotal   = parseFloat(data.total).toFixed(2);
                    this.paymentModalOpen = false;
                    this.successModalOpen = true;
                } else {
                    alert('خطأ: ' + (data.message || 'فشل حفظ الطلب'));
                }
            } catch (err) {
                alert('خطأ في الاتصال بالخادم. حاول مجدداً.');
                console.error(err);
            } finally {
                this.submitting = false;
            }
        },

        // ===== Kitchen Print =====
        printKitchen() {
            if (this.cart.length === 0) {
                alert('السلة فارغة!');
                return;
            }
            alert('✅ تم إرسال الطلب للمطبخ:\n' + this.cart.map(i => `- ${i.name} × ${i.quantity}`).join('\n'));
        },
    };
}
</script>

@endsection
