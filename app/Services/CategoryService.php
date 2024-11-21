<?php

namespace App\Services;

class CategoryService
{
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
}
