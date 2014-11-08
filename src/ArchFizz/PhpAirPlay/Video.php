<?php

namespace ArchFizz\PhpAirPlay;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class Video
{
    const BEGINNING = 0;
    const END = 1;

    /**
     * @var string
     */
    private $contentLocation;

    /**
     * @var float
     */
    private $startPosition = 0;

    /**
     * @param null|string   $contentLocation
     * @param integer|float $startPosition
     */
    public function __construct($contentLocation = null, $startPosition = self::BEGINNING)
    {
        $this->contentLocation = $contentLocation;
        $this->startPosition = $startPosition;
    }

    /**
     * @param $contentLocation
     */
    public function setContentLocation($contentLocation)
    {
        $this->contentLocation = $contentLocation;
    }

    /**
     * @return null|string
     */
    public function getContentLocation()
    {
        return $this->contentLocation;
    }

    /**
     * @todo Verify starting position is between 0 and 1
     *
     * @param float|integer $startPosition
     */
    public function setStartPosition($startPosition)
    {
        $this->startPosition = $startPosition;
    }

    /**
     * @return float|integer
     */
    public function getStartPosition()
    {
        return $this->startPosition;
    }

    /**
     * @return string
     */
    public function getPropertyList()
    {
        $plist = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
  <dict>
    <key>Content-Location</key>
    <string>%s</string>
    <key>Start-Position</key>
    <real>%f</real>
  </dict>
</plist>
XML;

        return sprintf(
            $plist, $this->contentLocation, $this->startPosition
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getPropertyList();
    }
}
