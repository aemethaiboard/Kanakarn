<?php



require "vendor/autoload.php";

$access_token = 'A7fyuqaArDj1uvQUhcTpVaLm7r8PGppXWbWja/JH8cGC+qupCOHwrqgxQ2Ja6CvKNkPBTdYECMPZ/RswvU9uLhGVryp4nUT0FtK+3ovyafkzxkryhpNoafwRY0RbZKEnI1qCHNI0YAtjr6q0/I9BSwdB04t89/1O/w1cDnyilFU=';

$channelSecret = '1ff4cb7f8474791abfb5381a833a2973';

$pushID = 'Cf6873f721004b548268efa4c3497c268';
//$pushID = '1cb155bc-e8bf-47e1-8bbb-3b49e4ffc7ad';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();







