<?php

namespace Mauricehofman\LaravelTranslationPackage;

use GuzzleHttp\Client;

class LaravelTranslation
{
    /**
     * @var array
     */
    public $files = [
        'auth.php',
        // 'pagination.php',
        // 'passwords.php',
        // 'validation.php',
    ];

    /**
     * @var string
     */
    private $sourcePath;

    /**
     * @var string
     */
    private $projectPath;

    /**
     * LaravelTranslation constructor.
     */
    public function __construct()
    {
        $this->sourcePath =  dirname(__DIR__, 1) . '/assets/versions/' . app()->version();
        $this->projectPath = resource_path('lang/en');
    }

    /**
     * @param array $files
     */
    public function getSource(array $files = []): void
    {
        if (!$files) {
            $files = $this->files;
        }

        collect($files)
            ->each(function (string $fileName) {
                $client = new Client();
                $response = $client->get('https://raw.githubusercontent.com/laravel/laravel/v' . app()->version() . '/resources/lang/en/' . $fileName);

                if (!file_exists($this->sourcePath)) {
                    mkdir($this->sourcePath, 0777, true);
                }
                $file = fopen($this->sourcePath . '/' . $fileName, "w+");
                fwrite($file, $response->getBody()->getContents());
                fclose($file);
            });
    }

    /**
     * @return void
     */
    public function deleteSource(): void
    {
        foreach (scandir($this->sourcePath) as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            }
            unlink($this->sourcePath . '/' . $file);
        }

        rmdir($this->sourcePath);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $i = 0;

        foreach (scandir($this->sourcePath) as $fileName) {
            if ('.' === $fileName || '..' === $fileName) {
                continue;
            }
            $i++;
        }

        return $i;
    }

    /**
     * @return array
     */
    public function files(): array
    {
        $fileNames = [];

        foreach (scandir($this->sourcePath) as $fileName) {
            if ('.' === $fileName || '..' === $fileName) {
                continue;
            }
            array_push($fileNames, $fileName);
        }

        return $fileNames;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function sourcePath(string $fileName): string
    {
        return $this->sourcePath . '/' . $fileName;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function projectPath(string $fileName): string
    {
        return $this->projectPath . '/' . $fileName;
    }

    /**
     * @return array
     */
    public function paths(): array
    {
        $paths = [];

        foreach(['sourcePath', 'projectPath'] as $location) {
            foreach (scandir($this->{$location}) as $fileName) {
                if ('.' === $fileName || '..' === $fileName) {
                    continue;
                }
                $paths[$location][$fileName] = $this->{$location} . '/' . $fileName;
            }
        }

        return $paths;
    }

    /**
     * @param array $files
     */
    public function merge(array $files= []): void
    {
        if(!$files) {
            $files = $this->files;
        }

        $this->getSource($files);

        collect($files)
            ->each(function (string $fileName) {

                $source = include $this->sourcePath($fileName);
                $project = include $this->projectPath($fileName);

                $merged = array_merge($source, $project);

                $file = fopen($this->sourcePath($fileName), 'w');
                fwrite($file, '<?php' . PHP_EOL . PHP_EOL . 'return ' . var_export($merged, true) . ';' . PHP_EOL);
                fclose($file);
            });

        $this->deleteSource();
    }
}