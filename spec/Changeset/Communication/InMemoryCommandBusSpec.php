<?php

namespace spec\Changeset\Communication;

use Changeset\Command\CommandInterface;
use Changeset\Command\HandlerInterface;
use Changeset\Communication\CommandBusInterface;
use Changeset\Communication\EventBusInterface;
use Changeset\Communication\InMemoryCommandBus;
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

    function it_can_dispatch_commands_to_command_handlers(CommandInterface $command, HandlerInterface $handler)
    {
        $this->addHandler($handler);

        $handler->supports(Argument::any())->willReturn(false);

        $this->dispatch($command);

        $handler->supports(Argument::any())->willReturn(true);
        $handler->process(Argument::any())->shouldBeCalled();

        $this->dispatch($command);
    }
}
