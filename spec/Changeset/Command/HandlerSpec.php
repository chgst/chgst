<?php

namespace spec\Changeset\Command;

use Changeset\Command\CommandInterface;
use Changeset\Command\Handler;
use Changeset\Command\HandlerInterface;
use Changeset\Event\EventInterface;
use Changeset\Event\RepositoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $dispatcher, RepositoryInterface $repository)
    {
        $this->beConstructedWith($dispatcher, $repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Handler::class);
        $this->shouldHaveType(HandlerInterface::class);
    }

    function it_can_handle_any_command(CommandInterface $command, EventInterface $event, EventDispatcherInterface $dispatcher, RepositoryInterface $repository)
    {
        $repository->create()->willReturn($event);
        $repository->append($event)->shouldBeCalled();

        $command->getName()->willReturn('do_somehting');
        $command->getAggregateType()->willReturn(\stdClass::class);
        $command->getAggregateId()->willReturn('some id');
        $command->getPayload()->willReturn('some payload');

        $dispatcher->dispatch(Argument::any(), Argument::any())->shouldBeCalled();

        $this->process($command)->shouldReturn($event);
    }
}
