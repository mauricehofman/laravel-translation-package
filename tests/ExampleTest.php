<?php

namespace Mauricehofman\LaravelTranslationPackage\Tests;

use Orchestra\Testbench\TestCase;
use Mauricehofman\LaravelTranslationPackage\LaravelTranslationPackageServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelTranslationPackageServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
