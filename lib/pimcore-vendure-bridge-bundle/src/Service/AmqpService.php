<?php

namespace PimcoreVendureBridgeBundle\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use OpenDxp\Logger;

class AmqpService
{
    private const MAX_OPENED_CHANNELS = 1000;

    private string $amqpHost;

    private string $amqpPort;

    private string $amqpUser;

    private string $amqpPassword;

    private ?AMQPStreamConnection $amqpStreamConnection = null;

    private ?int $channelId = null;

    /**
     * @param string $amqpHost
     * @param string $amqpPort
     * @param string $amqpUser
     * @param string $amqpPassword
     */
    public function __construct(
        string $amqpHost,
        string $amqpPort,
        string $amqpUser,
        string $amqpPassword,
    ) {
        $this->amqpHost = $amqpHost;
        $this->amqpPort = $amqpPort;
        $this->amqpUser = $amqpUser;
        $this->amqpPassword = $amqpPassword;
    }

    /**
     * @param string $queue
     * @param array $data
     *
     * @throws \Exception
     * @return void
     */
    public function sendToAmqp(string $queue, array $data): void
    {
        if ($this->amqpStreamConnection === null) {
            Logger::notice('[AmqpService] Init AMQP stream connection');
            $this->amqpStreamConnection = new AMQPStreamConnection(
                $this->amqpHost,
                $this->amqpPort,
                $this->amqpUser,
                $this->amqpPassword
            );
        }

        if ($this->channelId === null) {
            $this->channelId = $this->amqpStreamConnection->get_free_channel_id();
        }

        $channel = $this->amqpStreamConnection->channel($this->channelId);
        $channel->queue_declare($queue, false, false, false, false);

        $msg = new AMQPMessage(json_encode($data));
        $channel->basic_publish($msg, '', $queue);
    }
}
