<?php

declare(strict_types=1);

namespace App\Services\Item;

use App\Repositories\Interfaces\ItemRepository;
use App\Services\BaseService;
use Cloudinary\Cloudinary;

class UploadImageItemService extends BaseService
{
    public const IMAGE_PATH_ITEMS = 'items_folder';

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
        $uploadResult = $this->uploadImageItem();

        $itemUpdated = $this->repository->update([
            'img_url' => $uploadResult['secure_url'],
            'img_url_public_id' => $uploadResult['public_id'],
        ], $this->model->id); // @phpstan-ignore-line

        return $itemUpdated;
    }

    /**
     * uploadImageItem
     *
     * @return mixed
     */
    public function uploadImageItem()
    {
        $file = $this->data->get('image');

        // delete existed image
        if (! empty($this->model->img_url_public_id)) {
            $this->cloudinary->uploadApi()->destroy($this->model->img_url_public_id);
        }

        // update new image
        $uploadResult = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => self::IMAGE_PATH_ITEMS,
        ]);

        return $uploadResult;
    }
}
