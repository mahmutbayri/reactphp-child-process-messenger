<?php

namespace WyriHaximus\React\Tests\ChildProcess\Messenger;

use React\EventLoop\LoopInterface;
use WyriHaximus\React\ChildProcess\Messenger\ChildInterface;
use WyriHaximus\React\ChildProcess\Messenger\Messages\Payload;
use WyriHaximus\React\ChildProcess\Messenger\Messenger;

class ReturnChild implements ChildInterface
{
    /**
     * @var bool
     */
    protected $ran = false;

    public static function create(Messenger $messenger, LoopInterface $loop)
    {
        new static($messenger, $loop);
    }

    protected function __construct(Messenger $messenger, LoopInterface $loop)
    {
        $messenger->registerRpc('return', function (Payload $payload) {
            return \React\Promise\resolve($payload->getPayload());
        });
        $this->ran = true;
    }

    public function getRan()
    {
        return $this->ran;
    }
}
