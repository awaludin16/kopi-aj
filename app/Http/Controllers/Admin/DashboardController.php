<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->query('period', 'today');
        $customStart = $request->query('start_date');
        $customEnd = $request->query('end_date');

        $now = Carbon::now('Asia/Jakarta');

        // Tentukan Rentang Tanggal berdasarkan Periode
        switch ($period) {
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                $periodLabel = 'Kemarin (' . $startDate->translatedFormat('d F Y') . ')';
                break;

            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                $periodLabel = 'Minggu Ini (' . $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y') . ')';
                break;

            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodLabel = 'Bulan Ini (' . $now->translatedFormat('F Y') . ')';
                break;

            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodLabel = 'Tahun Ini (' . $now->translatedFormat('Y') . ')';
                break;

            case 'custom':
                $startDate = $customStart ? Carbon::parse($customStart, 'Asia/Jakarta')->startOfDay() : $now->copy()->startOfDay();
                $endDate = $customEnd ? Carbon::parse($customEnd, 'Asia/Jakarta')->endOfDay() : $now->copy()->endOfDay();
                $periodLabel = $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
                break;

            case 'today':
            default:
                $period = 'today';
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'Hari Ini (' . $now->translatedFormat('d F Y') . ')';
                break;
        }

        // 1. Kartu Metrik Periode
        $periodIncome = Order::where('status', OrderStatus::PAID)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        $periodOrdersCount = Order::whereIn('status', [OrderStatus::SUBMITTED, OrderStatus::PAID])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Pending butuh aksi kasir (selalu real-time saat ini)
        $pendingOrdersCount = Order::where('status', OrderStatus::SUBMITTED)
            ->whereHas('payment', function ($q) {
                $q->where('status', PaymentStatus::PENDING);
            })
            ->count();

        $totalMenusCount = Menu::count();

        // 2. Data Grafik Penjualan Sesuai Periode
        $chartLabels = [];
        $chartIncomeData = [];
        $chartOrderData = [];

        if (in_array($period, ['today', 'yesterday'])) {
            // Grafik per interval 2 jam (08:00 - 22:00)
            $hours = [8, 10, 12, 14, 16, 18, 20, 22];
            foreach ($hours as $hour) {
                $timeSlotStart = $startDate->copy()->setHour($hour)->setMinute(0)->setSecond(0);
                $timeSlotEnd = $timeSlotStart->copy()->addHours(2)->subSecond();

                $chartLabels[] = sprintf('%02d:00', $hour);

                $slotIncome = Order::where('status', OrderStatus::PAID)
                    ->whereBetween('created_at', [$timeSlotStart, $timeSlotEnd])
                    ->sum('total');

                $slotCount = Order::where('status', OrderStatus::PAID)
                    ->whereBetween('created_at', [$timeSlotStart, $timeSlotEnd])
                    ->count();

                $chartIncomeData[] = (float) $slotIncome;
                $chartOrderData[] = $slotCount;
            }
        } elseif ($period === 'this_week') {
            // Grafik 7 Hari dalam Minggu Ini
            for ($d = 0; $d < 7; $d++) {
                $day = $startDate->copy()->addDays($d);
                $chartLabels[] = $day->translatedFormat('D, d M');

                $dayIncome = Order::where('status', OrderStatus::PAID)
                    ->whereDate('created_at', $day)
                    ->sum('total');

                $chartIncomeData[] = (float) $dayIncome;
            }
        } elseif ($period === 'this_month') {
            // Grafik per interval 5 hari di bulan ini
            $totalDays = $startDate->daysInMonth;
            for ($d = 1; $d <= $totalDays; $d += ($totalDays > 20 ? 3 : 2)) {
                $currentDay = $startDate->copy()->setDay(min($d, $totalDays));
                $nextDay = $startDate->copy()->setDay(min($d + ($totalDays > 20 ? 2 : 1), $totalDays))->endOfDay();

                $chartLabels[] = $currentDay->format('d M');

                $intervalIncome = Order::where('status', OrderStatus::PAID)
                    ->whereBetween('created_at', [$currentDay->startOfDay(), $nextDay])
                    ->sum('total');

                $chartIncomeData[] = (float) $intervalIncome;
            }
        } elseif ($period === 'this_year') {
            // Grafik 12 Bulan
            for ($m = 1; $m <= 12; $m++) {
                $monthDate = $startDate->copy()->setMonth($m)->startOfMonth();
                $chartLabels[] = $monthDate->translatedFormat('M');

                $monthIncome = Order::where('status', OrderStatus::PAID)
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $m)
                    ->sum('total');

                $chartIncomeData[] = (float) $monthIncome;
            }
        } else {
            // Custom Range: Bagi menjadi maksimal 7 interval
            $diffDays = $startDate->diffInDays($endDate) ?: 1;
            $step = max(1, (int) ceil($diffDays / 7));

            for ($d = 0; $d <= $diffDays; $d += $step) {
                $dayStart = $startDate->copy()->addDays($d)->startOfDay();
                $dayEnd = $startDate->copy()->addDays(min($d + $step - 1, $diffDays))->endOfDay();

                $chartLabels[] = $dayStart->translatedFormat('d M');

                $income = Order::where('status', OrderStatus::PAID)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum('total');

                $chartIncomeData[] = (float) $income;
            }
        }

        // 3. Distribusi Metode Pembayaran dalam Periode Terpilih
        $cashPaymentsCount = Payment::where('status', PaymentStatus::PAID)
            ->where('payment_method', 'cash')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $onlinePaymentsCount = Payment::where('status', PaymentStatus::PAID)
            ->where('payment_method', '!=', 'cash')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 4. Menu Terlaris dalam Periode Terpilih (Top 5)
        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(qty) as total_sold'), DB::raw('SUM(qty * price) as total_revenue'))
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('status', OrderStatus::PAID)
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 5. Pesanan Terkini
        $recentOrders = Order::with(['table', 'payment'])
            ->whereIn('status', [OrderStatus::SUBMITTED, OrderStatus::PAID])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'period',
            'periodLabel',
            'customStart',
            'customEnd',
            'periodIncome',
            'periodOrdersCount',
            'pendingOrdersCount',
            'totalMenusCount',
            'chartLabels',
            'chartIncomeData',
            'cashPaymentsCount',
            'onlinePaymentsCount',
            'topMenus',
            'recentOrders'
        ));
    }
}
