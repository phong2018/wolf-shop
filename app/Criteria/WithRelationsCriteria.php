<?php

declare(strict_types=1);

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WithRelationsCriteria.
 */
class WithRelationsCriteria implements CriteriaInterface
{
    /**
     * List of request relations from query string
     *
     * @var array
     */
    protected $input; // @phpstan-ignore-line

    /**
     * List of allow relations
     *
     * @var array
     */
    protected $allows; // @phpstan-ignore-line

    /**
     * An constructor of WithRelationsCriteria
     *
     * @param mixed $input
     * @param array $allows
     */
    public function __construct($input = '', $allows = []) // @phpstan-ignore-line
    {
        $this->input = array_filter(
            array_map(
                '\Illuminate\Support\Str::camel',
                is_array($input) ? $input : explode(',', $input ?? '')
            )
        );

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
        $withs = [];

        foreach ($this->allows as $key => $value) {
            if (! in_array(is_numeric($key) ? $value : $key, $this->input, true)) {
                continue;
            }

            if (is_array($value)) {
                $withs[$value[0]] = $value[1] ?? function () {
                    //
                };
                continue;
            }

            $withs[$key] = $value;
        }

        return empty($withs) ? $model : $model->with($withs);
    }
}
