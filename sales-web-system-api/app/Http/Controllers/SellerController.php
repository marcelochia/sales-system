<?php

namespace App\Http\Controllers;

use App\Actions\SendDailySalesReportByEmail;
use App\Domain\Services\SaleService;
use App\Domain\Services\SellerService;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\SellerRequest;
use App\Http\Resources\SellerResource;
use App\Http\Resources\SellerSalesResource;
use App\Http\Resources\SellersResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SellerController extends Controller
{
    public function __construct(private SellerService $service) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $sellers = $this->service->getAllSellers(
                query: $request->q,
                sortBy: $request->get('sortBy', 'id'),
                order: $request->get('order', 'asc'),
            );

            return response()->json(SellersResource::collection($sellers));

        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            return $this->errorResponse();
        }
    }

    public function store(SellerRequest $request): JsonResponse
    {
        try {
            $seller = $this->service->createSeller($request->name, $request->email);

            return response()->json(
                data: SellerResource::make($seller),
                status: Response::HTTP_CREATED
            );

        } catch (\DomainException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            return $this->errorResponse();
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $seller = $this->service->getSeller($id);

            return response()->json(SellerResource::make($seller));

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            return $this->errorResponse();
        }
    }

    public function update(SellerRequest $request, string $id): JsonResponse
    {
        try {
            $seller = $this->service->updateSeller($id, $request->name, $request->email);

            return response()->json(SellerResource::make($seller));

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\DomainException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            return $this->errorResponse();
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->service->deleteSeller($id);

            return response()->json(['success' => 'Vendedor excluído.']);

        } catch (\DomainException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine(), 'exception' => class_basename($e)]);
            return $this->errorResponse();
        }
    }

    public function sales(string $id, SaleService $saleService): JsonResponse
    {
        try {
            $seller = $this->service->getSeller($id);

            $sales = $saleService->getAllSalesPerSeller($seller);

            return response()->json(SellerSalesResource::collection($sales));

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            return $this->errorResponse();
        }
    }

    public function sendReport(
        string $id,
        ReportRequest $request,
        SaleService $saleService,
        SendDailySalesReportByEmail $sendDailySalesReportByEmail
    ): JsonResponse
    {
        try {
            $seller = $this->service->getSeller($id);

            $sale = $saleService->getTotalSalesForTheDayGroupedBySeller(new \DateTime($request->date), $seller->id);

            if (count($sale) === 0) {
                return response()->json(['message' => 'Não existem vendas para este vendedor nesta data.']);
            }

            $sendDailySalesReportByEmail->sendReportToSeller(
                email: $seller->email,
                date: $request->date,
                totalOfSales: $sale[0]->total_sales,
                totalSalesValue: $sale[0]->total_value,
                totalCommissionValue: $sale[0]->total_commission
            );

            return response()->json(['message' => 'Relatório enviado com sucesso.']);

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            return $this->errorResponse();
        }
    }
}
