<?php

namespace App\Actions;

use App\Mail\SalesReportOfTheDay;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReportByEmail
{
    public function execute(string $email, string $date, int $totalOfSales, float $totalSalesValue, float $totalCommissionValue): void
    {
        Mail::to($email)
            ->queue(new SalesReportOfTheDay(
                $date,
                $totalOfSales,
                $totalSalesValue,
                $totalCommissionValue
            ));
    }
}
