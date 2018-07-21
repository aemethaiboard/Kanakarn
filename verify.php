<?php
$access_token = 'iFGXK2ZblmbslB+r/NhqXriLHWTxsPT1PxCYUgmn4+J9xoF/amkR632zfLXngtEmNkPBTdYECMPZ/RswvU9uLhGVryp4nUT0FtK+3ovyafkEETcxy7AxG+HVu3AwUgQoVffperEeuqQmOkn4n9YCVQdB04t89/1O/w1cDnyilFU=';


$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
