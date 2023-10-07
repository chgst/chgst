<?php

namespace Chgst\Communication;

use Chgst\Command\CommandInterface;
use Chgst\Command\HandlerInterface;

class InMemoryCommandBus implements CommandBusInterface
{
    private EventBusInterface $eventBus;

    private HandlerInterface $handler;

    public function setHandler(HandlerInterface $handler): static
    {
        $this->handler = $handler;

        return $this;
    }

    public function setEventBus(EventBusInterface $eventBus): static
    {
        $this->eventBus = $eventBus;

        return $this;
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->eventBus->dispatch($this->handler->process($command));
    }
}
