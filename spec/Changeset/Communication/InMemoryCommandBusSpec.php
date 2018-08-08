<?php

namespace spec\Changeset\Communication;

use Changeset\Command\CommandInterface;
use Changeset\Communication\CommandBusInterface;
use Changeset\Communication\InMemoryCommandBus;
use PhpSpec\ObjectBehavior;

class InMemoryCommandBusSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CommandBusInterface::class);
        $this->shouldHaveType(InMemoryCommandBus::class);
    }

    function it_can_dispatch_commands(CommandInterface $command)
    {
        $this->dispatch($command);
    }
}
