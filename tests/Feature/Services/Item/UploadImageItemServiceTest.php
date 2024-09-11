<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Item;

use App\Models\Item;
use Cloudinary\Api\Exception\AuthorizationRequired;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Tests\TestCase;

class UploadImageItemServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $cloudinary;

    protected $uploadApi;

    protected $item;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testAuthenticationWithWrongPassword(): void
    {
        // request uploadImage
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('username:wrong_password'),
        ])->postJson(route('items.uploadImageItem'), []);

        // assert unauthorzed
        $response->assertUnauthorized();
    }

    public function testUploadImageWithValidationFail(): void
    {
        // request uploadImage
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('username:password'),
        ])->postJson(route('items.uploadImageItem'), []);

        // assert valiation fail
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['image', 'item_id']);
    }

    public function testHandleSuccessfullyUpdatesImage(): void
    {
        // fake data
        $this->item = Item::factory()->create();
        $fakeCloudinarySecureUrl = 'cloudinary_secure_url';
        $fakeCloudinaryPublicId = 'cloudinary_public_id';

        // mock service Cloudinary
        $this->uploadApi = $this->mock(UploadApi::class, function (MockInterface $mock) use ($fakeCloudinarySecureUrl, $fakeCloudinaryPublicId) {
            $mock->shouldReceive('upload')
                ->andReturn([
                    'secure_url' => $fakeCloudinarySecureUrl,
                    'public_id' => $fakeCloudinaryPublicId,
                ]);
            $mock->shouldReceive('destroy')
                ->andReturn(true);
        });

        $this->cloudinary = $this->mock(Cloudinary::class, function (MockInterface $mock) {
            $mock->shouldReceive('uploadApi')
                ->andReturn($this->uploadApi);
        });

        // request uploadImage
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('username:password'),
        ])->postJson(route('items.uploadImageItem'), [
            'image' => UploadedFile::fake()->image('test-image.jpg'),
            'item_id' => $this->item->id,
        ]);

        // assert result success
        $response->assertOk();
        $this->assertDatabaseHas('items', [
            'id' => $this->item->id,
            'img_url' => $fakeCloudinarySecureUrl,
            'img_url_public_id' => $fakeCloudinaryPublicId,
        ]);
    }

    public function testHandleThrowsAuthorizationRequiredException(): void
    {
        // fake data
        $this->item = Item::factory()->create();

        // mock service
        $this->uploadApi = $this->mock(UploadApi::class, function (MockInterface $mock) {
            $mock->shouldReceive('upload') // @phpstan-ignore-line
                ->andThrow(AuthorizationRequired::class);
            $mock->shouldReceive('destroy')
                ->andReturn(true);
        });

        $this->cloudinary = $this->mock(Cloudinary::class, function (MockInterface $mock) {
            $mock->shouldReceive('uploadApi')
                ->andReturn($this->uploadApi);
        });

        // request uploadImage
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('username:password'),
        ])->postJson(route('items.uploadImageItem'), [
            'image' => UploadedFile::fake()->image('test-image.jpg'),
            'item_id' => $this->item->id,
        ]);

        // assert badRequest
        $response->assertBadRequest();
    }
}
