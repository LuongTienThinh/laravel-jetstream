<?php

namespace Modules\Cart\Services\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ProductRepository.
 *
 * @package namespace App\Repositories;
 */
interface CartService extends RepositoryInterface
{
    /**
     * Find a product by id
     *
     * @param  $id
     * @return mixed
     */
    public function findById($id): mixed;
}
