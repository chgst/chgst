<?php

namespace Chgst\Communication;

use Chgst\Event\EventInterface;
use Chgst\Event\ListenerInterface;
use Chgst\Event\ProjectorInterface;

class InMemoryEventBus implements EventBusInterface
{
    private array $projectors = [];

    private array $listeners = [];

    private bool $listenersEnabled = false;

    public function enableListeners(): void
    {
        $this->listenersEnabled = true;
    }

    public function disableListeners(): void
    {
        $this->listenersEnabled = false;
    }

    public function listenersEnabled() : bool
    {
        return $this->listenersEnabled;
    }

    public function addListener(ListenerInterface $listener, $priority = 0): void
    {
        if ( ! isset($this->listeners[$priority]) || ! is_array($this->listeners[$priority]))
        {
            $this->listeners[$priority] = [];
        }

        if ( ! in_array($listener, $this->listeners[$priority]))
        {
           $this->listeners[$priority][] = $listener;
        }
    }

    public function addProjector(ProjectorInterface $projector, $priority = 0): void
    {
        if ( ! isset($this->projectors[$priority]) || ! is_array($this->projectors[$priority]))
        {
            $this->projectors[$priority] = [];
        }

        if ( ! in_array($projector, $this->projectors[$priority]))
        {
            $this->projectors[$priority][] = $projector;
        }
    }

    public function dispatch(EventInterface $event): void
    {
        krsort($this->projectors);

        foreach ($this->projectors as $priority => $projectors)
        {
            foreach ($projectors as $projector)
            {
                if ($projector->supports($event))
                {
                    $projector->process($event);
                }
            }
        }

        if($this->listenersEnabled)
        {
            krsort($this->listeners);

            foreach ($this->listeners as $priority => $listeners)
            {
                foreach ($listeners as $listener)
                {
                    if($listener->supports($event))
                    {
                        $listener->process($event);
                    }
                }
            }
        }
    }
}
