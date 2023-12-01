<?php

namespace App\Http\Controllers;

use App\Services\SaleService;
use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function __construct(private SaleService $service) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $sales = $this->service->getAllSales(query: $request->q);

            return response()->json(SaleResource::collection($sales));

        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile() => $e->getLine(), 'exception' => class_basename($e)]);

            return $this->errorResponse();
        }
    }

    public function store(SaleRequest $request): JsonResponse
    {
        try {
            ['value' => $value, 'date' => $date, 'seller_id' => $seller_id] = $request->validated();

            $date = new DateTime($date);

            $seller = $this->service->createSale($value, $date, $seller_id);

            return response()->json(SaleResource::make($seller), Response::HTTP_CREATED);

        } catch (\DomainException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile() => $e->getLine(), 'exception' => class_basename($e)]);

            return $this->errorResponse();
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $sale = $this->service->getSale($id);

            return response()->json(SaleResource::make($sale));

        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile() => $e->getLine(), 'exception' => class_basename($e)]);

            return $this->errorResponse();
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->service->deleteSale($id);

            return response()->json(['success' => 'Venda excluída.']);

        } catch (\DomainException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile() => $e->getLine(), 'exception' => class_basename($e)]);

            return $this->errorResponse();
        }
    }

    public function dailyTotal(): JsonResponse
    {
        try {
            $totalDailySales = $this->service->getTotalDailySales();

            return response()->json($totalDailySales);

        } catch (\DomainException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile() => $e->getLine(), 'exception' => class_basename($e)]);

            return $this->errorResponse();
        }
    }
}
