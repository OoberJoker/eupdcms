<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit871865c0bc643fbda9314348f65e70a3
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'andreskrey\\Readability\\' => 23,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'andreskrey\\Readability\\' => 
        array (
            0 => __DIR__ . '/..' . '/andreskrey/readability.php/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit871865c0bc643fbda9314348f65e70a3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit871865c0bc643fbda9314348f65e70a3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
