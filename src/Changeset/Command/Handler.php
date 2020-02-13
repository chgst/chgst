<?php

namespace Changeset\Command;

use Changeset\Event\EventInterface;
use Changeset\Event\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;
use Symfony\Component\EventDispatcher\GenericEvent;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;

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
        $this->dispatcher = class_exists(LegacyEventDispatcherProxy::class)
            ? LegacyEventDispatcherProxy::decorate($dispatcher)
            : $dispatcher
        ;

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

        if($this->dispatcher instanceof ContractsEventDispatcherInterface)
        {
            $this->dispatcher->dispatch(new GenericEvent($event), 'changeset.command.handled');
        }
        else
        {
            $this->dispatcher->dispatch('changeset.command.handled', new GenericEvent($event));
        }

        $this->repository->append($event);

        return $event;
    }
}
