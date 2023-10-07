<?php

namespace spec\Chgst\Communication;

use Chgst\Command\CommandInterface;
use Chgst\Command\HandlerInterface;
use Chgst\Communication\CommandBusInterface;
use Chgst\Communication\EventBusInterface;
use Chgst\Communication\InMemoryCommandBus;
use Chgst\Event\EventInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InMemoryCommandBusSpec extends ObjectBehavior
{
    function let(EventBusInterface $eventBus)
    {
        $this->setEventBus($eventBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandBusInterface::class);
        $this->shouldHaveType(InMemoryCommandBus::class);
    }

    function it_can_dispatch_commands_to_command_handler(CommandInterface $command, HandlerInterface $handler, EventInterface $event)
    {
        $this->setHandler($handler);

        $handler->process(Argument::any())->willReturn($event)->shouldBeCalled();

        $this->dispatch($command);
    }
}
