<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 02.07.2017
 * Time: 15:27
 */

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait RestSuccessResponseTrait
{
    /**
     * @param null $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($data = null, int $statusCode = 200): JsonResponse
    {
        $payload = ['status' => 'success'];
        $data ? $payload['data'] = $data : [];

        return response()->json($payload, $statusCode);
    }
}