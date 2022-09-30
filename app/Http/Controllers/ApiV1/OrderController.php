<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderChangeStatusRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Display a listing of the orders",
     *     @OA\Parameter(
     *         in="path",
     *         name="product",
     *         description="Use product id to filter",
     *         required=false,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="client",
     *         description="Use client id to filter",
     *         required=false,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="price",
     *         description="Use price to filter",
     *         required=false,
     *         @OA\Schema(type="float"),
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="status",
     *         description="Use status to filter",
     *         required=false,
     *         @OA\Schema(type="float"),
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="paid_at",
     *         description="Use date to filter list by paid at date",
     *         example="[2022-09-01, 2022-09-03]",
     *         required=false,
     *         @OA\Schema(type="date"),
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="paid_at",
     *         description="Use date to filter list by expired at date",
     *         example="[2022-09-01, 2022-09-03]",
     *         required=false,
     *         @OA\Schema(type="date"),
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="platformType",
     *         description="Enter platform type 1=Autoweboffice, 2=Gurucan",
     *         example="1 or 2",
     *         required=false,
     *         @OA\Schema(type="date"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *      )
     * )
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(
            Order::paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Store a newly created resource in storage",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/OrderRequest")
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     )
     * )
     *
     * @param OrderRequest $request
     * @return OrderResource
     */
    public function store(OrderRequest $request): OrderResource
    {
        return new OrderResource(
            Order::create($request->validated())
        );
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/orders/{order}",
     *     tags={"Orders"},
     *     summary="Display the specified resource",
     *     @OA\Parameter(
     *         in="path",
     *         name="order",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *      )
     * )
     *
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/orders/{order}",
     *     tags={"Orders"},
     *     summary="Update the specified resource in storage",
     *     @OA\Parameter(
     *         in="path",
     *         name="order",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     )
     * )
     *
     * @param OrderRequest $request
     * @param Order $order
     * @return OrderResource
     */
    public function update(OrderRequest $request, Order $order): OrderResource
    {
        $order->update($request->validated());

        return new OrderResource(
            $order->refresh()
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Remove the specified resource from storage.",
     *     @OA\Parameter(
     *         in="path",
     *         name="order",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     )
     * )
     *
     * @param Order $order
     * @return OrderResource
     */
    public function destroy(Order $order): OrderResource
    {
        $order->delete();

        return new OrderResource($order);
    }

    /**
     * Changes the order status
     *
     * @OA\Post(
     *     path="/orders/{order}/change-status",
     *     tags={"Orders"},
     *     summary="Changes the order status",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/OrderChangeStatusRequest")
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     )
     * )
     *
     * @param int $order
     * @param OrderChangeStatusRequest $request
     * @param OrderService $orderService
     * @return OrderResource
     */
    public function changeStatus(int $order, OrderChangeStatusRequest $request, OrderService $orderService): OrderResource
    {
        return new OrderResource(
            $orderService->changeStatus(
                $order, $request->status
            )
        );
    }
}
