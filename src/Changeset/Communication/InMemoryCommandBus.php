<?php

namespace Changeset\Communication;

use Changeset\Command\CommandInterface;
use Changeset\Command\HandlerInterface;

class InMemoryCommandBus implements CommandBusInterface
{
    /** @var EventBusInterface */
    private $eventBus;

    /** @var HandlerInterface[] */
    private $handlers = [];

    public function addHandler(HandlerInterface $handler)
    {
        if ( ! in_array($handler, $this->handlers))
        {
            $this->handlers[] = $handler;
        }
    }

    public function setEventBus(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;

        return $this;
    }

    public function dispatch(CommandInterface $command)
    {
        foreach($this->handlers as $handler)
        {
            if($handler->supports($command))
            {
                return $this->eventBus->dispatch($handler->process($command));
            }
        }
    }
}
