<?php

declare(strict_types=1);

namespace App\Criteria;

use Illuminate\Support\Str;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterCriteria.
 */
class FilterCriteria implements CriteriaInterface
{
    /**
     * @var mixed
     */
    protected $input;

    /**
     * List of allowable fiters
     *
     * @var mixed
     */
    protected $allows;

    /**
     * Instance of FilterCriteria
     */
    public function __construct(array $input = [], array $allows = [])
    {
        $this->input = $input;
        $this->allows = $allows;
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
        foreach ($this->allows as $key => $value) {
            $filterName = is_string($key) ? $key : $value;
            $filter = is_string($key) ? $value : $this->getFilter($value);

            // TODO: add logic to filter by relation
            // for filtering by more than one value at the same time,
            // likes: store_name, store_address
            if (isset($filterName)
                && isset($this->input[$filterName])
                && $this->isValidFilter($filter)
            ) {
                $model = $filter::apply($model, $this->input[$filterName]);
            }
        }

        return $model;
    }

    private function getFilter($filterName)
    {
        return 'App\\Filters\\' . Str::studly($filterName);
    }

    private function isValidFilter($filter)
    {
        return class_exists($filter);
    }
}
