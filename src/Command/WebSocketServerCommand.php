<?php

namespace App\Command;

use App\Service\ChatManager;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:websocket:start',
    description: 'Starts the WebSocket server',
)]
class WebSocketServerCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'The port to run the WebSocket server on', 8080);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $port = $input->getOption('port');
        $io->success(sprintf('Starting WebSocket server on port %d', $port));

        try {
            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new ChatManager()
                    )
                ),
                $port
            );
            $io->note('WebSocket server running...');
            $io->note('Press Ctrl+C to stop the server');
            // Run the server
            $server->run();
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('Failed to start WebSocket server: %s', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}
