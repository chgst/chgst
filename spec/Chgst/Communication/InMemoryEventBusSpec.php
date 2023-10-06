<?php

namespace spec\Chgst\Communication;

use Chgst\Communication\InMemoryEventBus;
use Chgst\Event\EventInterface;
use Chgst\Event\ListenerInterface;
use Chgst\Event\ProjectorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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

    function it_can_dispatch_event_to_projectors_and_listeners(
        EventInterface $event,
        ProjectorInterface $projector,
        ProjectorInterface $projector2,
        ProjectorInterface $projector3,
        ListenerInterface $listener
    )
    {
        $this->addProjector($projector2, -32);
        $this->addProjector($projector);
        $this->addProjector($projector3, 45);
        $this->addListener($listener);

        $projector->supports(Argument::any())->willReturn(true);
        $projector2->supports(Argument::any())->willReturn(true);
        $projector3->supports(Argument::any())->willReturn(true);

        $projector->process($event)->shouldBeCalled();
        $projector2->process($event)->shouldBeCalled();
        $projector3->process($event)->shouldBeCalled();

        $this->dispatch($event);

        $this->enableListeners();

        $listener->supports(Argument::any())->willReturn(true);
        $listener->process($event)->shouldBeCalled();

        $this->dispatch($event);
    }
}
