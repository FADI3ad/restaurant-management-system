<?php

use App\Models\Reservation;
use App\Models\Order;
use App\Models\User;
use App\Models\Table;
use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $todayReservationsCount;
    public $todayOrdersCount;
    public $employeesCount;
    public $privateTablesCount;

    public $monthlyReservations = [];
    public $totalReservationsYear;
    public $confirmedReservationsYear;
    public $completedReservationsYear;
    public $cancelledReservationsYear;

    public $newTodoText = '';
    public $newTodoPriority = 'low';
    public $newTodoDueText = 'في أي وقت';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->todayReservationsCount = Reservation::whereDate('date', today())->count();

        $this->todayOrdersCount = Order::whereDate('created_at', today())->count();

        $this->employeesCount = User::count();

        $this->privateTablesCount = Table::where('type', 'Private')->count();

        $monthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthly[] = Reservation::whereYear('date', now()->year)
                ->whereMonth('date', $m)
                ->count();
        }
        $this->monthlyReservations = $monthly;

        $this->totalReservationsYear = Reservation::whereYear('date', now()->year)->count();
        $this->confirmedReservationsYear = Reservation::whereYear('date', now()->year)->where('status', 'Confirmed')->count();
        $this->completedReservationsYear = Reservation::whereYear('date', now()->year)->where('status', 'Completed')->count();
        $this->cancelledReservationsYear = Reservation::whereYear('date', now()->year)->where('status', 'Cancelled')->count();
    }

    
    public function getTodosProperty()
    {
        return Todo::orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function addTodo()
    {
        $this->validate([
            'newTodoText' => 'required|string|max:255',
            'newTodoPriority' => 'required|string|in:low,upcoming,urgent,warn',
            'newTodoDueText' => 'required|string|max:100',
        ]);

        Todo::create([
            'text' => $this->newTodoText,
            'priority' => $this->newTodoPriority,
            'due_text' => $this->newTodoDueText,
            'is_completed' => false,
        ]);

        $this->newTodoText = '';
        $this->newTodoPriority = 'low';
        $this->newTodoDueText = 'في أي وقت';

        $this->dispatch('close-todo-modal');
        $this->loadData();
    }

    public function toggleTodo($id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            $todo->is_completed = !$todo->is_completed;
            $todo->save();
        }
        $this->loadData();
    }

    public function deleteTodo($id)
    {
        $todo = Todo::find($id);
        if ($todo) {
            $todo->delete();
        }
        $this->loadData();
    }
};
?>

