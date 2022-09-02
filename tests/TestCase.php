<?php

namespace Tests;

use App\Models\Graph;
use App\Models\Node;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->graph = new Graph();
        $this->node = new Node();
    }
}
