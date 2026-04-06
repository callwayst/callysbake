<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        $dummyVouchers = [
            [
                'code' => 'WELCOME20',
                'type' => 'percent',
                'value' => 20,
                'max_discount' => 50000,
                'min_purchase' => 0,
                'usage_limit' => 100,
                'used_count' => 0,
                'expired_at' => Carbon::parse('2026-03-01'),
                'is_active' => true
            ],
            [
                'code' => 'FLAT50K',
                'type' => 'fixed',
                'value' => 50000,
                'max_discount' => 0,
                'min_purchase' => 200000,
                'usage_limit' => 50,
                'used_count' => 0,
                'expired_at' => Carbon::parse('2026-03-10'),
                'is_active' => true
            ],
            [
                'code' => 'SPRING15',
                'type' => 'percent',
                'value' => 15,
                'max_discount' => 30000,
                'min_purchase' => 100000,
                'usage_limit' => 200,
                'used_count' => 0,
                'expired_at' => Carbon::parse('2026-04-01'),
                'is_active' => true
            ],
            [
                'code' => 'NEWYEAR30',
                'type' => 'percent',
                'value' => 30,
                'max_discount' => 100000,
                'min_purchase' => 300000,
                'usage_limit' => 20,
                'used_count' => 0,
                'expired_at' => Carbon::parse('2026-03-15'),
                'is_active' => true
            ],
            [
                'code' => 'FREESHIP50',
                'type' => 'fixed',
                'value' => 50000,
                'max_discount' => 0,
                'min_purchase' => 0,
                'usage_limit' => 500,
                'used_count' => 0,
                'expired_at' => Carbon::parse('2026-05-01'),
                'is_active' => true
            ],
        ];

        foreach ($dummyVouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}