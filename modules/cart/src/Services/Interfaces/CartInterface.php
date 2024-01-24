<?php

namespace Modules\Cart\Services\Interfaces;

/**
 * Interface CartInterface.
 *
 * @package namespace Modules\Cart\Services\Interfaces;
 */
interface CartInterface
{
    /**
     * Find a product by id
     *
     * @param  $id
     * @return mixed
     */
    public function findById($id): mixed;
}
