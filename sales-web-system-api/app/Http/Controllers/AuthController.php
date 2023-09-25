<?php

namespace App\Http\Controllers;

use App\Domain\Services\AuthService;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}

    public function auth(AuthRequest $request): JsonResponse
    {
        try {
            $isAuthorized = $this->service->validateUserAuthorization($request->email, $request->password);

            if (!$isAuthorized) {
                return $this->errorResponse(
                    message: 'Não autorizado',
                    code: Response::HTTP_UNAUTHORIZED
                );
            }

            $accessAuthorization = $this->service->generateToken();

            return response()->json([
                'access_token' => $accessAuthorization['token'],
                'user_name' => $accessAuthorization['user']['name'],
                'is_admin' => (bool) $accessAuthorization['user']['is_admin']
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e->getFile() => $e->getLine()]);
            return $this->errorResponse();
        }
    }
}
