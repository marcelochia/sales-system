<?php

namespace App\Http\Controllers;

use App\Domain\Services\SaleService;
use App\Domain\Services\SellerService;
use App\Exceptions\EntityNotFoundException;
use App\Http\Requests\SellerRequest;
use App\Http\Resources\SaleResource;
use App\Http\Resources\SellerResource;
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
            $sellers = $this->service->getAllSellers(query: $request->q);

            return response()->json(SellerResource::collection($sellers));

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

        } catch (EntityNotFoundException $e) {
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

        } catch (EntityNotFoundException $e) {
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

            return response()->json(SaleResource::collection($sales));

        } catch (EntityNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            return $this->errorResponse();
        }
    }
}