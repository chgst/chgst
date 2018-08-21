<?php

namespace spec\Changeset\Communication;

use Changeset\Communication\InMemoryEventBus;
use Changeset\Event\EventInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InMemoryEventBusSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryEventBus::class);
    }

    function it_can_dispatch(EventInterface $event, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->dispatch(Argument::any(), Argument::any())->shouldBeCalled();

        $event->getName()->willReturn('some name');

        $this->dispatch($event);
    }
}
