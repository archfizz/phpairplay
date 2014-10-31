<?php

namespace spec\ArchFizz\PhpAirPlay;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class MirrorSpec extends ObjectBehavior
{
    function let(ProcessBuilder $processBuilder)
    {
        $utility = 'imagemagick';
        $this->beConstructedWith($processBuilder, $utility, '/tmp/airplay.jpg');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ArchFizz\PhpAirPlay\Mirror');
    }

    function it_mirrors_the_desktop_using_ImageMagick(ProcessBuilder $processBuilder, Process $process)
    {
        $processBuilder->setPrefix('import')->shouldBeCalled();
        $processBuilder->setArguments(['-window', 'root', '/tmp/airplay.jpg'])->shouldBeCalled();

        $processBuilder->getProcess()->willReturn($process);

        $process->run()->shouldBeCalled();

        $this->reflect();
    }

    function it_exposes_the_path_to_the_mirrored_image()
    {
        $this->getImage()->shouldReturn('/tmp/airplay.jpg');
    }
}
