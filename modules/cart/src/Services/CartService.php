<?php

namespace Modules\Cart\Services;

use App\Traits\ApiResponseTrait;
use App\Validators\ProductValidator;
use Modules\Cart\Models\Cart;
use Modules\Cart\Services\Interfaces\CartInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CartService extends BaseRepository implements CartInterface
{
    use ApiResponseTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Cart::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function findById($id): mixed
    {
        return $this->model->findOrFail($id);
    }
}
