<?php

namespace spec\Chgst\Event;

use Chgst\Event\Event;
use Chgst\Event\EventInterface;
use PhpSpec\ObjectBehavior;

class EventSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Event::class);
        $this->shouldHaveType(EventInterface::class);
    }
}
