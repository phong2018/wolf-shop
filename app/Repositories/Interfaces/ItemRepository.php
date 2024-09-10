<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 *
 * @package namespace App\Repositories\Interfaces;
 */
interface ItemRepository extends RepositoryInterface
{
    /**
     * Retrieve a list of employees.
     *
     * @param mixed $data The data object or parameters.
     * @return mixed
     */
    public function list($data);

    /**
     * Show the specified resource.
     *
     * @param  mixed  $model
     * @param  mixed  $data
     * @return mixed
     */
    public function show($model, $data);
}
