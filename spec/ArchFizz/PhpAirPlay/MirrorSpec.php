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

        $process->isSuccessful()->willReturn(true);

        $this->reflect();
    }

    function it_throws_a_Runtime_Exception_when_a_mirror_image_could_not_be_generated(ProcessBuilder $processBuilder, Process $process)
    {
        $processBuilder->setPrefix(Argument::any())->shouldBeCalled();
        $processBuilder->setArguments(Argument::any())->shouldBeCalled();

        $processBuilder->getProcess()->willReturn($process);
        $process->run()->shouldBeCalled();

        $process->isSuccessful()->willReturn(false);
        $process->getErrorOutput()->shouldBeCalled();

        $this->shouldThrow('Symfony\Component\Process\Exception\RuntimeException')->duringReflect();
    }

    function it_exposes_the_path_to_the_mirrored_image()
    {
        $this->getImage()->shouldReturn('/tmp/airplay.jpg');
    }

    function it_exposes_the_supported_utilities()
    {
        $this->getSupportedUtilities()->shouldBeLike([
            'imagemagick',
            'shutter',
            'gnome',
            'osx',
        ]);
    }
}
