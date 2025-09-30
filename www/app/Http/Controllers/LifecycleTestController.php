<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifecycleTestController extends Controller
{
    public function showServiceContainerTest()
    {
        app()->bind('lifecycleTest', function () {
            return 'ライフサイクルテスト';
        });
        $test = app()->make('lifecycleTest');

        // サービスコンテナなしのパターン
//        $message = new Message();
//        $sample = new Sample($message);
//        $sample->run();

        // サービスコンテナありのパターン
        app()->bind('sample', Sample::class);
        /** @var Sample $sample */
        $sample = app()->make('sample');
        $sample->run();

        dd($test, app());
    }
}

class Sample
{
    public function __construct(
        public Message $message,
    )
    {}

    public function run()
    {
        $this->message->send();
    }
}

class Message
{
    public function send()
    {
        echo('メッセージ表示');
    }
}
