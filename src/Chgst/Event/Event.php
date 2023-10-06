<?php

namespace Chgst\Event;

use Chgst\Common\OnAggregateTrait;
use Chgst\Common\BlameableInterface;
use Chgst\Common\BlameableTrait;
use Chgst\Common\HasPayloadTrait;
use Chgst\Common\TimestampableInterface;
use Chgst\Common\TimestampableTrait;

class Event implements EventInterface, TimestampableInterface, BlameableInterface
{
    use EventTrait, OnAggregateTrait, TimestampableTrait, HasPayloadTrait, BlameableTrait;
}
