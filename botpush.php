<?php



require "vendor/autoload.php";

$access_token = 'iFGXK2ZblmbslB+r/NhqXriLHWTxsPT1PxCYUgmn4+J9xoF/amkR632zfLXngtEmNkPBTdYECMPZ/RswvU9uLhGVryp4nUT0FtK+3ovyafkEETcxy7AxG+HVu3AwUgQoVffperEeuqQmOkn4n9YCVQdB04t89/1O/w1cDnyilFU=';

$channelSecret = '9dcea8f6ee7c47ab9523ef00474ca0ee';

$pushID = 'U9d4db29b76910af5a6679e56a3a96172';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();







