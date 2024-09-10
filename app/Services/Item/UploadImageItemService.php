<?php

declare(strict_types=1);

namespace App\Services\Item;

use App\Repositories\Interfaces\ItemRepository;
use App\Services\BaseService;
use Cloudinary\Cloudinary;

class UploadImageItemService extends BaseService
{
    protected $collectsData = true;

    protected Cloudinary $cloudinary;

    public function __construct(
        ItemRepository $repository,
        Cloudinary $cloudinary
    ) {
        $this->repository = $repository;
        $this->cloudinary = $cloudinary;
    }

    /**
     * Logic to handle the data
     * @return mixed
     */
    public function handle()
    {
        $file = $this->data->get('image');
        $uploadResult = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'items_folder',
        ]);
        $imageUrl = $uploadResult['secure_url'];

        return [
            'url' => $imageUrl,
        ];
    }
}
