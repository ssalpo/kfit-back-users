<?php

namespace App\Http\Controllers\ApiV1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the product.
     *
     * @OA\Get(
     *     path="/admin/products",
     *     tags={"Admin Products"},
     *     summary="Display a listing of the product.",
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *      )
     * )
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(
            Product::paginate(10)
        );
    }

    /**
     * Store a newly created product in storage.
     *
     * @OA\Post(
     *     path="/admin/products",
     *     tags={"Admin Products"},
     *     summary="Store a newly created product in storage",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ProductRequest")
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     )
     * )
     *
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function store(ProductRequest $request): ProductResource
    {
        return new ProductResource(
            Product::create($request->validated())
        );
    }

    /**
     * Display the specified product.
     *
     * @OA\Get(
     *     path="/admin/products/{user}",
     *     tags={"Admin Products"},
     *     summary="Display the specified product",
     *     @OA\Parameter(
     *         in="path",
     *         name="product",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      )
     * )
     *
     * @return ProductResource
     * @var Product $product
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @OA\Put(
     *     path="/admin/products/{id}",
     *     tags={"Users"},
     *     summary="Update the specified product in storage.",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     )
     * )
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());

        return new ProductResource(
            $product->refresh()
        );
    }

    /**
     * Remove the specified product from storage.
     *
     * @OA\Delete(
     *     path="/admin/products",
     *     tags={"Admin Products"},
     *     summary="Remove the specified product from storage.",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     )
     * )
     *
     * @param Product $product
     * @return ProductResource
     */
    public function destroy(Product $product): ProductResource
    {
        $product->delete();

        return new ProductResource($product);
    }
}
