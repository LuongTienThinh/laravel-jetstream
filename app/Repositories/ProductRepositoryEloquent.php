<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ProductRepository;
use App\Models\Product;
use App\Validators\ProductValidator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductRepositoryEloquent extends BaseRepository implements ProductRepository
{
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


    public function create(array $attributes): void
    {
        $this->model->create($attributes);
    }

    public function update(array $attributes, $id): bool
    {
        $product = $this->findById($id);
        if ($product instanceof $this->model) {
            $product->update($attributes);
            return true;
        }
        return false;
    }

    public function delete($id): bool
    {
        $product = $this->findById($id);
        if ($product instanceof $this->model) {
            $product->delete();
            return true;
        }
        return false;
    }

    public function findById(string $id)
    {
        return $this->model->findOrFail($id);
    }

    public function filterSearch(string $search): Builder
    {
        return $this->model->where('name', 'like', '%' . $search . '%')->with('category');
    }

    public function getProductWith(): Builder
    {
        return $this->model->with('category');
    }
}
