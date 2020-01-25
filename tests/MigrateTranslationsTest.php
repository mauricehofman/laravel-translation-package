<?php

namespace Mauricehofman\LaravelLanguagePackage\Tests;

use Illuminate\Foundation\Application;
use Mauricehofman\LaravelTranslationPackage\LaravelTranslation;
use Mauricehofman\LaravelTranslationPackage\LaravelTranslationPackageServiceProvider;
use Orchestra\Testbench\TestCase;

class MigrateTranslationsTest extends TestCase
{
    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [LaravelTranslationPackageServiceProvider::class];
    }

    /** @test */
    public function it_can_read_project_translations()
    {
        $laravelTranslation = new LaravelTranslation();

        collect($laravelTranslation->files)
            ->each(function (string $fileName) use ($laravelTranslation) {
                $this->assertFileExists($laravelTranslation->projectPath($fileName));
            });
    }

    /** @test */
    public function it_can_read_laravel_version()
    {
        $this->assertIsString(app()->version());
    }

    // /** @test */
    // public function it_can_read_github_source_for_project()
    // {
    //     collect($this->files)
    //         ->each(function (string $fileName) {
    //             $client = new Client();
    //             $response = $client->get('https://raw.githubusercontent.com/laravel/laravel/v' . app()->version() . '/resources/lang/en/' . $fileName);
    //
    //             $this->assertEquals(200, $response->getStatusCode());
    //
    //             usleep(250000);
    //         });
    // }

    /** @test */
    public function it_can_temporarily_write_source_files_to_disk()
    {
        $laravelTranslation = new LaravelTranslation();
        $laravelTranslation->getSource();

        collect($laravelTranslation->files)
            ->each(function (string $fileName) use ($laravelTranslation) {
                $this->assertFileExists($laravelTranslation->sourcePath($fileName));
            });
    }

    /** @test */
    public function it_can_remove_temporarily_stored_files()
    {
        $laravelTranslation = new LaravelTranslation();
        $laravelTranslation->getSource();
        $laravelTranslation->deleteSource();

        collect($laravelTranslation->files)
            ->each(function (string $fileName) use ($laravelTranslation) {
                $this->assertFileNotExists($laravelTranslation->sourcePath($fileName));
            });
    }

    /** @test */
    public function it_can_return_amount_of_files_found_at_source()
    {
        $laravelTranslation = new LaravelTranslation();
        $laravelTranslation->getSource();
        $this->assertIsInt($laravelTranslation->count());
    }

    /** @test */
    public function it_can_return_source_file_names()
    {
        $laravelTranslation = new LaravelTranslation();
        $laravelTranslation->getSource();
        $this->assertIsArray($laravelTranslation->files());
    }

    /** @test */
    public function it_can_return_source_file_paths()
    {
        $laravelTranslation = new LaravelTranslation();
        $laravelTranslation->getSource();
        $this->assertIsArray($laravelTranslation->paths());
    }

    /** @test */
    public function it_can_merge_source_with_project()
    {
        $laravelTranslation = new LaravelTranslation();
        $laravelTranslation->merge();

        collect($laravelTranslation->files)
            ->each(function (string $fileName) use ($laravelTranslation) {
                $this->assertFileExists($laravelTranslation->projectPath($fileName));
            });
    }
}
