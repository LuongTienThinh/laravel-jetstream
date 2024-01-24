<?php

namespace Modules\Order\Services;

use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Modules\Order\Models\Order;
use Modules\Order\Services\Interfaces\OrderInterface;

/**
 * Class OrderService.
 *
 * @package namespace Modules\Order\Services;
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

    public function getListOrders(string $userId): Collection|array
    {
        $orders = Order::query()->where('user_id', '=', $userId)
                                ->orderBy('created_at', 'desc')
                                ->get();

        return $orders->map(function ($item) {
            $item->order_time = $item->created_at->diffForHumans(Carbon::now());
            $item->total_price = $item->orderItem->sum(function ($orderItem) {
                return $orderItem->total_price;
            });
            return $item;
        });
    }
}
