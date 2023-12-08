<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Category::all();
    }

    public function show(Category $dataId)
    {
        return $dataId;
    }

    /**
     * @param CategoryRequest $request
     * @param CategoryService $service
     * @param Category $dataId
     * @return string[]
     */
    public function update(CategoryRequest $request, CategoryService $service, Category $dataId)
    {
        $updatedCategory = $request->validated();
        if ($updatedCategory) {
            return $service->update($dataId, $updatedCategory);
        }
    }

    /**
     * @param CategoryRequest $request
     * @return string[]
     */
    public function store(CategoryRequest $request, CategoryService $service)
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
        $category = Category::find($dataId);
        if ($category->delete()) {
            return 'successfully deleted';
        } else {
            return 'not deleted';
        }
    }
}
