<?php

namespace App\Http\Controllers\ApiV1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/admin/clients",
     *     tags={"Admin Clients"},
     *     summary="Display a listing of the resource",
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ClientResource")
     *      )
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ClientResource::collection(
            Client::paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/admin/clients",
     *     tags={"Admin Clients"},
     *     summary="Store a newly created resource in storage",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ClientStoreRequest")
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ClientResource")
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return ClientResource
     */
    public function store(ClientStoreRequest $request): ClientResource
    {
        return new ClientResource(
            Client::create($request->validated())
        );
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/admin/clients/{id}",
     *     tags={"Admin Clients"},
     *     summary="Display the specified resource",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ClientResource")
     *      )
     * )
     *
     * @param Client $client
     * @return ClientResource
     */
    public function show(Client $client): ClientResource
    {
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/admin/clients/{id}",
     *     tags={"Admin Clients"},
     *     summary="Update the specified resource in storage",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ClientResource")
     *     )
     * )
     *
     * @param ClientStoreRequest $request
     * @param Client $client
     * @return ClientResource
     */
    public function update(ClientStoreRequest $request, Client $client): ClientResource
    {
        $client->update($request->validated());

        return new ClientResource(
            $client->refresh()
        );
    }
}
