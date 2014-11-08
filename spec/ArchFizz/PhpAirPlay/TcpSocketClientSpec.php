<?php

namespace spec\ArchFizz\PhpAirPlay;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class TcpSocketClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\TcpSocketClient');
    }

    function it_is_an_Apple_TV_client()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\AppleTvClient');
    }
}
