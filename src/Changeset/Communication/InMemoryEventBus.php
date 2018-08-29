<?php

namespace Changeset\Communication;

use Changeset\Event\EventInterface;
use Changeset\Event\ListenerInterface;
use Changeset\Event\ProjectorInterface;

class InMemoryEventBus implements EventBusInterface
{
    /** @var ProjectorInterface[] */
    private $projectors = [];

    /** @var ListenerInterface[] */
    private $listeners = [];

    /** @var bool */
    private $listenersEnabled = false;

    public function enableListeners()
    {
        $this->listenersEnabled = true;
    }

    public function disableListeners()
    {
        $this->listenersEnabled = false;
    }

    public function listenersEnabled() : bool
    {
        return $this->listenersEnabled;
    }

    public function addListener(ListenerInterface $listener)
    {
        if ( ! in_array($listener, $this->listeners))
        {
           $this->listeners[] = $listener;
        }
    }

    public function addProjector(ProjectorInterface $projector)
    {
        if ( ! in_array($projector, $this->projectors))
        {
            $this->projectors[] = $projector;
        }
    }

    public function dispatch(EventInterface $event)
    {
        foreach ($this->projectors as $projector)
        {
            if($projector->supports($event))
            {
                $projector->process($event);
            }
        }

        if($this->listenersEnabled)
        {
            foreach ($this->listeners as $listener)
            {
                if($listener->supports($event))
                {
                    $listener->process($event);
                }
            }
        }
    }
}
