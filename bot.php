<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/constant.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

$discord = new Discord(
    [
        'token' => TOKEN_BOT,
        'intents' => Intents::getDefaultIntents()
    ]
);

$discord->on(
    'ready', 
    function (Discord $discord) {
        echo "Bot is ready!", PHP_EOL;
        $discord->on(
            Event::MESSAGE_CREATE,
            function (Message $message, Discord $discord) {

                try{
                    if ($message->author->bot) {
                        return;
                    }
    
                    $DEVELOPER_KEY = KEY_YOUTUBE;
    
                    $client =  new Google_Client();
                    $client->setDeveloperKey($DEVELOPER_KEY);
    
                    $youtube = new Google_Service_YouTube($client);
                    $searchResponse = $youtube->search->listSearch(
                        'id,snippet', array(
                            'q' => $message->content,
                            'maxResults' => 3,
                        )
                    );

                    if ($message->content == "") {
                        $message->reply("Não foi possível identificar a mensagem");
                    } else {
                        foreach ($searchResponse as $item) {
                            $message->reply(
                                'https://www.youtube.com/watch?v='.$item['id']['videoId']
                            );
                        }
                    }
                    
                    
                } catch(Exception $e){
                    $message->reply('Singão buscaMeme foi Neutralizado!');
                }

            }
        );
    }
);

$discord->run();