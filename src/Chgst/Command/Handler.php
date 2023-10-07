<?php

namespace Chgst\Command;

use Chgst\Event\EventInterface;
use Chgst\Event\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class Handler implements HandlerInterface
{
    protected RepositoryInterface $repository;

    protected EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, RepositoryInterface $repository = null)
    {
        $this->dispatcher = $dispatcher;

        if ($repository) $this->setRepository($repository);
    }

    public function setRepository(RepositoryInterface $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function process(CommandInterface $command): EventInterface
    {
        $event = $this->repository->create();

        $event->setAggregateType($command->getAggregateType());
        $event->setAggregateId($command->getAggregateId());
        $event->setPayload($command->getPayload());

        $event->setName(sprintf('%s_completed', $command->getName()));

        $this->dispatcher->dispatch(new GenericEvent($event), 'chgst.command.handled');

        $this->repository->append($event);

        return $event;
    }
}
