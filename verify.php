<?php
$access_token = 'A7fyuqaArDj1uvQUhcTpVaLm7r8PGppXWbWja/JH8cGC+qupCOHwrqgxQ2Ja6CvKNkPBTdYECMPZ/RswvU9uLhGVryp4nUT0FtK+3ovyafkzxkryhpNoafwRY0RbZKEnI1qCHNI0YAtjr6q0/I9BSwdB04t89/1O/w1cDnyilFU=';


$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
