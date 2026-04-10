<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use App\Models\Product;

// ... tetap namespace & use sebelumnya

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->resolveDate($request);
        $sections = $request->input('sections') ?? [];

        $data = $this->queryReportFull($from, $to);

        $sales  = $data->sum('total');
        $profit = $sales * 0.2;
        $orders = $data->count();
        $users  = $data->pluck('user_name')->unique()->count();

        [$dailyLabels, $dailySales, $dailyOrders, $productLabels, $productTotals] = $this->prepareAnalytics($data);

        return view('admin.reports.index', compact(
            'from','to','sections','sales','profit','orders','users',
            'dailyLabels','dailySales','dailyOrders','productLabels','productTotals'
        ));
    }

    private function resolveDate(Request $request)
    {
        $from = $request->input('from') 
            ? Carbon::parse($request->input('from'))->startOfDay() 
            : now()->startOfMonth();
        $to = $request->input('to') 
            ? Carbon::parse($request->input('to'))->endOfDay() 
            : now()->endOfDay();
        return [$from, $to];
    }

    private function queryReportFull($from, $to)
    {
        return DB::table('orders as o')
            ->leftJoin('users as u','o.user_id','=','u.id')
            ->leftJoin('order_items as oi','oi.order_id','=','o.id')
            ->whereBetween('o.created_at', [$from, $to])
            ->select(
                'o.id',
                'u.name as user_name',
                'u.email',
                DB::raw('(o.total_price - o.discount) as total'),
                'o.status',
                'o.created_at',
                DB::raw('IFNULL(GROUP_CONCAT(CONCAT(oi.product_name," x",oi.qty) SEPARATOR ", "), "") as items')
            )
            ->groupBy('o.id','u.name','u.email','o.total_price','o.discount','o.status','o.created_at')
            ->orderByDesc('o.created_at')
            ->get()
            ->map(fn($o) => (array)$o); // ⚠ cast semua ke array
    }

    private function prepareAnalytics($data)
    {
        $dailyLabels = $data->groupBy(fn($d) => Carbon::parse($d['created_at'])->format('d-m-Y'))->keys()->toArray();
        $dailySales = [];
        $dailyOrders = [];
        foreach($dailyLabels as $label){
            $dayData = $data->filter(fn($d) => Carbon::parse($d['created_at'])->format('d-m-Y') == $label);
            $dailySales[] = $dayData->sum('total');
            $dailyOrders[] = $dayData->count();
        }

        $productGroups = [];
        foreach($data as $order){
            $items = $order['items'] ? explode(',', $order['items']) : [];
            foreach($items as $item){
                [$productName, $qty] = array_map('trim', explode(' x', $item) + [null,1]);
                if ($productName) $productGroups[$productName] = ($productGroups[$productName] ?? 0) + (int)$qty;
            }
        }
        arsort($productGroups);
        $productLabels = array_keys($productGroups);
        $productTotals = array_values($productGroups);

        return [$dailyLabels, $dailySales, $dailyOrders, $productLabels, $productTotals];
    }

    public function export(Request $request, $type)
    {
        [$from, $to] = $this->resolveDate($request);
        $sections = $request->input('sections') ?? [];

        // Jika 'all' ada di sections, expand ke semua section
        if (in_array('all', $sections)) {
            $sections = ['summary', 'analytics', 'inventory', 'orders'];
        }

        // Jika tidak ada section dipilih sama sekali, default semua
        if (empty($sections)) {
            $sections = ['summary', 'analytics', 'inventory', 'orders'];
        }

        $data = $this->queryReportFull($from, $to);

        $summary = [
            'sales'       => $data->sum('total'),
            'profit'      => $data->sum('total') * 0.2,
            'ordersCount' => $data->count(),
            'usersCount'  => $data->pluck('user_name')->unique()->count(),
        ];

        [$dailyLabels, $dailySales, $dailyOrders, $productLabels, $productTotals] = $this->prepareAnalytics($data);

        $topProducts = [];
        foreach ($productLabels as $i => $label) {
            $topProducts[] = [
                'product_name' => $label,
                'total'        => $productTotals[$i] ?? 0,
            ];
        }

        $analytics = [
            'dailyLabels'  => $dailyLabels,
            'dailyTotals'  => $dailySales,
            'dailyOrders'  => $dailyOrders,
            'topProducts'  => $topProducts,
        ];

        $inventoryProducts = Product::select('name', 'stock')->get();
        $inventory = [
            'products' => $inventoryProducts->map(fn($p) => ['name' => $p->name, 'stock' => $p->stock])->toArray(),
            'lowStock' => $inventoryProducts->where('stock', '<=', 5)->count(),
        ];

        $ordersDetail = $data;

        return match ($type) {
            'csv' => response()->streamDownload(function () use ($sections, $summary, $analytics, $inventory, $ordersDetail) {
                $handle = fopen('php://output', 'w');

                if (in_array('summary', $sections)) {
                    fputcsv($handle, ['SUMMARY']);
                    fputcsv($handle, ['Total Sales', 'Total Profit', 'Orders Count', 'Users Count']);
                    fputcsv($handle, [$summary['sales'], $summary['profit'], $summary['ordersCount'], $summary['usersCount']]);
                    fputcsv($handle, []);
                }

                if (in_array('analytics', $sections)) {
                    fputcsv($handle, ['ANALYTICS - Daily Sales']);
                    fputcsv($handle, ['Date', 'Total Sales', 'Orders Count']);
                    foreach ($analytics['dailyLabels'] as $i => $label) {
                        fputcsv($handle, [$label, $analytics['dailyTotals'][$i], $analytics['dailyOrders'][$i]]);
                    }
                    fputcsv($handle, []);

                    fputcsv($handle, ['ANALYTICS - Top Products']);
                    fputcsv($handle, ['Product', 'Quantity Sold']);
                    foreach ($analytics['topProducts'] as $p) {
                        fputcsv($handle, [$p['product_name'], $p['total']]);
                    }
                    fputcsv($handle, []);
                }

                if (in_array('inventory', $sections)) {
                    fputcsv($handle, ['INVENTORY']);
                    fputcsv($handle, ['Product', 'Stock']);
                    foreach ($inventory['products'] as $p) {
                        fputcsv($handle, [$p['name'], $p['stock']]);
                    }
                    fputcsv($handle, ['Low Stock Count', $inventory['lowStock']]);
                    fputcsv($handle, []);
                }

                if (in_array('orders', $sections)) {
                    fputcsv($handle, ['ORDERS DETAIL']);
                    fputcsv($handle, ['ID', 'User', 'Email', 'Total', 'Status', 'Date', 'Items']);
                    foreach ($ordersDetail as $o) {
                        fputcsv($handle, [$o['id'], $o['user_name'], $o['email'], $o['total'], $o['status'], $o['created_at'], $o['items']]);
                    }
                }

                fclose($handle);
            }, 'report.csv'),

            'excel' => Excel::download(new ReportExport($summary, $analytics, $inventory, $ordersDetail, $sections), 'report.xlsx', \Maatwebsite\Excel\Excel::XLSX),

            'pdf' => Pdf::loadView('admin.reports.pdf', [
                'data'         => $data,
                'from'         => $from,
                'to'           => $to,
                'sections'     => $sections,
                'summary'      => $summary,
                'analytics'    => $analytics,
                'inventory'    => $inventory,
                'ordersDetail' => $ordersDetail,
                'logoPath'     => public_path('images/logo.png'),
                'webName'      => config('app.name'),
            ])->download('report.pdf'),

            default => abort(404),
        };
    }
}