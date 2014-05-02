<?php

namespace ArchFizz\PhpAirPlay;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use GuzzleHttp\Client;

/**
 * @author Adam Elsodaney <aelso1@gmail.com>
 */
class StreamCommand extends Command
{
    const NAME = 'stream:to';
    const HOST_ARGUMENT = 'host';

    const AIRPLAY_DEFAULT_PORT = 7000;
    const TEMP_PATH_TO_SCREENSHOT = '/tmp/airplay.jpg';

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
     * @param InputInterface    $input
     * @param OutputInterface   $output
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

        while (true) {
            // screencapture -m -x -C -t jpg /tmp/airplay.jpg

            $process = new Process(sprintf('shutter -f -o %s -e', self::TEMP_PATH_TO_SCREENSHOT));

            $process->run(function ($type, $buffer) use ($output) {
                if (!Process::ERR === $type) {
                    $output->writeln('OUT > '.$buffer);
                }
            });

            $this->putPhoto($output, $client, file_get_contents(self::TEMP_PATH_TO_SCREENSHOT));

            $this->getApplication()->getFilesystem()->remove(self::TEMP_PATH_TO_SCREENSHOT);
        }
    }

    /**
     * @param OutputInterface   $output
     * @param Client            $client
     * @param string            $image  The path to the image
     *
     * @return \GuzzleHttp\Message\Response
     */
    private function putPhoto(OutputInterface $output, Client $client, $image)
    {
        return $client->put('/photo', [
            'body' => $image,
            'headers' => [
                'User-Agent' => 'MediaControl/1.0',
                'X-Apple-Transition' => self::TRANSISTION_NONE,
            ]
        ]);
    }

    /**
     * @todo
     *
     * Currently not functional.
     *
     * @param OutputInterface   $output
     * @param Client            $client
     * @param string            $image  The path to the image
     *
     * @return \GuzzleHttp\Message\Response
     */
    private function putVideo(OutputInterface $output, Client $client, $video)
    {
        return $client->post('/play', [
            'body' => $this->createVideoBody($video),
            'headers' => [
                'User-Agent' => 'MediaControl/1.0',
                'Content-Type' => 'application/x-apple-binary-plist',
            ]
        ]);
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    private function createVideoBody($uri)
    {
        $plist = <<<PLIST
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN"
 "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
 <dict>
  <key>Content-Location</key>
  <string>%s</string>
  <key>Start-Position</key>
  <real>0</real>
 </dict>
</plist>
PLIST;

        return sprintf($plist, $uri);
    }
}
