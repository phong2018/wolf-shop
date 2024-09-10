<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** @SuppressWarnings(PHPMD.NumberOfChildren) */
class BaseRequest extends FormRequest
{
    public const INT_32_MIN = 1;

    public const LIMIT_DEFAULT_MAX = 100;

    public const ORDER_DEFAULT_LENGTH = 100;

    public const WITH_DEFAULT_LENGTH = 500;

    public const DEFAULT_PER_PAGE = 10;

    public const MIN_LENGTH = 3;

    public const MAX_LENGTH = 255;

    public const MIN_LENGTH_PASSWORD = 8;

    public const MAX_LENGTH_PASSWORD = 50;

    public const MAX_IMAGE_SIZE = 5000;

    /**
     * Common list rules
     *
     * @return mixed[] array
     */
    public function commonListRules()
    {
        return [
            'page' => [
                'bail',
                'sometimes',
                'integer',
            ],
            'perPage' => [
                'bail',
                'sometimes',
                'integer',
                'min:' . self::INT_32_MIN,
                'max:' . static::LIMIT_DEFAULT_MAX,
            ],
            'orderBy' => [
                'bail',
                'sometimes',
                'string',
                'max:' . self::ORDER_DEFAULT_LENGTH,
            ],
            'with' => [
                'bail',
                'sometimes',
                'string',
                'max:' . self::WITH_DEFAULT_LENGTH,
            ],
        ];
    }
}
