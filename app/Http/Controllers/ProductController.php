<?php

namespace App\Http\Controllers;

use App\Repositories\Product\IProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Ecommerce API",
 *     version="1.0.0",
 *     description="API documentation for the ecommerce application.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"id", "name", "price", "description"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Smartphone"),
 *     @OA\Property(property="price", type="number", format="float", example=299.99),
 *     @OA\Property(property="description", type="string", example="A high-end smartphone"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-01T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-01T12:34:56Z")
 * )
 */
class ProductController extends Controller
{
    private $productRepository;

    public function __construct(IProduct $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/get-all-products",
     *     summary="Retrieve all products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        $products = $this->productRepository->getAll();
        return response()->json([
            'status'  => true,
            'message' => 'Products retrieved successfully',
            'data'    => $products
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/get-product-by-id",
     *     summary="Retrieve a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful response"),
     *     @OA\Response(response=422, description="Validation failed"),
     * )
     */
    public function getById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $product = $this->productRepository->getById($request->id);

        return response()->json([
            'status'  => true,
            'message' => 'Product retrieved successfully',
            'data'    => $product
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/create-products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "description"},
     *             @OA\Property(property="name", type="string", example="Smartphone"),
     *             @OA\Property(property="price", type="number", format="float", example=299.99),
     *             @OA\Property(property="description", type="string", example="A high-end smartphone")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Product created successfully"),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $product = $this->productRepository->create($request);

        return response()->json([
            'status'  => true,
            'message' => 'Product created successfully',
            'data'    => $product
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/update-product-by-id",
     *     summary="Update a product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Smartphone Pro"),
     *             @OA\Property(property="price", type="number", format="float", example=399.99),
     *             @OA\Property(property="description", type="string", example="An upgraded smartphone")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Product updated successfully"),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'          => 'required|integer',
            'name'        => 'nullable|string',
            'price'       => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $product = $this->productRepository->update($request);

        return response()->json([
            'status'  => true,
            'message' => 'Product updated successfully',
            'data'    => $product
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/delete-product-by-id",
     *     summary="Soft delete a product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Product deleted successfully"),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $this->productRepository->updateStatusById($request->id);

        return response()->json([
            'status'  => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
