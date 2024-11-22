<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    private function getProductsCountByCategory(): array
    {
        $productCountByCategory = DB::table('category_product')
            ->select(DB::raw('category_id, COUNT(*) as count'))
            ->groupBy('category_id')
            ->get()
            ->toArray();

        return array_column($productCountByCategory, 'count', 'category_id');
    }

    private function addCountField($categories): array
    {
        $productsCount = $this->getProductsCountByCategory();

        foreach ($categories as $category) {
            $category['count'] = $productsCount[$category['id']];
        }

        return $categories->toArray();
    }

    private function getChildren(array $categories, int $parentId): array
    {
        $branch = array();

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = $this->getChildren($categories, $category['id']);

                if ($children) {
                    $category['children'] = $children;
                }

                $branch[] = $category;
            }
        }

        return $branch;
    }

    /**
     * Recursively sorts all categories
     * to json tree structure
     */
    public function buildCategoryTree($categories)
    {
        $elements = [
            'categories' => [],
        ];

        $rootCategories = $categories->whereNull('parent_id')
            ->toArray();
        $childCategories = $categories->whereNotNull('parent_id')
            ->toArray();

        foreach ($rootCategories as $rootCategory) {
            $rootCategory['children'] = $this->getChildren($childCategories, $rootCategory['id']);

            $elements['categories'][] = $rootCategory;
        }

        return $elements;
    }

    public function getRootCategories(): array
    {
        $categoriesCollection = Category::whereNull('parent_id')
            ->get();

        $categories = $this->addCountField($categoriesCollection);

        return $categories;
    }

    public function getSubCategories(int $parentId)
    {
        $categoriesCollection = Category::where('parent_id', $parentId)
            ->get();

        $categories = $this->addCountField($categoriesCollection);

        return $categories;
    }
}
