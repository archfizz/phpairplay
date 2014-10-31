<?php

namespace ArchFizz\PhpAirPlay;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Mirrors your desktop to an AppleTV by constantly taking a screenshot.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class MirrorCommand extends Command
{
    const NAME = 'mirror';
    const HOST_ARGUMENT = 'host';

    const AIRPLAY_DEFAULT_PORT = 7000;

    const TRANSISTION_NONE = 'None';
    const TRANSISTION_SLIDE_LEFT = 'SlideLeft';
    const TRANSISTION_SLIDE_RIGHT = 'SlideRight';
    const TRANSISTION_DISSOLVE = 'Dissolve';

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Stream a sequence of desktop screenshots to an Apple TV device.')
            ->addArgument(
                'host',
                InputArgument::REQUIRED,
                'The IP address of the Apple TV'
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getArgument(self::HOST_ARGUMENT);

        $baseUrl = sprintf('http://%s:%d', $host, self::AIRPLAY_DEFAULT_PORT);

        $output->writeln(sprintf("Connecting to %s", $baseUrl));
        $output->writeln('Press Ctrl-c to quit');

        $client = new Client([
            'base_url' => $baseUrl
        ]);

        $mirror = new Mirror(new ProcessBuilder(), 'imagemagick');

        while (true) {
            $mirror->reflect();

            $this->putPhoto($output, $client, file_get_contents($mirror->getImage()));

            $this->getApplication()->getFilesystem()->remove($mirror->getImage());
        }
    }

    /**
     * @param OutputInterface   $output
     * @param Client            $client
     * @param string            $image  The path to the image
     */
    private function putPhoto(OutputInterface $output, Client $client, $image)
    {
        $client->put('/photo', [
            'body' => $image,
            'headers' => [
                'User-Agent' => 'MediaControl/1.0',
                'X-Apple-Transition' => self::TRANSISTION_NONE,
            ]
        ]);
    }
}
