<?php

namespace WyriHaximus\React\ChildProcess\Messenger;

use React\EventLoop\LoopInterface;

interface ChildInterface
{
    /**
     * @param Messenger $messenger
     * @param LoopInterface $loop
     * @return void
     */
    public static function create(Messenger $messenger, LoopInterface $loop);
}
