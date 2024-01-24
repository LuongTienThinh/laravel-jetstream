<?php

namespace Modules\Order\Services;

use App\Traits\ApiResponseTrait;
use App\Validators\ProductValidator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Order\Models\PaymentMethod;
use Modules\Order\Services\Interfaces\PaymentMethodInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PaymentMethodService.
 *
 * @package namespace Modules\Order\Services;
 */
class PaymentMethodService implements PaymentMethodInterface
{
    use ApiResponseTrait;

    public function getPaymentMethods(): Collection
    {
        return PaymentMethod::query()->get();
    }
}
