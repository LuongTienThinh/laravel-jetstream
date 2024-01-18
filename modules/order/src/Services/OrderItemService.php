<?php

namespace Modules\Order\Services;

use App\Traits\ApiResponseTrait;
use App\Validators\ProductValidator;
use Modules\Order\Models\OrderItem;
use Modules\Order\Services\Interfaces\OrderInterface;
use Modules\Order\Services\Interfaces\OrderItemInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class OrderItemService extends BaseRepository implements OrderItemInterface
{
    use ApiResponseTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OrderItem::class;
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
