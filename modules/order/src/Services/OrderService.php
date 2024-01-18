<?php

namespace Modules\Order\Services;

use App\Traits\ApiResponseTrait;
use App\Validators\ProductValidator;
use Modules\Order\Models\Order;
use Modules\Order\Services\Interfaces\OrderInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class OrderService extends BaseRepository implements OrderInterface
{
    use ApiResponseTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function create(array $attributes): mixed
    {
        return $this->model->create($attributes);
    }
}
