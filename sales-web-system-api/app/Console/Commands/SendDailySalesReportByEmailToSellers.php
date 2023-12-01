<?php

namespace App\Console\Commands;

use App\Actions\SendDailySalesReportByEmail as SendDailySalesReportByEmailAction;
use App\Services\SaleService;
use App\Services\SellerService;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailySalesReportByEmailToSellers extends Command
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
    protected $signature = 'sales:send-daily-report-sellers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia o relatório de vendas do dia por e-mail aos vendedores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = new DateTime();

        $sendEmailAction = new SendDailySalesReportByEmailAction();

        $salesInTheDay = $this->saleService->getTotalSalesForTheDayGroupedBySeller($date);

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
                Log::error('Erro ao enviar relatório de vendas por email para: ' . $seller->getEmail(), ['error' => $e->getMessage()]);
            }
        }

        $this->info('Relatório diário de vendas enviado para os vendedores. Verificar log do sistema para casos de falhas.');
    }
}
