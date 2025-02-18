<?php

declare(strict_types=1);

namespace App\Http\Requests\Item;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UploadImageItemRequest extends BaseRequest
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
        return [
            'item_id' => [
                'required',
                'integer',
                Rule::exists('items', 'id'),
            ],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:' . BaseRequest::MAX_IMAGE_SIZE,
            ],
        ];
    }
}
