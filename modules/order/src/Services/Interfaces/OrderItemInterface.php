<?php

namespace Modules\Order\Services\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface OrderItemInterface.
 *
 * @package namespace Modules\Order\Services\Interfaces;
 */
interface OrderItemInterface
{
    /**
     * Create an order item
     *
     * @param  array $attributes
     * @return Builder|Model
     */
    public function create(array $attributes): Builder|Model;

    /**
     * Get all order items in order
     *
     * @param  string $orderId
     * @return Collection|array
     */
    public function getOrderItemByOrder(string $orderId): Collection|array;
}
