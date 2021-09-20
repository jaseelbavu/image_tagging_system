<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * test for uploading photo.
     *
     * @return void
     */
    public function test_upload_photo()
    {
        $response = $this->actingAs($this->user)
        ->json('POST', route('image.upload'), [
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'visibility' => 1,
            'category' => 'Men'
        ]);

        $response->assertStatus(200);
    }

    /**
     * test for creating image tag.
     *
     * @return void
     */
    public function test_create_image_tag()
    {
        $data = Image::create([
            'name' => UploadedFile::fake()->image('avatar.jpg'),
            'path' => UploadedFile::fake()->image('avatar.jpg')->path(),
            'user_id' => $this->user->id,
            'visibility' => 1,
            'category' => 'Men'
        ]);

        $response = $this->actingAs($this->user)
            ->json('POST', route('image.tag.add', $data->id), [
                'coords' => "10,10,60,105",
                'label' => 'Bag',
                'description' => 'Unisex bags'
            ]);

            $response->assertStatus(200);
    }
}
