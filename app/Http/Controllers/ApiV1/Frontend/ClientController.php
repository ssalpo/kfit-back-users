<?php

namespace App\Http\Controllers\ApiV1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Returns the data of the current login user
     *
     * @OA\Get(
     *     path="/me",
     *     tags={"Frontend Users"},
     *     summary="Returns the data of the current login user",
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ClientResource")
     *      )
     * )
     *
     * @param Request $request
     * @return ClientResource
     */
    public function me(Request $request): ClientResource
    {
        return new ClientResource($request->user());
    }
}
