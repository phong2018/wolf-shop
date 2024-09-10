<?php

declare(strict_types=1);

namespace App\Repositories\Eloquents;

use App\Criteria\FilterCriteria;
use App\Criteria\OrderCriteria;
use App\Criteria\WithRelationsCriteria;
use App\Models\Item;
use App\Repositories\Interfaces\ItemRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class ItemRepositoryEloquents extends BaseRepository implements ItemRepository
{
    public function model()
    {
        return Item::class;
    }

    /**
     * Retrieve a list of employees.
     *
     * @param mixed $data The data object or parameters.
     * @return mixed
     */
    public function list($data)
    {
        $query = $this->pushCriteria(new FilterCriteria($data->toArray(), $this->allowFilters()))
            ->pushCriteria(new WithRelationsCriteria($data->get('with'), $this->allowRelations()))
            ->pushCriteria(new OrderCriteria($data->get('orderBy', '-id')));

        return $data->get('perPage')
            ? $query->paginate((int) $data->get('perPage'))
            : $query->all();
    }

    /**
     * Show the specified resource.
     *
     * @param  mixed  $model
     * @param  mixed  $data
     * @return mixed
     */
    public function show($model, $data)
    {
        return $this->pushCriteria(
            new WithRelationsCriteria($data->get('with'), $this->allowRelations())
        )->find($model);
    }

    /**
     * Get the orderable fields.
     *
     * @return string[] array of getOrderableFields
     */
    public function getOrderableFields(): array
    {
        return [
            'id',
        ];
    }

    /**
     * Returns the list of filters allowed for this repository.
     *
     * @return string[] array of allowFilters
     */
    private function allowFilters(): array
    {
        return [];
    }

    /**
     * Enable relations Eloquents class.
     * @return string[] array of orderable fields.
     */
    private function allowRelations(): array
    {
        return [];
    }
}
