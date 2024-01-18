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
interface PaymentMethodInterface extends RepositoryInterface
{
    /**
     * Get list payment methods
     *
     * @return Collection
     */
    public function getPaymentMethods(): Collection;
}
