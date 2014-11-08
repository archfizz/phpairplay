<?php

namespace ArchFizz\PhpAirPlay;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Mirror
{
    const TEMP_PATH_TO_IMAGE = '/tmp/airplay.jpg';

    /**
     * @var ProcessBuilder
     */
    private $processBuilder;

    /**
     * @var string
     */
    private $utility;

    /**
     * @var array
     */
    private $utilityCommands = [
        'imagemagick' => 'import',
        'shutter' => 'shutter',
        'gnome' => 'gnome-screenshot',
        'mac' => 'screencapture',
    ];

    /**
     * @var array
     */
    private $utilityArguments = [
        'imagemagick' => ['-window', 'root'],
        'shutter' => ['-f', '-e', '-o'],
        'gnome' => ['-f'],
        'mac' => ['-m', '-x', '-C', '-t', 'jpg'],
    ];

    /**
     * @var string
     */
    private $image;

    /**
     * @param ProcessBuilder $processBuilder
     * @param string         $utility
     * @param null|string    $image
     */
    public function __construct(ProcessBuilder $processBuilder, $utility = 'imagemagick', $image = null)
    {
        $this->processBuilder = $processBuilder;
        $this->utility = $utility;
        $this->image = $image ?: self::TEMP_PATH_TO_IMAGE;
    }

    /**
     * @param string $utility
     */
    public function setUtility($utility)
    {
        $this->utility = $utility;
    }

    /**
     * Take screenshot.
     */
    public function reflect()
    {
        $this->processBuilder->setPrefix($this->utilityCommands[$this->utility]);

        $this->processBuilder->setArguments(array_merge($this->utilityArguments[$this->utility], [$this->image]));
        $process = $this->processBuilder->getProcess();

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(sprintf("Could not save image to %s.%s", $this->image, \PHP_EOL . $process->getErrorOutput()));
        }
    }

    /**
     * @return string The path to the image.
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string[]
     */
    public function getSupportedUtilities()
    {
        return array_keys($this->utilityCommands);
    }
}
