<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
  /**
   * Success response method.
   *
   * @param mixed $data
   * @param string $message
   * @param int $statusCode
   * @return JsonResponse
   */
  public function successResponse($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
  {
    $response = [
      'success' => true,
      'message' => $message,
    ];

    if ($data instanceof LengthAwarePaginator) {
      $response['data'] = $data->items();
      $response['pagination'] = [
        'total' => $data->total(),
        'per_page' => $data->perPage(),
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
      ];
    } elseif ($data !== null) {
      $response['data'] = $data;
    }

    return response()->json($response, $statusCode);
  }

  /**
   * Error response method.
   *
   * @param string $message
   * @param int $statusCode
   * @param mixed $errors
   * @return JsonResponse
   */
  public function errorResponse(string $message = 'Error', int $statusCode = 400, $errors = null): JsonResponse
  {
    $response = [
      'success' => false,
      'message' => $message,
    ];

    if ($errors !== null) {
      $response['errors'] = $errors;
    }

    return response()->json($response, $statusCode);
  }

  /**
   * Response with status code 201 (Created).
   *
   * @param mixed $data
   * @param string $message
   * @return JsonResponse
   */
  public function createdResponse($data = null, string $message = 'Resource created successfully'): JsonResponse
  {
    return $this->successResponse($data, $message, 201);
  }

  /**
   * Response with status code 204 (No Content).
   *
   * @return JsonResponse
   */
  public function noContentResponse(): JsonResponse
  {
    return response()->json(null, 204);
  }

  /**
   * Response for unauthorized access (401).
   *
   * @param string $message
   * @return JsonResponse
   */
  public function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
  {
    return $this->errorResponse($message, 401);
  }

  /**
   * Response for forbidden access (403).
   *
   * @param string $message
   * @return JsonResponse
   */
  public function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
  {
    return $this->errorResponse($message, 403);
  }

  /**
   * Response for not found (404).
   *
   * @param string $message
   * @return JsonResponse
   */
  public function notFoundResponse(string $message = 'Resource not found'): JsonResponse
  {
    return $this->errorResponse($message, 404);
  }

  /**
   * Response for validation errors (422).
   *
   * @param mixed $errors
   * @param string $message
   * @return JsonResponse
   */
  public function validationErrorResponse($errors, string $message = 'Validation errors'): JsonResponse
  {
    return $this->errorResponse($message, 422, $errors);
  }
}
