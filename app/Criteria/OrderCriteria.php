<?php

declare(strict_types=1);

namespace App\Criteria;

use Illuminate\Support\Str;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OrderCriteria.
 */
class OrderCriteria implements CriteriaInterface
{
    /**
     * @var mixed
     */
    protected $orders;

    /**
     * @var mixed
     */
    protected $options;

    /**
     * Instance of Order2Criteria
     *
     * @param array|string $input
     */
    public function __construct($input, $options = [])
    {
        $this->orders = array_filter(is_array($input) ? $input : explode(',', $input));
        $this->options = $options;
    }

    /**
     * Apply criteria in query repository
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (! method_exists($repository, 'getOrderableFields')) {
            return $model;
        }

        $orderableFields = $repository->getOrderableFields();

        if (! empty($this->options)) {
            $orderableFields = array_merge($this->options, $orderableFields);
        }

        if (empty($this->orders)) {
            return $model->orderByDesc($orderableFields[0]); // @phpstan-ignore-line
        }

        foreach ($this->orders as $o) {
            $desc = $o[0] === '-';
            $field = $desc ? substr($o, 1) : $o;

            if (! in_array($field, $orderableFields, true)) {
                continue;
            }

            // order by relation
            if (preg_match('/^(\w+)\.(\w+)$/', $field, $found)) {
                $relationName = Str::camel($found[1]);
                $relation = $model->getModel()->{$relationName}();
                $field = $relation->getModel()->getTable() . '_' . $found[2];

                $model = $model->select()
                    ->selectSub(function ($query) use ($relation, $found) {
                        $relatedAlias = Str::snake(class_basename($relation->getRelated()));

                        return $query->select($relatedAlias . '.' . $found[2])
                            ->fromRaw($relation->getModel()->getTable() . ' as ' . $relatedAlias)
                            ->whereColumn($relatedAlias . '.' . $relation->getOwnerKeyName(), $relation->getQualifiedForeignKeyName());
                    }, $field);
            }

            // order by normal field
            $model = $desc ? $model->orderBy($field, 'DESC') : $model->orderBy($field);
        }

        return $model;
    }
}
