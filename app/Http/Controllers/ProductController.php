<?php

namespace App\Http\Controllers;

use App\Repositories\Product\IProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $productRepository;

    /**
     * Author :Arshad Hussain
     * Date : 2025-04-02
     * Description : This class implements the IProduct interface and provides methods to interact with the Product model.
     * It includes methods for retrieving all products, getting a product by ID, creating a new product, updating an existing product, and soft deleting a product by updating its status.
     * The methods utilize Eloquent ORM for database operations and return appropriate responses.
     */

    /**
     * Constructor to inject the product repository interface.
     */
    public function __construct(IProduct $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products.
     * @return \Illuminate\Http\JsonResponse
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
     * Get a product by ID.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Fetch product by ID
        $product = $this->productRepository->getById($request->id);

        return response()->json([
            'status'  => true,
            'message' => 'Product retrieved successfully',
            'data'    => $product
        ], 200);
    }

    /**
     * Store a new product.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'required|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Create new product
        $product = $this->productRepository->create($request);

        return response()->json([
            'status'  => true,
            'message' => 'Product created successfully',
            'data'    => $product
        ], 201);
    }

    /**
     * Update an existing product.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'id'          => 'required|integer',
            'name'        => 'nullable|string',
            'price'       => 'nullable|numeric',
            'description' => 'nullable|string',
            'stock'       => 'nullable|integer',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Update product details
        $product = $this->productRepository->update($request);

        return response()->json([
            'status'  => true,
            'message' => 'Product updated successfully',
            'data'    => $product
        ], 200);
    }

    /**
     * Soft delete a product (update status).
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Soft delete product by updating status
        $this->productRepository->updateStatusById($request->id);

        return response()->json([
            'status'  => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
