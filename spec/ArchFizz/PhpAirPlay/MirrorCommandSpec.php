<?php

namespace spec\ArchFizz\PhpAirPlay;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MirrorCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\MirrorCommand');
    }

    function it_is_a_Symfony_Command()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }
}
