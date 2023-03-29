<?php

namespace App\Http\Api\Base\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseJsonTrait
{
    /**
     * @param $data
     * @param $status
     * @return JsonResponse
     */
    public function responseJson($data, $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
     * @param array $errors
     * @return JsonResponse
     */
    public function notFound(array $errors = []): JsonResponse
    {
        if (!$errors) {
            $errors = [
                'code' => Response::HTTP_NOT_FOUND,
                'success' => false
            ];
        }
        return $this->responseJson($errors, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param array $errors
     * @return JsonResponse
     */
    public function permissionErrors(array $errors = []): JsonResponse
    {
        if (!$errors) {
            $errors = [
                'success' => false,
                'code' => Response::HTTP_FORBIDDEN,
                'errors' => [
                    'message' => [
                        'Permission denied'
                    ]
                ]
            ];
        }

        return $this->responseJson($errors, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param $array
     * @return JsonResponse
     */
    public function successResponse($array): JsonResponse
    {
        return $this->responseJson([
            'success' => true,
            'data' => $array,
            'time' => microtime(true) - LARAVEL_START
        ]);
    }

    public function validationError(array $errors): JsonResponse
    {
        return $this->responseJson(
            [
                'success' => false,
                'errors' => $errors,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function serverError(?string $message = null): JsonResponse
    {
        return $this->responseJson([
            'message' => $message ?? 'Internal server error'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