<div>
    <style>
        .shortcuts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-top: 1.25rem;
        }
        .shortcut-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 1.25rem;
            border-radius: 12px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
        }
        .shortcut-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: 0 8px 16px var(--primary-ring);
        }
        .shortcut-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: var(--primary-soft);
            color: var(--primary);
            transition: all 0.25s;
        }
        .shortcut-card:hover .shortcut-icon {
            background: var(--primary);
            color: #fff;
        }
        .shortcut-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .shortcut-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }
        .shortcut-desc {
            font-size: 0.8rem;
            color: var(--text-light);
        }
        .todo-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
        }
        .todo-item:last-child {
            border-bottom: none;
        }
        .todo-item.is-done .todo-text {
            text-decoration: line-through;
            opacity: 0.6;
        }
        .todo-delete-btn {
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            margin-right: auto;
            margin-left: 0;
            opacity: 0.5;
            display: flex;
            align-items: center;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .todo-delete-btn:hover {
            opacity: 1;
            background: var(--primary-soft);
            color: var(--danger);
        }
    </style>

    <!-- KPI Grid -->
    <section class="kpi-grid anim-stagger" aria-label="Key metrics" style="margin-bottom: 2rem;">
        <!-- Today's Reservations -->
        <article class="kpi-card c-success">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="kpi-label">حجوزات اليوم</div>
                </div>
            </div>
            <div class="kpi-value">{{ $todayReservationsCount }}</div>
            <div class="kpi-compare">
                <svg class="up" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                حجوزات نشطة اليوم
            </div>
        </article>

        <!-- Today's Orders -->
        <article class="kpi-card c-danger">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </div>
                    <div class="kpi-label">طلبات اليوم</div>
                </div>
            </div>
            <div class="kpi-value">{{ $todayOrdersCount }}</div>
            <div class="kpi-compare">
                <svg class="info" viewBox="0 0 24 24">
                    <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                طلبات مسجلة اليوم
            </div>
        </article>

        <!-- Employees Count -->
        <article class="kpi-card c-purple">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="kpi-label">عدد العاملين</div>
                </div>
            </div>
            <div class="kpi-value">{{ $employeesCount }}</div>
            <div class="kpi-compare">
                <svg class="info" viewBox="0 0 24 24">
                    <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                حساب موظف مسجل
            </div>
        </article>

        <!-- Private Tables Count -->
        <article class="kpi-card c-primary">
            <div class="kpi-top">
                <div class="kpi-identity">
                    <div class="kpi-icon primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="3" rx="1"></rect>
                            <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"></path>
                        </svg>
                    </div>
                    <div class="kpi-label">التربيزات الخاصة</div>
                </div>
            </div>
            <div class="kpi-value">{{ $privateTablesCount }}</div>
            <div class="kpi-compare">
                <svg class="info" viewBox="0 0 24 24">
                    <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                طاولة VIP / خاصة
            </div>
        </article>
    </section>

    <!-- Main Content Row -->
    <div class="grid" style="margin-bottom: 2rem;">
        <!-- Monthly Reservations Chart -->
        <section class="col-6 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">الأداء السنوي</span>
                    <h2 class="card-title">عدد الحجوزات الشهرية</h2>
                </div>
                <span class="card-action">{{ now()->year }}</span>
            </div>
            
            <div x-data="{
                init() {
                    const initChart = () => {
                        const ctx = document.getElementById('monthlyReservationsChart');
                        if (!ctx) return;
                        const values = {{ json_encode($monthlyReservations) }};
                        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#4f46e5';
                        
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
                                datasets: [{
                                    label: 'الحجوزات',
                                    data: values,
                                    borderColor: primaryColor,
                                    backgroundColor: primaryColor + '24',
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 0,
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: primaryColor,
                                    borderWidth: 2.5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: {
                                        grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                        ticks: { maxTicksLimit: 5 }
                                    },
                                    x: {
                                        grid: { display: false }
                                    }
                                }
                            }
                        });
                    };
                    if (window.Chart) {
                        initChart();
                    } else {
                        window.addEventListener('load', initChart);
                    }
                }
            }" class="chart-canvas-wrap" style="height: 240px; position: relative;">
                <canvas id="monthlyReservationsChart"></canvas>
            </div>

            <div class="monthly-footer">
                <div class="stat-cell">
                    <div class="stat-cell-label">إجمالي حجوزات العام</div>
                    <div class="stat-cell-value">{{ $totalReservationsYear }}</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-cell-label">مؤكدة</div>
                    <div class="stat-cell-value" style="color: var(--success)">{{ $confirmedReservationsYear }}</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-cell-label">مكتملة</div>
                    <div class="stat-cell-value" style="color: var(--info)">{{ $completedReservationsYear }}</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-cell-label">ملغاة</div>
                    <div class="stat-cell-value" style="color: var(--danger)">{{ $cancelledReservationsYear }}</div>
                </div>
            </div>
        </section>

        <!-- Functional Todo List -->
        <section class="col-6 card" x-data="{ todoModalOpen: false }">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">شخصي</span>
                    <h2 class="card-title">قائمة المهام</h2>
                </div>
                <button type="button" class="card-action" @click="todoModalOpen = true" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 4px; color: var(--primary);">
                    <span>إضافة مهمة</span>
                    <svg viewBox="0 0 24 24" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </button>
            </div>
            
            <ul class="todo-list">
                @forelse ($this->todos as $todo)
                    <li class="todo-item {{ $todo->is_completed ? 'is-done' : '' }}">
                        <input type="checkbox" class="todo-check" id="todo-{{ $todo->id }}" 
                               wire:click="toggleTodo({{ $todo->id }})" 
                               {{ $todo->is_completed ? 'checked' : '' }} />
                        <label for="todo-{{ $todo->id }}" class="todo-text">{{ $todo->text }}</label>
                        
                        @if ($todo->is_completed)
                            <span class="todo-badge done">مكتمل</span>
                        @else
                            <span class="todo-badge {{ $todo->priority }}">{{ $todo->due_text }}</span>
                        @endif

                        <button type="button" wire:click="deleteTodo({{ $todo->id }})" class="todo-delete-btn" title="حذف المهمة">
                            <svg viewBox="0 0 24 24" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </li>
                @empty
                    <li class="todo-item" style="justify-content: center; padding: 3rem; color: var(--text-light); text-align: center;">
                        <div>
                            <svg viewBox="0 0 24 24" style="width: 48px; height: 48px; opacity: 0.3; margin-bottom: 8px;" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 12l2 2 4-4M7.83 12a4 4 0 110-8h8.34a4 4 0 110 8H7.83z"></path>
                            </svg>
                            <p style="margin: 0; font-size: 0.9rem;">لا توجد مهام حالياً. أضف مهمة جديدة للبدء!</p>
                        </div>
                    </li>
                @endforelse
            </ul>

            <!-- Add Todo Modal -->
            <div class="modal-overlay is-active" x-show="todoModalOpen" x-cloak @click.self="todoModalOpen = false;"
                 x-transition.opacity.duration.200ms>
                <div class="modal-content modal-md">
                    <x-modal-head-component title="إضافة مهمة جديدة" />
                    
                    <form wire:submit.prevent="addTodo" @close-todo-modal.window="todoModalOpen = false">
                        <div class="modal-body modal-form-grid">
                            <div class="field span-2">
                                <label class="field-label">المهمة <span class="req">*</span></label>
                                <input wire:model="newTodoText" type="text" class="input" placeholder="مثال: الاتصال بالمورد لشراء مستلزمات">
                                @error('newTodoText')
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
                                <label class="field-label">الأولوية <span class="req">*</span></label>
                                <select wire:model="newTodoPriority" class="select">
                                    <option value="low">منخفضة (Low)</option>
                                    <option value="upcoming">قادمة (Upcoming)</option>
                                    <option value="warn">متوسطة (Warn)</option>
                                    <option value="urgent">عاجلة (Urgent)</option>
                                </select>
                            </div>

                            <div class="field">
                                <label class="field-label">وقت الاستحقاق / التفاصيل المكتوبة <span class="req">*</span></label>
                                <input wire:model="newTodoDueText" type="text" class="input" placeholder="مثال: غداً، في أي وقت، خلال ساعة...">
                            </div>
                        </div>
                        <div class="modal-foot">
                            <button type="button" class="btn btn--ghost" @click="todoModalOpen = false">إلغاء</button>
                            <button type="submit" class="btn btn--primary">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- Quick Links & Shortcuts Card -->
    <section class="card col-12" style="margin-top: 2rem;">
        <div class="card-head">
            <div class="card-title-wrap">
                <span class="eyebrow">التوجيه السريع</span>
                <h2 class="card-title">الروابط السريعة والاختصارات</h2>
            </div>
        </div>
        
        <div class="shortcuts-grid">
            <!-- Cashier -->
            <a href="{{ route('cashier.index') }}" class="shortcut-card">
                <div class="shortcut-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <line x1="12" y1="4" x2="12" y2="20"></line>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">شاشة الكاشير</span>
                    <span class="shortcut-desc">نظام المبيعات السريعة والطلبات</span>
                </div>
            </a>

            <!-- Orders -->
            <a href="{{ route('orders.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="color: var(--danger); background: rgba(239, 68, 68, 0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">طلبات اليوم</span>
                    <span class="shortcut-desc">متابعة وحالة الطلبات النشطة</span>
                </div>
            </a>

            <!-- Reservations -->
            <a href="{{ route('reservations.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="color: var(--success); background: rgba(34, 197, 94, 0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">الحجوزات والتخزين</span>
                    <span class="shortcut-desc">إدارة وتتبع حجوزات الطاولات</span>
                </div>
            </a>

            <!-- Tables -->
            <a href="{{ route('tables.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="color: var(--primary); background: rgba(79, 70, 229, 0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <rect x="3" y="11" width="18" height="3" rx="1"></rect>
                        <path d="M5 14v5M19 14v5M5 8V5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"></path>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">إدارة الطاولات</span>
                    <span class="shortcut-desc">مخطط وسعة الطاولات الحالية</span>
                </div>
            </a>

            <!-- Full Menu -->
            <a href="{{ route('menu.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="color: var(--purple); background: rgba(168, 85, 247, 0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">عرض المينيو الكامل</span>
                    <span class="shortcut-desc">استعراض كافة الوجبات والأصناف</span>
                </div>
            </a>

            <!-- Items -->
            <a href="{{ route('items.index') }}" class="shortcut-card">
                <div class="shortcut-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
                        <path d="M7 2v20"></path>
                        <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">عناصر المينيو</span>
                    <span class="shortcut-desc">تعديل وإضافة وجبات ومكوناتها</span>
                </div>
            </a>

            <!-- Categories -->
            <a href="{{ route('categories.index') }}" class="shortcut-card">
                <div class="shortcut-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                        <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path>
                        <line x1="6" y1="2" x2="6" y2="4"></line>
                        <line x1="10" y1="2" x2="10" y2="4"></line>
                        <line x1="14" y1="2" x2="14" y2="4"></line>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">الأصناف الرئيسية</span>
                    <span class="shortcut-desc">أصناف الطعام والمشروبات الكبرى</span>
                </div>
            </a>

            <!-- Subcategories -->
            <a href="{{ route('subcategories.index') }}" class="shortcut-card">
                <div class="shortcut-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M12 2v20"></path>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">الأصناف الفرعية</span>
                    <span class="shortcut-desc">تصنيفات دقيقة ومجموعات منوعة</span>
                </div>
            </a>

            <!-- Sections -->
            <a href="{{ route('sections.index') }}" class="shortcut-card">
                <div class="shortcut-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">أقسام المينيو</span>
                    <span class="shortcut-desc">التقسيمات الكبرى للمطعم</span>
                </div>
            </a>

            <!-- Expenses -->
            <a href="{{ route('expenses.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="color: var(--warning); background: rgba(245, 158, 11, 0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">إدارة المصروفات</span>
                    <span class="shortcut-desc">تتبع التكاليف والمصاريف اليومية</span>
                </div>
            </a>

            <!-- Users -->
            <a href="{{ route('users.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="color: var(--purple); background: rgba(168, 85, 247, 0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">إدارة المستخدمين والعاملين</span>
                    <span class="shortcut-desc">إدارة صلاحيات وحسابات الموظفين</span>
                </div>
            </a>

            <!-- Settings -->
            <a href="{{ route('settings.index') }}" class="shortcut-card">
                <div class="shortcut-icon" style="color: var(--text-light); background: rgba(156, 163, 175, 0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <div class="shortcut-info">
                    <span class="shortcut-title">إعدادات النظام العامة</span>
                    <span class="shortcut-desc">تعديل الألوان والشعار والخطوط</span>
                </div>
            </a>
        </div>
    </section>
</div>
