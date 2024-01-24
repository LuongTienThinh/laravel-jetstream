<?php

namespace Modules\Order\Services;

use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Models\OrderItem;
use Modules\Order\Services\Interfaces\OrderItemInterface;

/**
 * Class OrderItemService.
 *
 * @package namespace Modules\Order\Services;
 */
class OrderItemService implements OrderItemInterface
{
    use ApiResponseTrait;

    public function create(array $attributes): Builder|Model
    {
        return OrderItem::query()->create($attributes);
    }

    public function getOrderItemByOrder(string $orderId): Collection|array
    {
        $orderItem = OrderItem::query()->where('order_id', '=', $orderId)
                                       ->with('product')
                                       ->get();

        return $orderItem->map(function ($item) {
            $item->product_name = $item->product->name;
            unset($item->product);
            return $item;
        });
    }
}
