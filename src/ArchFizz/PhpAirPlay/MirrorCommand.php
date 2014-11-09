<?php

namespace ArchFizz\PhpAirPlay;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    const UTILITY_OPTION = 'utility';

    /**
     * @var Mirror
     */
    private $mirror;

    /**
     * @param null|string $name
     * @param Mirror      $mirror
     */
    public function __construct($name, Mirror $mirror)
    {
        $this->mirror = $mirror;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Stream a sequence of desktop screenshots to an Apple TV device.')
            ->addArgument(
                self::HOST_ARGUMENT,
                InputArgument::REQUIRED,
                'The IP address of the Apple TV'
            )
            ->addOption(
                self::UTILITY_OPTION,
                'u',
                InputOption::VALUE_REQUIRED,
                sprintf(
                    'The utility for capturing the desktop as an image. One of [%s]',
                    implode('|', $this->mirror->getSupportedUtilities())
                )
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

        $baseUrl = sprintf('http://%s:%d', $host, AppleTvClient::AIRPLAY_DEFAULT_PORT);

        $output->writeln(sprintf("Connecting to %s", $baseUrl));
        $output->writeln('Press Ctrl-c to quit');

        $client = new Client([
            'base_url' => $baseUrl
        ]);

        if ($input->hasOption(self::UTILITY_OPTION)) {
            $this->mirror->setUtility($input->getOption(self::UTILITY_OPTION));
        }

        while (true) {
            $this->mirror->reflect();

            $this->putPhoto($output, $client, file_get_contents($this->mirror->getImage()));

            $this->getApplication()->getFilesystem()->remove($this->mirror->getImage());
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
