<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UploadImageItemRequest;
use App\Http\Resources\Item\ItemResource;
use App\Services\Item\UploadImageItemService;

class ItemController extends Controller
{
    /**
     * upload Image Item in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImageItem(UploadImageItemRequest $request)
    {
        $item = resolve(UploadImageItemService::class)->setData($request)->handle();
        return response()->json(new ItemResource($item)); // @phpstan-ignore-line
    }
}
