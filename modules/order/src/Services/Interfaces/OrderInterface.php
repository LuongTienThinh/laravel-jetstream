<?php

namespace Modules\Order\Services\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

/**
 * Interface ProductRepository.
 *
 * @package namespace App\Repositories;
 */
interface OrderInterface
{
    /**
     * Create a new order
     *
     * @param  array $attributes
     * @return Builder|Model
     */
    public function create(array $attributes): Builder|Model;

    /**
     * Find order by id
     *
     * @param  string $id
     * @return Builder|array|Collection|Model
     */
    public function findOrder(string $id): array|Builder|Collection|Model;

    public function getOrderById(string $id): \Illuminate\Support\Collection;
}
