<?php

namespace App\Console\Commands;

use App\Actions\SendDailySalesReportByEmail as SendDailySalesReportByEmailAction;
use App\Services\SaleService;
use App\Services\SellerService;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailySalesReportByEmailToAdmin extends Command
{
    public function __construct(private SaleService $saleService, private SellerService $sellerService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:send-daily-report-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia o relatório de vendas do dia por e-mail ao administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = new DateTime();

        $sendEmailAction = new SendDailySalesReportByEmailAction();

        $salesInTheDay = $this->saleService->getTotalSalesForTheDayGroupedBySeller($date);

        $report = [];

        $quantityOfSales = 0;
        $sumOfSalesValue = 0;
        $sumOfCommissionValue = 0;

        /** @var stdClass $sale */
        foreach ($salesInTheDay as $sale) {
            $quantityOfSales += $sale->total_sales;
            $sumOfSalesValue += $sale->total_value;
            $sumOfCommissionValue += $sale->total_commission;

            $seller = $this->sellerService->getSeller($sale->seller_id);

            $report[] = [
                'seller' => $seller->getName(),
                'totalOfSales' => $sale->total_sales,
                'totalSalesValue' => $sale->total_value,
                'totalCommissionValue' => $sale->total_commission
            ];
        }

        $totals = [
            'quantityOfSales' => $quantityOfSales,
            'sumOfSalesValue' => $sumOfSalesValue,
            'sumOfCommissionValue' => $sumOfCommissionValue
        ];

        $admin = User::where('is_admin', true)->first();

        try {
            $sendEmailAction->sendReportToAdmin($admin->email, $date->format('d/m/Y'), $report, $totals);
            $this->info('Relatório diário de vendas enviado com sucesso para o administrador.');
        } catch (Exception $e) {
            Log::error('Erro ao enviar relatório de vendas por email para o administrador do sistema:' . $admin->email, ['error' => $e->getMessage()]);
        }
    }
}
