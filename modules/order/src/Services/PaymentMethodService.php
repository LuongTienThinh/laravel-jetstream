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
 * Class ProductRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PaymentMethodService extends BaseRepository implements PaymentMethodInterface
{
    use ApiResponseTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PaymentMethod::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getPaymentMethods(): Collection
    {
        return $this->model->all();
    }
}
