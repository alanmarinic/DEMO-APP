<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(Request $request, CategoryService $service)
    {
        $categories = Category::all();
        $categoryTree = $service->buildCategoryTree($categories);

        return response()->json($categoryTree);
    }

    public function rootCategories(Request $request, CategoryService $service)
    {
        $categories = $service->getRootCategories();

        return response()->json($categories);
    }

    public function subcategories(Request $request, CategoryService $service, int $categoryId)
    {
        $categories = $service->getSubCategories($categoryId);

        return response()->json($categories);
    }
}
