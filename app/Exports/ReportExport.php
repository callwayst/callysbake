<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\SummarySheet;
use App\Exports\Sheets\AnalyticsSheet;
use App\Exports\Sheets\InventorySheet;
use App\Exports\Sheets\OrdersSheet;

class ReportExport implements WithMultipleSheets
{
    protected array $summary;
    protected array $analytics;
    protected array $inventory;
    protected $ordersDetail;
    protected array $sections;

    public function __construct($summary, $analytics, $inventory, $ordersDetail, $sections)
    {
        $this->summary      = $summary;
        $this->analytics    = $analytics;
        $this->inventory    = $inventory;
        $this->ordersDetail = $ordersDetail;
        $this->sections     = array_values($sections); // pastikan index berurutan
    }

    public function sheets(): array
    {
        $sheets = [];
        $all = in_array('all', $this->sections);

        if ($all || in_array('summary', $this->sections)) {
            $sheets[] = new SummarySheet($this->summary);
        }

        if ($all || in_array('analytics', $this->sections)) {
            $sheets[] = new AnalyticsSheet($this->analytics);
        }

        if ($all || in_array('inventory', $this->sections)) {
            $sheets[] = new InventorySheet($this->inventory);
        }

        if ($all || in_array('orders', $this->sections)) {
            $sheets[] = new OrdersSheet($this->ordersDetail);
        }

        return $sheets;
    }
}