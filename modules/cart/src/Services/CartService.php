<?php

namespace Modules\Cart\Services;

use App\Traits\ApiResponseTrait;
use Modules\Cart\Services\Interfaces\CartInterface;
use Modules\Cart\Models\Cart;

/**
 * Class CartService.
 *
 * @package namespace Modules\Cart\Services;
 */
class CartService implements CartInterface
{
    use ApiResponseTrait;

    public function findById($id): mixed
    {
        return Cart::query()->findOrFail($id);
    }
}
