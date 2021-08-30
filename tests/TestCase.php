<?php

namespace Tests;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setup();
        User::where("id", ">", 2)->forceDelete();
        Client::truncate();
        Project::truncate();
    }
}
