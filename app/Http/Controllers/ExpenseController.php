<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('user');

        $period = $request->get('period', 'all');
        $category = $request->get('category');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Apply Category Filter
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        // Apply Date/Period Filter
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        if ($period === 'today') {
            $query->whereDate('expense_date', $today);
        } elseif ($period === 'week') {
            $query->where('expense_date', '>=', $startOfWeek);
        } elseif ($period === 'month') {
            $query->where('expense_date', '>=', $startOfMonth);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }

        $filteredTotal = (clone $query)->sum('amount');

        $expenses = $query->orderBy('expense_date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // Calculate KPI Summaries
        $stats = [
            'total_today' => Expense::whereDate('expense_date', $today)->sum('amount'),
            'total_week' => Expense::where('expense_date', '>=', $startOfWeek)->sum('amount'),
            'total_month' => Expense::where('expense_date', '>=', $startOfMonth)->sum('amount'),
            'electricity_month' => Expense::where('category', 'electricity')->where('expense_date', '>=', $startOfMonth)->sum('amount'),
            'water_month' => Expense::where('category', 'water')->where('expense_date', '>=', $startOfMonth)->sum('amount'),
            'gas_month' => Expense::where('category', 'gas')->where('expense_date', '>=', $startOfMonth)->sum('amount'),
            'filtered_total' => $filteredTotal,
        ];

        return view('expenses', compact('expenses', 'stats', 'period', 'category', 'startDate', 'endDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:electricity,water,gas,rent,maintenance,supplies,salaries,other',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'تم إضافة المصروف بنجاح.');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:electricity,water,gas,rent,maintenance,supplies,salaries,other',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'تم تعديل المصروف بنجاح.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'تم حذف المصروف بنجاح.');
    }
}
