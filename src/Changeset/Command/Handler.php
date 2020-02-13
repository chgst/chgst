<?php

namespace Changeset\Command;

use Changeset\Event\EventInterface;
use Changeset\Event\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;
use Symfony\Component\EventDispatcher\GenericEvent;

class Handler implements HandlerInterface
{
    /** @var RepositoryInterface */
    protected $repository;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /**
     * Handler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param RepositoryInterface|null $repository
     */
    public function __construct(EventDispatcherInterface $dispatcher, RepositoryInterface $repository = null)
    {
        $this->dispatcher = LegacyEventDispatcherProxy::decorate($dispatcher);

        if ($repository) $this->setRepository($repository);
    }

    public function setRepository(RepositoryInterface $repository)
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

        $this->dispatcher->dispatch(new GenericEvent($event), 'changeset.command.handled');

        $this->repository->append($event);

        return $event;
    }
}
