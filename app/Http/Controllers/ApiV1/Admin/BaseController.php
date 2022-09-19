<?php
namespace App\Http\Controllers\ApiV1\Admin;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    private function getApiHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    protected function response($data, int $statusCode = 200)
    {
        return \response()->json($data, $statusCode, $this->getApiHeaders());
    }

}
