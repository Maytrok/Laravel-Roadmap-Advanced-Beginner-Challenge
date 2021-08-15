<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserModelUnitTest extends TestCase
{

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
}
