<?php

namespace ArchFizz\PhpAirPlay;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @todo This command is still proof of concept and requires more work.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class PlayVideoCommand extends Command
{
    const NAME = 'play:video';

    const HOST_ARGUMENT = 'host';

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

        $baseUrl = sprintf('http://%s:%d', $host, AppleTvClient::AIRPLAY_DEFAULT_PORT);

        $output->writeln(sprintf("Connecting to %s", $baseUrl));
        $output->writeln('Press Ctrl-c to quit');

        $client = new Client([
            'base_url' => $baseUrl
        ]);

        $this->postVideo($output, $client, 'http://192.168.64.135/equinox.mp4');
    }

    /**
     * @param OutputInterface   $output
     * @param Client            $client
     * @param string            $image  The path to the image
     */
    public function postVideo(OutputInterface $output, Client $client, $video)
    {
        $this->tcpConnection();
        return;

        $datetime = substr((new \DateTime())->format(\DateTime::ISO8601), 0, -5);

        $videoBody = $this->createVideoBody($video);

        $headers = [
            'User-Agent' => 'MediaControl/1.0',
            'Content-Type' => 'application/x-apple-binary-plist',
            'X-Transmit-Date' => $datetime,
            'Connection' =>  'Keep-Alive',
            'Content-Length' => strlen($videoBody),
        ];

        $response = $client->post('/play', [
            'body' => $videoBody,
            'headers' => $headers,
            'future' => true,
        ]);

//        $resource = curl_init($client->getBaseUrl() . '/play');
//
//        curl_setopt($resource, CURLOPT_HTTPHEADER, array_map(function ($header) use ($headers) {
//            return $headers[$header] . ': ' . $header;
//        }, array_keys($headers)));
//
//        $result = curl_exec($resource);
//
//        if ($result) {
//            curl_close($resource);
//        }

//        return;

        $response
            ->then(
                function (Response $response) {
                    // This is called when the request succeeded
                    echo 'Success: ' . $response->getStatusCode();
                    // Returning a value will forward the value to the next promise
                    // in the chain.
                    return $response;
                },
                function ($error) {
                    // This is called when the exception failed.
                    echo 'Exception: ' . $error->getMessage();
                    // Throwing will "forward" the exception to the next promise
                    // in the chain.
                    throw $error;
                }
            )
            ->then(
                function(Response $response) {
                    echo $response->getBody();

                    // This is called after the first promise in the chain. It
                    // receives the value returned from the first promise.
                    echo '->'.$response->getReasonPhrase();
                },
                function ($error) {
                    // This is called if the first promise error handler in the
                    // chain rethrows the exception.
                    echo 'Error: ' . $error->getMessage();
                }
            );
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    private function createVideoBody($uri)
    {
        $video = new Video($uri);

        return (string) $video;
    }

    private function tcpConnection()
    {
        /* Get the port for the WWW service. */
        $service_port = 7000;

        /* Get the IP address for the target host. */
        $address = '192.168.64.65';

        /* Create a TCP/IP socket. */
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        } else {
            echo "OK.\n";
        }

        echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result === false) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        } else {
            echo "OK.\n";
        }

        $datetime = substr((new \DateTime())->format(\DateTime::ISO8601), 0, -5);

        $video = 'http://192.168.64.135/equinox.mp4';

        $videoBody = $this->createVideoBody($video);


        $headers = [
            'User-Agent' => 'MediaControl/1.0',
            'Content-Type' => 'application/x-apple-binary-plist',
            'X-Transmit-Date' => $datetime,
            'Content-Length' => strlen($videoBody),
        ];

        $httpHeaderStrings = implode("\r\n", array_map(function ($header) use ($headers) {
            return $header . ': ' . $headers[$header];
        }, array_keys($headers)));


        $in = "POST /play HTTP/1.1\r\n";
        $in .= "Host: $address\r\n";
        $in .= $httpHeaderStrings . "\r\n\r\n";
        $in .= $videoBody;



        $out = '';

        echo "Sending HTTP POST request...";

        socket_write($socket, $in, strlen($in));
        echo "OK.\n";

        echo $in;

        echo "Reading response:\n\n";
        while (true) {
            $out = socket_read($socket, 2048, PHP_NORMAL_READ);
            echo $out;
        }

        echo "Closing socket...";
        socket_close($socket);
        echo "OK.\n\n";
    }
}
