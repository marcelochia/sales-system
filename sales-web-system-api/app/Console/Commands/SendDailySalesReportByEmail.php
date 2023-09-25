<?php

namespace App\Console\Commands;

use App\Actions\SendDailySalesReportByEmail as SendDailySalesReportByEmailAction;
use App\Domain\Services\SaleService;
use App\Domain\Services\SellerService;
use DateTime;
use Illuminate\Console\Command;

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
    protected $description = 'Envia o relatório de vendas do dia por e-mail por vendedor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = new DateTime();

        $salesInTheDay = $this->saleService->getTotalSalesForTheDayGroupedBySeller($date);

        /** @var stdClass $sale */
        foreach ($salesInTheDay as $sale) {
            $seller = $this->sellerService->getSeller($sale->seller_id);

            $sendEmail = new SendDailySalesReportByEmailAction();
            $sendEmail->execute(
                email: $seller->getEmail(),
                date: $date->format('d/m/Y'),
                totalOfSales: $sale->total_sales,
                totalSalesValue: $sale->total_value,
                totalCommissionValue: $sale->total_commission
            );
        }

        $this->info('Relatório diário de vendas enviado com sucesso.');
    }
}
