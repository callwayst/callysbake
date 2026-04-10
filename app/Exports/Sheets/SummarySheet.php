<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummarySheet implements FromArray, WithTitle
{
    protected $summary;

    public function __construct($summary)
    {
        $this->summary = $summary;
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function array(): array
    {
        return [
            ['SUMMARY'],
            ['Total Sales','Total Profit','Orders Count','Users Count'],
            [
                $this->summary['sales'],
                $this->summary['profit'],
                $this->summary['ordersCount'],
                $this->summary['usersCount']
            ]
        ];
    }
}