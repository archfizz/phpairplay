<?php

namespace ArchFizz\PhpAirPlay;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Adam Elsodaney <aelso1@gmail.com>
 */
class Application extends BaseApplication
{
    const NAME = 'PhpAirPlay';
    const VERSION = '0.0.1-DEV';
    const VERSION_ID = '00001';
    const MAJOR_VERSION = '0';
    const MINOR_VERSION = '0';
    const RELEASE_VERSION = '1';
    const EXTRA_VERSION = 'DEV';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct(self::NAME, self::VERSION);

        $this->filesystem = $filesystem;
    }
}
