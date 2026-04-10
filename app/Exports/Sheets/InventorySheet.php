<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class InventorySheet implements FromArray, WithTitle
{
    protected $inventory;

    public function __construct($inventory)
    {
        $this->inventory = $inventory;
    }

    public function title(): string
    {
        return 'Inventory';
    }

    public function array(): array
    {
        $data = [];
        $data[] = ['INVENTORY'];
        $data[] = ['Product','Stock'];

        foreach($this->inventory['products'] as $p){
            $data[] = [
                $p['name'] ?? '',
                $p['stock'] ?? 0
            ];
        }

        $data[] = [];
        $data[] = ['Low Stock Count', $this->inventory['lowStock'] ?? 0];

        return $data;
    }
}