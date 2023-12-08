<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Product::all();
    }

    public function show(Product $dataId)
    {
        return $dataId;
    }

    /**
     * @param ProductRequest $request
     * @param ProductService $service
     * @param Product $dataId
     * @return string[]
     */
    public function update(ProductRequest $request, ProductService $service, Product $dataId)
    {
        $updatedProduct = $request->validated();
        if ($updatedProduct) {
            return $service->update($dataId, $updatedProduct);
        }
    }

    /**
     * @param ProductRequest $request
     * @return string[]
     */
    public function store(ProductRequest $request, ProductService $service)
    {
        $data = $request->validated();
        if ($data) {
            return $service->store($data);
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function delete($dataId)
    {
        $category = Product::find($dataId);
        if ($category->delete()) {
            return 'successfully deleted';
        } else {
            return 'not deleted';
        }
    }
}
