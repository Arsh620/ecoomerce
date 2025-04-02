<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\IProduct;

class ProductRepository implements IProduct
{

 
    /**
     * Retrieve all products from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Product::all();
    }

    /**
     * Retrieve a product by its ID.
     *
     * @param int $id
     * @return Product
     */
    public function getById($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Create a new product and save it to the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return Product
     */
    public function create($request)
    {
        // Creating a new product instance
        $product = new Product();
        $product->name        = $request->name;
        $product->price       = $request->price;
        $product->description = $request->description;
        $product->stock       = $request->stock;
        
        // Save the product to the database
        $product->save();

        return $product;
    }

    /**
     * Update an existing product's details.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($request)
    {
        // Find the product by ID
        $product = Product::find($request->id);

        // Ensure the product exists
        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Updating only the provided fields
        $data = [
            'name'        => $request->name        ?? $product->name,
            'price'       => $request->price       ?? $product->price,
            'description' => $request->description ?? $product->description,
            'stock'       => $request->stock       ?? $product->stock,
        ];

        // Apply the update
        $product->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Product updated successfully',
            'data'    => $product
        ], 200);
    }

    /**
     * Soft delete a product by updating its status.
     *
     * @param int $id
     * @return void
     */
    public function updateStatusById($id)
    {
        // Mark the product as inactive by setting 'status' to 0
        Product::where('id', $id)->update(['status' => 0]);
    }
}
