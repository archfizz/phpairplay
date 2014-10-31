<?php

namespace spec\ArchFizz\PhpAirPlay;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Filesystem\Filesystem;

class ApplicationSpec extends ObjectBehavior
{
    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\Application');
    }

    function it_is_a_Symfony_Console_Application()
    {
        $this->shouldHaveType('Symfony\Component\Console\Application');
    }
}
