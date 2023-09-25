<?php

namespace App\Actions;

use App\Mail\AdminSalesReportOfTheDay;
use App\Mail\SalesReportOfTheDay;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReportByEmail
{
    public function sendReportToAdmin(string $email, string $date, array $sales, array $totals): void
    {
        Mail::to($email)->queue(new AdminSalesReportOfTheDay($date, $sales, $totals));
    }

    public function sendReportToSeller(
        string $email,
        string $date,
        int $totalOfSales,
        float $totalSalesValue,
        float $totalCommissionValue
    ): void
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
