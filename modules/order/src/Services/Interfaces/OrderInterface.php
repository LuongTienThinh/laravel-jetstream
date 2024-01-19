<?php

namespace Modules\Order\Services\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ProductRepository.
 *
 * @package namespace App\Repositories;
 */
interface OrderInterface extends RepositoryInterface
{
    /**
     * Create a new order
     *
     * @param  array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed;
}
