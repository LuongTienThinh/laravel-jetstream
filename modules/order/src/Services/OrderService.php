<?php

namespace Modules\Order\Services;

use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Modules\Order\Models\Order;
use Modules\Order\Services\Interfaces\OrderInterface;

/**
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class OrderService implements OrderInterface
{
    use ApiResponseTrait;

    public function create(array $attributes): Builder|Model
    {
        return Order::query()->create($attributes);
    }

    public function findOrder(string $id): Builder|array|Collection|Model
    {
        return Order::query()->where('id', '=', $id);
    }

    public function getOrderById(string $id): Collection
    {
        $order = $this->findOrder($id)->get();
        $orderItem = (new OrderItemService())->getOrderItemByOrder($id);

        $order[0]['order_item'] = $orderItem;

        return $order;
    }
}
