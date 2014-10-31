<?php

namespace ArchFizz\PhpAirPlay;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Application extends BaseApplication
{
    const NAME = 'PhpAirPlay';
    const VERSION = '0.0.2-DEV';
    const VERSION_ID = '00002';
    const MAJOR_VERSION = '0';
    const MINOR_VERSION = '0';
    const RELEASE_VERSION = '2';
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

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }
}
