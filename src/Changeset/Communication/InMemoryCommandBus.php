<?php

namespace Changeset\Communication;

use Changeset\Command\CommandInterface;
use Changeset\Command\HandlerInterface;

class InMemoryCommandBus implements CommandBusInterface
{
    /** @var EventBusInterface */
    private $eventBus;

    /** @var HandlerInterface */
    private $handler;

    public function setHandler(HandlerInterface $handler)
    {
        $this->handler = $handler;

        return $this;
    }

    public function setEventBus(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;

        return $this;
    }

    public function dispatch(CommandInterface $command)
    {
        return $this->eventBus->dispatch($this->handler->process($command));
    }
}
