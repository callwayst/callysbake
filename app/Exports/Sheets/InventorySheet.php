<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;

class InventorySheet implements FromArray
{
    protected $inventory;

    public function __construct($inventory)
    {
        $this->inventory = $inventory;
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

        $data[] = ['Low Stock Count', $this->inventory['lowStock'] ?? 0];

        return $data;
    }
}