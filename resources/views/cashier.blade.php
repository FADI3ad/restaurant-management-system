@extends('layouts.app')
@section('title', 'شاشة الكاشير - نقطة البيع')
@section('shell-class', 'shell')
@section('main-class', 'main')

@section('content')
<link href="{{ asset('assets/css/cashier.css') }}?v={{ time() }}" rel="stylesheet" />


<main class="content">



    <!-- Main Container -->
    <div class="pos-container">
        
        <!-- RIGHT COLUMN: Menu & Items -->
        <div class="pos-menu-side">
            
            <!-- Search & Actions -->
            <div class="pos-search-bar">
                <div class="pos-search-input">
                    <span class="search-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" placeholder="ابحث عن وجبة، مشروب أو رقم عنصر..." x-model="searchQuery">
                </div>
            </div>

            <!-- Categories Slider -->
            <div class="pos-categories-wrapper">
                <button class="category-btn" :class="{ 'is-active': selectedSection === 'all' }" @click="selectedSection = 'all'; selectedSubcategory = 'all';">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>الكل</span>
                </button>
                <template x-for="section in sections" :key="section.id">
                    <button class="category-btn" :class="{ 'is-active': selectedSection == section.id }" @click="selectedSection = section.id; selectedSubcategory = 'all';">
                        <span x-text="section.name"></span>
                    </button>
                </template>
            </div>

            <!-- Subcategories Pills (Optional filter) -->
            <div class="pos-subcategories-wrapper" x-show="selectedSection !== 'all'">
                <div class="subcategory-pill" :class="{ 'is-active': selectedSubcategory === 'all' }" @click="selectedSubcategory = 'all'">
                    الكل
                </div>
                <template x-for="sub in subcategories" :key="sub.id">
                    <div class="subcategory-pill" :class="{ 'is-active': selectedSubcategory == sub.id }" @click="selectedSubcategory = sub.id" x-text="sub.name"></div>
                </template>
            </div>

            <!-- Items Grid -->
            <div class="pos-items-grid-scroll">
                <div class="pos-items-grid">
                    <template x-for="item in filteredItems" :key="item.id">
                        <div class="item-card" @click="addToCart(item)">
                            <!-- Soft Gradient Background instead of missing image -->
                            <div class="item-card-image">
                                <span class="item-card-badge" x-text="'#' + item.id"></span>
                                <svg class="item-placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <div class="item-card-content">
                                <h3 class="item-card-title" x-text="item.name"></h3>
                                <p class="item-card-desc" x-text="item.description"></p>
                                <div class="item-card-footer">
                                    <div class="item-card-price">
                                        <span x-text="item.price.toFixed(2)"></span>
                                        <span>ر.س</span>
                                    </div>
                                    <button class="item-card-add-btn">
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
                    <div class="order-number">الطلب الحالي: <span>#{{ date('dmy') }}-018</span></div>
                    <div class="order-type-tabs">
                        <div class="order-type-btn" :class="{ 'is-active': orderType === 'takeaway' }" @click="orderType = 'takeaway'; selectedTable = ''">سفري</div>
                        <div class="order-type-btn" :class="{ 'is-active': orderType === 'dinein' }" @click="orderType = 'dinein'">محلي</div>
                        <div class="order-type-btn" :class="{ 'is-active': orderType === 'delivery' }" @click="orderType = 'delivery'; selectedTable = ''">توصيل</div>
                    </div>
                </div>

                <div class="order-setup-row">
                    <div class="order-setup-field" x-show="orderType === 'dinein'">
                        <select x-model="selectedTable">
                            <option value="">اختر طاولة</option>
                            <option value="1">طاولة 1</option>
                            <option value="2">طاولة 2</option>
                            <option value="3">طاولة 3</option>
                            <option value="4">طاولة 4</option>
                            <option value="5">طاولة 5</option>
                        </select>
                    </div>
                    <div class="order-setup-field" :style="orderType !== 'dinein' ? 'grid-column: span 2;' : ''">
                        <input type="text" placeholder="رقم جوال العميل..." x-model="customerPhone">
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
                        <p>السلة فارغة. ابدأ بإضافة الوجبات</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <div class="cart-item">
                        <div class="cart-item-details">
                            <div class="cart-item-name" x-text="item.name"></div>
                            <div class="cart-item-price">
                                <span x-text="(item.price * item.quantity).toFixed(2)"></span> ر.س
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
                    <span>الضريبة المضافة (15%):</span>
                    <span><strong x-text="taxAmount.toFixed(2)"></strong> ر.س</span>
                </div>
                <div class="summary-row" style="align-items: center;">
                    <span>الخصم المباشر (ر.س):</span>
                    <input type="number" style="width: 80px; padding: 4px 8px; border-radius: 6px; border: 1px solid var(--border); text-align: center; font-size: 12px; background: var(--bg-card); color: var(--t-base);" x-model="discount">
                </div>
                <div class="summary-row total-row">
                    <span>الإجمالي الكلي:</span>
                    <span class="total-amount"><strong x-text="totalAmount.toFixed(2)"></strong> ر.س</span>
                </div>

                <!-- Action buttons -->
                <div class="pos-actions-grid">
                    <button class="pos-btn pos-btn-danger" @click="clearCart()">إلغاء</button>
                    <button class="pos-btn pos-btn-warning" @click="alert('تم تعليق الطلب وحفظه مؤقتاً')">تعليق</button>
                    <button class="pos-btn pos-btn-success" @click="alert('تمت طباعة طلب المطبخ بنجاح!')">للمطبخ</button>
                    <button class="pos-btn pos-pay-btn" @click="openPayment()">دفع الفاتورة</button>
                </div>
            </div>
        </div>

    </div>

    <!-- PAYMENT MODAL -->
    <div class="modal-overlay" :class="{ 'is-active': paymentModalOpen }" x-show="paymentModalOpen" x-cloak @click.self="paymentModalOpen = false" style="z-index: 110;">
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
                    
                    <!-- Right: Options -->
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
                            <input type="number" class="input" style="font-size: 18px; font-weight: 700; text-align: center; color: var(--success);" x-model="receivedAmount">
                            
                            <div class="cash-suggestions">
                                <button class="suggestion-chip" @click="setReceived(totalAmount)">المبلغ بدقة</button>
                                <button class="suggestion-chip" @click="setReceived(50)">50 ر.س</button>
                                <button class="suggestion-chip" @click="setReceived(100)">100 ر.س</button>
                                <button class="suggestion-chip" @click="setReceived(200)">200 ر.س</button>
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
                                        <span style="color: var(--t-light);" x-text="' (x' + item.quantity + ')'"></span>
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
                                <span>الضريبة:</span>
                                <span x-text="taxAmount.toFixed(2) + ' ر.س'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;" x-show="discount > 0">
                                <span>الخصم:</span>
                                <span style="color: var(--danger);" x-text="'-' + discount + ' ر.س'"></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 700; border-top: 1px dashed var(--border); padding-top: 6px; margin-top: 4px;">
                                <span>الإجمالي النهائي:</span>
                                <span style="color: var(--success);" x-text="totalAmount.toFixed(2) + ' ر.س'"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn btn--ghost" @click="paymentModalOpen = false">رجوع</button>
                <button type="button" class="btn btn--primary" @click="submitPayment()">تأكيد عملية الدفع وطباعة الفاتورة</button>
            </div>

        </div>
    </div>

</main>
@endsection
