<?php

namespace App\Repositories;

use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductRepositoryEloquent implements ProductRepository
{
    use ApiResponseTrait;

    public function create(array $attributes): void
    {
        Product::query()->create($attributes);
    }

    public function update(array $attributes, $id): bool
    {
        $product = $this->findById($id);
        if ($product instanceof Product) {
            $product->update($attributes);
            return true;
        }
        return false;
    }

    public function delete($id): bool
    {
        $product = $this->findById($id);
        if ($product instanceof Product) {
            $product->delete();
            return true;
        }
        return false;
    }

    public function findById($id): mixed
    {
        return Product::query()->findOrFail($id);
    }

    public function filterSearch(string $search): Builder
    {
        return Product::query()->where('name', 'like', '%' . $search . '%')
                               ->with('category');
    }

    public function getProductWith(): Builder
    {
        return Product::query()->with('category');
    }

    public function productPagination(Builder $listProduct, int $page = null, int $perPage = null): JsonResponse
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 5;

        $products = $listProduct->skip(($page - 1) * $perPage)->take($perPage)->get();
        $nextProducts = $listProduct->skip($page * $perPage)->take($perPage)->get();

        $prev = $page > 1 && $products->isNotEmpty();
        $next = $nextProducts->isNotEmpty();

        try {
            $listProducts = $products->map(function($item, $index) use($page, $perPage) {
                $item->category_name = $item->category->name;
                $item->no = ($page - 1) * $perPage + 1 + $index;
                unset($item->category);
                return $item;
            });

            $newData = [
                'products'=> $listProducts,
                'prev' => $prev,
                'next' => $next
            ];

            $message = 'Get list products successfully';

            return $this->successResponse($newData, 200, $message);
        } catch (Exception $e) {
            return $this->errorResponse(500, $e->getMessage());
        }
    }
}
