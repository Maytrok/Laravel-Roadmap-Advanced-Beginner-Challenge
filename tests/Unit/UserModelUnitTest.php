<?php

namespace Tests\Unit;

use App\Exceptions\AdminUserCantBeDeletedException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelUnitTest extends TestCase
{

    use RefreshDatabase;

    private $seed = true;

    /**
     * @test
     */
    public function random_user_were_created()
    {
        $this->assertTrue(User::all()->count() > 0);
    }

    /**
     * @test
     */
    public function admin_user_were_created()
    {

        $user = User::find(1);
        $this->assertEquals("admin", $user->name);
    }

    /**
     * @test
     */
    public function a_user_can_be_soft_deleted()
    {

        $this->assertCount(11, User::all());
        User::destroy(5);
        $this->assertCount(10, User::all());
        $this->assertCount(11, User::withTrashed()->get());
    }

    /**
     * @test
     */
    public function super_admin_cant_be_deleted()
    {

        $this->expectException(AdminUserCantBeDeletedException::class);
        $this->assertEquals(0, User::destroy(1));
        $user = User::find(1);
        $this->assertNotNull($user);
    }
}
