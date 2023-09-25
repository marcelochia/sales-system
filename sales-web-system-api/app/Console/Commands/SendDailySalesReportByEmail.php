<?php

namespace App\Console\Commands;

use App\Actions\SendDailySalesReportByEmail as SendDailySalesReportByEmailAction;
use App\Domain\Services\SaleService;
use App\Domain\Services\SellerService;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailySalesReportByEmail extends Command
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
    protected $signature = 'sales:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia o relatório de vendas do dia por e-mail por vendedor e ao administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = new DateTime();

        $sendEmailAction = new SendDailySalesReportByEmailAction();

        $salesInTheDay = $this->saleService->getTotalSalesForTheDayGroupedBySeller($date);

        $reportToAdmin = [];

        $quantityOfSales = 0;
        $sumOfSalesValue = 0;
        $sumOfCommissionValue = 0;

        /** @var stdClass $sale */
        foreach ($salesInTheDay as $sale) {
            $quantityOfSales += $sale->total_sales;
            $sumOfSalesValue += $sale->total_value;
            $sumOfCommissionValue += $sale->total_commission;

            $seller = $this->sellerService->getSeller($sale->seller_id);

            $reportToAdmin[] = [
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

        if ($admin) {
            try {
                $sendEmailAction->sendReportToAdmin($admin->email, $date->format('d/m/Y'), $reportToAdmin, $totals);
            } catch (Exception $e) {
                Log::error('Erro ao enviar relatório de vendas por email para ' . $admin->email, ['error' => $e->getMessage()]);
            }
        }

        /** @var stdClass $sale */
        foreach ($salesInTheDay as $sale) {
            $seller = $this->sellerService->getSeller($sale->seller_id);

            try {
                $sendEmailAction->sendReportToSeller(
                    email: $seller->getEmail(),
                    date: $date->format('d/m/Y'),
                    totalOfSales: $sale->total_sales,
                    totalSalesValue: $sale->total_value,
                    totalCommissionValue: $sale->total_commission
                );
            } catch (Exception $e) {
                Log::error('Erro ao enviar relatório de vendas por email para ' . $seller->getEmail(), ['error' => $e->getMessage()]);
            }
        }

        $this->info('Relatório diário de vendas enviado com sucesso.');
    }
}
