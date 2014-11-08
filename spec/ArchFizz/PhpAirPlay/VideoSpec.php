<?php

namespace spec\ArchFizz\PhpAirPlay;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VideoSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(null, 0);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\Video');
    }

    function it_exposes_the_Content_Location()
    {
        $videoUrl = 'http://192.168.256.1/video.mp4';

        $this->setContentLocation($videoUrl);

        $this->getContentLocation()->shouldBe($videoUrl);
    }

    function it_exposes_the_Start_Position()
    {
        $startPosition = 0.54;

        $this->setStartPosition($startPosition);

        $this->getStartPosition()->shouldBe($startPosition);
    }

    function it_generates_a_Property_List_XML_document_for_a_request()
    {
        $videoUrl = 'http://192.168.256.1/video.mp4';

        $this->setContentLocation($videoUrl);

        $plist = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
  <dict>
    <key>Content-Location</key>
    <string>http://192.168.256.1/video.mp4</string>
    <key>Start-Position</key>
    <real>0.000000</real>
  </dict>
</plist>
XML;

        $this->getPropertyList()->shouldReturn($plist);
    }

    function it_returns_the_Property_List_XML_when_treated_as_a_string()
    {
        $videoUrl = 'http://192.168.256.1/video.mp4';

        $this->setContentLocation($videoUrl);
        $this->setStartPosition(0.1);

        $plist = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
  <dict>
    <key>Content-Location</key>
    <string>http://192.168.256.1/video.mp4</string>
    <key>Start-Position</key>
    <real>0.100000</real>
  </dict>
</plist>
XML;

        $this->__toString()->shouldBe($plist);
    }

    function _it_throws_an_Out_Of_Range_Exception_when_the_Start_Position_is_to_a_value_not_between_0_and_1()
    {}

    function _it_extends_or_decorates_SplFileInfo()
    {}
}
