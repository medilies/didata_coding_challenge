<?php

namespace Tests;

use App\Models\Graph;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->graph = new Graph();
    }
}
