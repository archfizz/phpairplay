<?php

namespace spec\ArchFizz\PhpAirPlay;

use ArchFizz\PhpAirPlay\Mirror;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MirrorCommandSpec extends ObjectBehavior
{
    function let(Mirror $mirror)
    {
        $mirror->getSupportedUtilities()->willReturn([]);

        $this->beConstructedWith('mirror', $mirror);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\MirrorCommand');
    }

    function it_is_a_Symfony_Command()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }
}
