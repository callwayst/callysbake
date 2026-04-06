<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\SummarySheet;
use App\Exports\Sheets\AnalyticsSheet;
use App\Exports\Sheets\InventorySheet;
use App\Exports\Sheets\OrdersSheet;

class ReportExport implements WithMultipleSheets
{
    protected $summary;
    protected $analytics;
    protected $inventory;
    protected $ordersDetail;
    protected $sections;

    public function __construct($summary, $analytics, $inventory, $ordersDetail, $sections)
    {
        $this->summary = $summary;
        $this->analytics = $analytics;
        $this->inventory = $inventory;
        $this->ordersDetail = $ordersDetail;
        $this->sections = $sections;
    }

    public function sheets(): array
    {
        $sheets = [];

        if (in_array('summary', $this->sections)) {
            $sheets[] = new SummarySheet($this->summary);
        }

        if (in_array('analytics', $this->sections)) {
            $sheets[] = new AnalyticsSheet($this->analytics);
        }

        if (in_array('inventory', $this->sections)) {
            $sheets[] = new InventorySheet($this->inventory);
        }

        if (in_array('orders', $this->sections)) {
            $sheets[] = new OrdersSheet($this->ordersDetail);
        }

        return $sheets;
    }
}