<?php

namespace App\Repositories;

use Exception;
use Illuminate\Http\JsonResponse;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Product;
use App\Validators\ProductValidator;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ApiResponseTrait;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductRepositoryEloquent extends BaseRepository implements ProductRepository
{
    use ApiResponseTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Create a product
     *
     * @param  array $attributes
     * @return void
     */
    public function create(array $attributes): void
    {
        $this->model->create($attributes);
    }

    /**
     * Update a product
     *
     * @param  array $attributes
     * @param  $id
     * @return bool
     */
    public function update(array $attributes, $id): bool
    {
        $product = $this->findById($id);
        if ($product instanceof $this->model) {
            $product->update($attributes);
            return true;
        }
        return false;
    }

    /**
     * Delete a product
     *
     * @param  $id
     * @return bool
     */
    public function delete($id): bool
    {
        $product = $this->findById($id);
        if ($product instanceof $this->model) {
            $product->delete();
            return true;
        }
        return false;
    }

    /**
     * Find a product by id
     *
     * @param  $id
     * @return mixed
     */
    public function findById($id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get list products by search content
     *
     * @param  string $search
     * @return Builder
     */
    public function filterSearch(string $search): Builder
    {
        return $this->model->where('name', 'like', '%' . $search . '%')->with('category');
    }

    /**
     * Insert category into products
     *
     * @return Builder
     */
    public function getProductWith(): Builder
    {
        return $this->model->with('category');
    }



    /**
     * Paginate for the list of products
     *
     * @param  Builder  $listProduct
     * @param  int|null $page
     * @param  int|null $perPage
     * @return JsonResponse
     */
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
