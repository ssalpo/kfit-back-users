<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('role:admin')->except(['index', 'show']);

        $this->productService = $productService;
    }

    /**
     * Display a listing of the product.
     *
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Display a listing of the product.",
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ProductCollectionResource")
     *      )
     * )
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(
            Product::with('goods')->paginate(10)
        );
    }

    /**
     * Store a newly created product in storage.
     *
     * @OA\Post(
     *     path="/products",
     *     tags={"Products"},
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
            $this->productService->add(
                $request->validated()
            )
        );
    }

    /**
     * Display the specified product.
     *
     * @OA\Get(
     *     path="/products/{product}",
     *     tags={"Products"},
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
     *          @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *      )
     * )
     *
     * @return ProductResource
     * @var Product $product
     */
    public function show(Product $product): ProductResource
    {
        $product->load('goods');

        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @OA\Put(
     *     path="/products/{product}",
     *     tags={"Products"},
     *     summary="Update the specified product in storage.",
     *     @OA\Parameter(
     *         in="path",
     *         name="product",
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
     * @param int $product
     * @return ProductResource
     */
    public function update(ProductRequest $request, int $product): ProductResource
    {
        return new ProductResource(
            $this->productService->update(
                $product,
                $request->validated()
            )
        );
    }

    /**
     * Remove the specified product from storage.
     *
     * @OA\Delete(
     *     path="/products",
     *     tags={"Products"},
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
