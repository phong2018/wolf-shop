<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Item\ListItemRequest;
use App\Http\Requests\Item\UploadImageItemRequest;
use App\Http\Resources\Item\ItemCollection;
use App\Http\Resources\Item\ItemResource;
use App\Services\Item\ListItemService;
use App\Services\Item\ShowItemService;
use App\Services\Item\UploadImageItemService;

class ItemController extends Controller
{
    /**
     * get a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListItemRequest $request)
    {
        $items = resolve(ListItemService::class)->setRequest($request)->handle();
        return response()->json(new ItemCollection($items)); // @phpstan-ignore-line
    }

    /**
     * upload Image Item in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImageItem(UploadImageItemRequest $request)
    {
        $item = resolve(ShowItemService::class)->setRequest($request)->setModel($request->get('item_id'))->handle();
        $itemUpdated = resolve(UploadImageItemService::class)->setRequest($request)->setModel($item)->handle();
        return response()->json(new ItemResource($itemUpdated)); // @phpstan-ignore-line
    }
}
