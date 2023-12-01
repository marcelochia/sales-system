<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Interfaces\SaleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SaleRepository implements SaleRepositoryInterface
{
    public function getSumOfDailySalesPerSeller(string $date, ?int $sellerId): array
    {
        $query = DB::table('sales')
            ->select(DB::raw('seller_id, COUNT(*) as total_sales, SUM(value) as total_value, SUM(commission) as total_commission'))
            ->where('date', $date);
        if (!is_null($sellerId)) {
            $query->where('seller_id', $sellerId);
        }

        return $query->groupBy('seller_id')
            ->get()
            ->toArray();
    }
}
