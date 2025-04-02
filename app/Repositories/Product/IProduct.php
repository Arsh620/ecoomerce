<?php

namespace App\Repositories\Product;

/**
 * Interface IProduct
 * 
 * Defines the contract for product-related repository operations.
 */
interface IProduct
{
    /**
     * Retrieve all products from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll();

    /**
     * Retrieve a product by its ID.
     *
     * @param int $id The ID of the product
     * @return mixed The product details
     */
    public function getById($id);

    /**
     * Create a new product in the database.
     *
     * @param \Illuminate\Http\Request $request The request containing product data
     * @return mixed The newly created product
     */
    public function create($request);

    /**
     * Update an existing product's details.
     *
     * @param \Illuminate\Http\Request $request The request containing updated product data
     * @return mixed The updated product
     */
    public function update($request);

    /**
     * Soft delete a product by updating its status.
     *
     * @param int $id The ID of the product to deactivate
     * @return void
     */
    public function updateStatusById($id);
}
