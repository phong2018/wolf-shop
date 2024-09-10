<?php

declare(strict_types=1);

namespace App\Http\Requests\Item;

use App\Http\Requests\BaseRequest;

class ListItemRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return mixed[]
     */
    public function rules()
    {
        return array_merge($this->commonListRules(), [
            'id' => [
                'nullable',
                'integer',
            ],
        ]);
    }
}
