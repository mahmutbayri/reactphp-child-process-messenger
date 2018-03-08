<?php

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use React\EventLoop\Factory as EventLoopFactory;
use React\EventLoop\Timer\Timer;
use WyriHaximus\React\ChildProcess\Messenger\Factory as MessengerFactory;
use WyriHaximus\React\ChildProcess\Messenger\Messages\Factory as MessagesFactory;
use WyriHaximus\React\ChildProcess\Messenger\Messages\Payload;
use WyriHaximus\React\ChildProcess\Messenger\Messenger;

$options = [
    'lineClass' => 'WyriHaximus\React\ChildProcess\Messenger\Messages\SecureLine',
    'lineOptions' => [
        'key' => 'abc123',
    ],
];

$loop = EventLoopFactory::create();

MessengerFactory::parentFromClass(ExamplesChildProcess::class, $loop, $options)->then(function (Messenger $messenger) use ($loop) {
    $messenger->on('message', function (Payload $payload) {
        echo $payload['time'], PHP_EOL;
    });

    $messenger->on('error', function ($e) {
        echo 'Error: ', var_export($e, true), PHP_EOL;
    });

    $i = 0;
    $loop->addPeriodicTimer(1, function (Timer $timer) use (&$i, $messenger) {
        if ($i >= 13) {
            $timer->cancel();
            $messenger->softTerminate();

            return;
        }

        $messenger->message(MessagesFactory::message([
            'i' => $i,
            'time' => time(),
        ]));

        $i++;
    });
});

$loop->run();
