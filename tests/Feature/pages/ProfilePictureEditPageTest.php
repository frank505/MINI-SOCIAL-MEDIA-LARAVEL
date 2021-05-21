<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfilePictureEditPageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */

    protected $user;

    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->make();
    }

    public function createUser()
    {
        User::factory()->times(10)->create();
    }

    public function userAccount()
    {
        $user =  User::factory()->create([
            'name' => 'dddd',
            'username'=>'dewdsdvfdsdf',
            'email' => 'akpufranklin2@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        return $user;
    }


    public function test_submit_edit_profile_picture()
    {
        $user = $this->userAccount();

        Storage::fake('local');
        $width = 200;
        $height = 200;
        $file =  UploadedFile::fake()->image('avatar.jpg', $width, $height)->size(100);
        $response =   $this->actingAs($user)->call('PATCH',
            '/panel/profile/edit-profile-picture',
            array(
            '_token' => csrf_token(),
                'file' => $file
        ));

        Storage::disk('local')->assertExists($file->hashName());

        $response->assertJson([
            'success'=>true
        ]);

    }

}
