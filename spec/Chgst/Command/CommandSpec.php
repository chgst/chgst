<?php

namespace spec\Chgst\Command;

use Chgst\Command\Command;
use Chgst\Command\CommandInterface;
use PhpSpec\ObjectBehavior;

class CommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith($name = 'do_something', $aggregateType = \stdClass::class, $aggregateId = 'some id', $payload = []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Command::class);
        $this->shouldHaveType(CommandInterface::class);
    }
}
