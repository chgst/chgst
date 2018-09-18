<?php

namespace spec\Changeset\Communication;

use Changeset\Communication\InMemoryEventBus;
use Changeset\Event\EventInterface;
use Changeset\Event\ListenerInterface;
use Changeset\Event\ProjectorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InMemoryEventBusSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryEventBus::class);
    }

    function it_can_have_projectors_and_listeners(ProjectorInterface $projector, ListenerInterface $listener)
    {
        $this->addProjector($projector);
        $this->addListener($listener);
    }

    function it_you_can_toggle_execution_of_listeners()
    {
        $this->listenersEnabled()->shouldReturn(false);
        $this->enableListeners();
        $this->listenersEnabled()->shouldReturn(true);
        $this->disableListeners();
        $this->listenersEnabled()->shouldReturn(false);
    }

    function it_can_dispatch_event_to_projectors_and_listeners(EventInterface $event, ProjectorInterface $projector, ListenerInterface $listener)
    {
        $this->addProjector($projector);
        $this->addListener($listener);

        $projector->supports(Argument::any())->willReturn(true);
        $projector->process($event)->shouldBeCalled();

        $this->dispatch($event);

        $this->enableListeners();

        $listener->supports(Argument::any())->willReturn(true);
        $listener->process($event)->shouldBeCalled();

        $this->dispatch($event);
    }
}
