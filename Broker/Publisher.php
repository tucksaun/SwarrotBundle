<?php

namespace Broker;

class Publisher
{
    protected $factory;
    protected $messageTypes;

    public function __construct(FactoryInterface $factory, array $messageTypes = array())
    {
        $this->factory      = $factory;
        $this->messageTypes = $messageTypes;
    }

    /**
     * publish
     *
     * @param string $messageType
     * @param Message $message
     *
     * @return void
     */
    public function publish($messageType, Message $message)
    {
        if (!isset($this->messageTypes[$messageType])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown message type "%s". Available are [].',
                $messageType,
                implode(array_keys($this->messageTypes))
            ));
        }

        $config = $this->messageTypes[$messageType];
        $messagePublisher = $this->factory->getMessagePublisher($config['exchange'], $config['conenction']);

        $messagePublisher->publish($message, $config['routing_key']);
    }
}