<?php

namespace spec\ArchFizz\PhpAirPlay;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuzzleClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\GuzzleClient');
    }

    function it_is_an_Apple_TV_client()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\AppleTvClient');
    }
}
