<?php

namespace Modules\Order\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface PaymentMethodInterface.
 *
 * @package namespace Modules\Order\Services\Interfaces;
 */
interface PaymentMethodInterface
{
    /**
     * Get list payment methods
     *
     * @return Collection
     */
    public function getPaymentMethods(): Collection;
}
