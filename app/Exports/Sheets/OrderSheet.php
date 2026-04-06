<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;

class OrdersSheet implements FromArray
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function array(): array
    {
        $data = [];
        $data[] = ['ORDERS DETAIL'];
        $data[] = ['ID','User','Email','Total','Status','Date','Items'];

        foreach($this->orders as $o){
            $data[] = [
                $o['id'] ?? '',
                $o['user_name'] ?? '',
                $o['email'] ?? '',
                $o['total'] ?? 0,
                $o['status'] ?? '',
                $o['created_at'] ?? '',
                $o['items'] ?? ''
            ];
        }

        return $data;
    }
}