<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;

abstract class BaseService
{
    /**
     * @var boolean
     */
    protected $collectsData = false;

    /**
     * @var \Prettus\Repository\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * @var \Illuminate\Database\Eloquent\Model|int
     */
    protected $model;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @var mixed
     */
    protected $header;

    /**
     * Set the data
     *
     * @param mixed $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = ($data instanceof Collection || ! $this->collectsData) ? $data : new Collection($data);

        return $this;
    }

    /**
     * Set the handler
     *
     * @param mixed $handler
     * @return self
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Set setHeader
     * @param mixed $request
     * @return self
     */
    public function setHeader($request)
    {
        $this->header = [
            'app-version' => $request->header('app-version'),
            'origin' => $request->header('origin', ''),
        ];

        return $this;
    }

    /**
     * Set the handler
     *
     * @param \Illuminate\Database\Eloquent\Model|int $model
     * @return self
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set the request to service
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return self
     */
    public function setRequest($request)
    {
        $this->setHandler($request->user());
        $this->setData($request->validated());
        $this->setHeader($request);

        return $this;
    }

    /**
     * Logic to handle the data
     * @return mixed
     */
    abstract public function handle();
}
