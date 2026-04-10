<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class AnalyticsSheet implements FromArray, WithTitle
{
    protected $analytics;

    public function __construct($analytics)
    {
        $this->analytics = $analytics;
    }

    public function title(): string
    {
        return 'Analytics';
    }

    public function array(): array
    {
        
        $data = [];

        $data[] = ['ANALYTICS - Daily Sales'];
        $data[] = ['Date','Total Sales','Orders Count'];
        foreach($this->analytics['dailyLabels'] as $i => $label){
            $data[] = [
                $label,
                $this->analytics['dailyTotals'][$i] ?? 0,
                $this->analytics['dailyOrders'][$i] ?? 0
            ];
        }
        $data[] = [];

        $data[] = ['ANALYTICS - Top Products'];
        $data[] = ['Product','Quantity Sold'];
        foreach($this->analytics['topProducts'] as $p){
            $data[] = [
                $p['product_name'] ?? '',
                $p['total'] ?? 0
            ];
        }

        return $data;
    }
}