<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    /**
     * @var ClientService
     */
    private $clientService;

    /**
     * @param ClientService $clientService
     */
    public function __construct(ClientService $clientService)
    {
        $this->middleware('role:admin')->except(['index', 'me']);

        $this->clientService = $clientService;
    }

    /**
     * Returns the data of the current login client
     *
     * @OA\Get(
     *     path="/clients/me",
     *     tags={"Clients"},
     *     summary="Returns the data of the currently logged in client",
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
        return new ClientResource(
            $request->user()
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/clients",
     *     tags={"Clients"},
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
            Client::with('orders')->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/clients",
     *     tags={"Clients"},
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
     *     path="/clients/{id}",
     *     tags={"Clients"},
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
        $client->load('orders');

        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/clients/{id}",
     *     tags={"Clients"},
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
     * @param ClientUpdateRequest $request
     * @param int $client
     * @return ClientResource
     */
    public function update(ClientUpdateRequest $request, int $client): ClientResource
    {
        return new ClientResource(
            $this->clientService->update($client, $request->validated())
        );
    }
}
