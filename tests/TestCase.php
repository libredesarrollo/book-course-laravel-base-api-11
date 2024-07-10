<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    function generateTokenAuth()
    {
        User::factory()->create();
        return User::first()->createToken('myapptoken')->plainTextToken;
    }
}
