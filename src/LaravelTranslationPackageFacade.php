<?php

namespace Mauricehofman\LaravelTranslationPackage;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mauricehofman\LaravelTranslationPackage\Skeleton\SkeletonClass
 */
class LaravelTranslationPackageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-translation-package';
    }
}
