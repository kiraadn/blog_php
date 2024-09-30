<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfe8075b722b5e63749b6a4ea7c023749
{
    public static $files = array (
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
        '7cca0da9604df282f16d129f538c9833' => __DIR__ . '/..' . '/digitalnature/php-ref/ref.php',
        '89efb1254ef2d1c5d80096acd12c4098' => __DIR__ . '/..' . '/twig/twig/src/Resources/core.php',
        'ffecb95d45175fd40f75be8a23b34f90' => __DIR__ . '/..' . '/twig/twig/src/Resources/debug.php',
        'c7baa00073ee9c61edf148c51917cfb4' => __DIR__ . '/..' . '/twig/twig/src/Resources/escaper.php',
        'f844ccf1d25df8663951193c3fc307c8' => __DIR__ . '/..' . '/twig/twig/src/Resources/string_loader.php',
        '9c3b312f47066964376ce00b1d45c129' => __DIR__ . '/../..' . '/sistema/configuracao.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'sistema\\' => 8,
        ),
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
        ),
        'P' => 
        array (
            'Pecee\\' => 6,
            'Pagerfanta\\Twig\\' => 16,
            'Pagerfanta\\Solarium\\' => 20,
            'Pagerfanta\\Elastica\\' => 20,
            'Pagerfanta\\Doctrine\\PHPCRODM\\' => 29,
            'Pagerfanta\\Doctrine\\ORM\\' => 24,
            'Pagerfanta\\Doctrine\\MongoDBODM\\' => 31,
            'Pagerfanta\\Doctrine\\DBAL\\' => 25,
            'Pagerfanta\\Doctrine\\Collections\\' => 32,
            'Pagerfanta\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'sistema\\' => 
        array (
            0 => __DIR__ . '/../..' . '/sistema',
        ),
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Pecee\\' => 
        array (
            0 => __DIR__ . '/..' . '/pecee/simple-router/src/Pecee',
        ),
        'Pagerfanta\\Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Twig',
        ),
        'Pagerfanta\\Solarium\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Adapter/Solarium',
        ),
        'Pagerfanta\\Elastica\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Adapter/Elastica',
        ),
        'Pagerfanta\\Doctrine\\PHPCRODM\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Adapter/Doctrine/PHPCRODM',
        ),
        'Pagerfanta\\Doctrine\\ORM\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Adapter/Doctrine/ORM',
        ),
        'Pagerfanta\\Doctrine\\MongoDBODM\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Adapter/Doctrine/MongoDBODM',
        ),
        'Pagerfanta\\Doctrine\\DBAL\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Adapter/Doctrine/DBAL',
        ),
        'Pagerfanta\\Doctrine\\Collections\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Adapter/Doctrine/Collections',
        ),
        'Pagerfanta\\' => 
        array (
            0 => __DIR__ . '/..' . '/pagerfanta/pagerfanta/lib/Core',
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PhpToken' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/PhpToken.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
        'Verot\\Upload\\Upload' => __DIR__ . '/..' . '/verot/class.upload.php/src/class.upload.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfe8075b722b5e63749b6a4ea7c023749::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfe8075b722b5e63749b6a4ea7c023749::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfe8075b722b5e63749b6a4ea7c023749::$classMap;

        }, null, ClassLoader::class);
    }
}
