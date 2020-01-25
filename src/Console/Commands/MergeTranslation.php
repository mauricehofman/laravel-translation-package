<?php

namespace Mauricehofman\LaravelTranslationPackage\Console\Commands;

use Illuminate\Console\Command;
use Mauricehofman\LaravelTranslationPackage\LaravelTranslation;

class MergeTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge project translations with framework source translations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $laravelTranslation = new LaravelTranslation();
        $laravelTranslation->merge();
    }
}
