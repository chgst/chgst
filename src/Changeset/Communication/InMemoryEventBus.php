<?php

namespace Changeset\Communication;

use Changeset\Event\EventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class InMemoryEventBus implements EventBusInterface
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /**
     * InMemoryEventBus constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(EventInterface $event)
    {
        $this->dispatcher->dispatch($event->getName(), new GenericEvent($event));
    }
}
