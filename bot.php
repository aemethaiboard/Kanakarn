<?php
// �óյ�ͧ��õ�Ǩ�ͺ����� error ����Դ 3 ��÷Ѵ��ҧ������ӧҹ �ó���� ��� comment �Դ�
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// include composer autoload
require_once '../vendor/autoload.php';
 
// ��õ������ǡѺ bot
require_once 'bot_settings.php';
 
// �ó��ա���������͡Ѻ�ҹ������
//require_once("dbconnect.php");
 
///////////// ��ǹ�ͧ������¡��ҹ class ��ҹ namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
 
 
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
 
// ��������Ѻ����觤���Ңͧ LINE Messaging API
$content = file_get_contents('php://input');
 
// ��˹���� signature ����Ѻ��Ǩ�ͺ�����ŷ����������繢����Ũҡ LINE
$hash = hash_hmac('sha256', $content, LINE_MESSAGE_CHANNEL_SECRET, true);
$signature = base64_encode($hash);
 
// �ŧ��Ң����ŷ�����Ѻ�ҡ LINE �� array �ͧ Event Object
$events = $bot->parseEventRequest($content, $signature);
$eventObj = $events[0]; // Event Object �ͧ array �á
 
// �֧��һ������ͧ Event �����㹵���� �շ����� 7 event
$eventType = $eventObj->getType();
 
// ���ҧ����� ����� sourceId �ͧ���л�����
$userId = NULL;
$groupId = NULL;
$roomId = NULL;
// ���ҧ����� replyToken ����Ѻ�ó���ͺ��Ѻ��ͤ���
$replyToken = NULL;
// ���ҧ����� ����纤������� Event �������˹
$eventMessage = NULL;
$eventPostback = NULL;
$eventJoin = NULL;
$eventLeave = NULL;
$eventFollow = NULL;
$eventUnfollow = NULL;
$eventBeacon = NULL;
// ���͹䢡�á�˹������� Event 
switch($eventType){
    case 'message': $eventMessage = true; break;    
    case 'postback': $eventPostback = true; break;  
    case 'join': $eventJoin = true; break;  
    case 'leave': $eventLeave = true; break;    
    case 'follow': $eventFollow = true; break;  
    case 'unfollow': $eventUnfollow = true; break;  
    case 'beacon': $eventBeacon = true; break;                          
}
// ���ҧ������纤�� groupId �ó��� Event ����Դ���� GROUP
if($eventObj->isGroupEvent()){
    $groupId = $eventObj->getGroupId();  
}
// ���ҧ������纤�� roomId �ó��� Event ����Դ���� ROOM
if($eventObj->isRoomEvent()){
    $roomId = $eventObj->getRoomId();            
}
// �֧��� replyToken �������ҹ �ء� Event �������� Leave ��� Unfollow Event
if(is_null($eventLeave) && is_null($eventUnfollow)){
    $replyToken = $eventObj->getReplyToken();    
}
// �֧��� userId �������ҹ �ء� Event �������� Leave Event
if(is_null($eventLeave)){
    $userId = $eventObj->getUserId();
}
// ��Ǩ�ͺ����� Join Event ��� bot �觢�ͤ���� GROUP ���������� GROUP ����
if(!is_null($eventJoin)){
    $textReplyMessage = "����ҡ�������¹�� GROUP ID:: ".$groupId;
    $replyData = new TextMessageBuilder($textReplyMessage);                 
}
// ��Ǩ�ͺ����� Leave Event ����� bot �͡�ҡ�����
if(!is_null($eventLeave)){
     
}
// ��Ǩ�ͺ����� Message Event ��С�˹���ҵ���õ�ҧ�
if(!is_null($eventMessage)){
    // ���ҧ�������¤�һ������ͧ Message �ҡ������ 8 ������
    $typeMessage = $eventObj->getMessageType();  
    //  text | image | sticker | location | audio | video | imagemap | template 
    // ����繢�ͤ���
    if($typeMessage=='text'){
        $userMessage = $eventObj->getText(); // �纤�Ң�ͤ��������������
    }
    // ����� sticker
    if($typeMessage=='sticker'){
        $packageId = $eventObj->getPackageId();
        $stickerId = $eventObj->getStickerId();
    }
    // ����� location
    if($typeMessage=='location'){
        $locationTitle = $eventObj->getTitle();
        $locationAddress = $eventObj->getAddress();
        $locationLatitude = $eventObj->getLatitude();
        $locationLongitude = $eventObj->getLongitude();
    }       
    // �纤�� id �ͧ��ͤ���
    $idMessage = $eventObj->getMessageId();  
}
 
// ��ǹ�ͧ��÷ӧҹ
if(!is_null($events)){
    // ����� Postback Event
    if(!is_null($eventPostback)){
        $dataPostback = NULL;
        $paramPostback = NULL;
        // �ŧ�����Ũҡ Postback Data �� array
        parse_str($eventObj->getPostbackData(),$dataPostback);
        // �֧��� params �ó��դ�� params
        $paramPostback = $eventObj->getPostbackParams();
        // ���ͺ�ʴ���ͤ�������Դ�ҡ Postaback Event
        $textReplyMessage = "��ͤ����ҡ Postback Event Data = ";        
        $textReplyMessage.= json_encode($dataPostback);
        $textReplyMessage.= json_encode($paramPostback);
        $replyData = new TextMessageBuilder($textReplyMessage);     
    }
    // ����� Message Event 
    if(!is_null($eventMessage)){
        switch ($typeMessage){ // ��˹����͹䢡�÷ӧҹ�ҡ �������ͧ message
            case 'text':  // ����繢�ͤ���
                $userMessage = strtolower($userMessage); // �ŧ�繵����� ����Ѻ���ͺ
                switch ($userMessage) {
                    case "t_b":
                        // ��˹� action 4 ���� 4 ������
                        $actionBuilder = array(
                            new MessageTemplateActionBuilder(
                                'Message Template',// ��ͤ����ʴ�㹻���
                                'This is Text' // ��ͤ��������ʴ���觼���� ����ͤ�ԡ���͡
                            ),
                            new UriTemplateActionBuilder(
                                'Uri Template', // ��ͤ����ʴ�㹻���
                                'https://www.ninenik.com'
                            ),
                            new DatetimePickerTemplateActionBuilder(
                                'Datetime Picker', // ��ͤ����ʴ�㹻���
                                http_build_query(array(
                                    'action'=>'reservation',
                                    'person'=>5
                                )), // �����ŷ������� webhook ��ҹ postback event
                                'datetime', // date | time | datetime �ٻẺ�����ŷ����� 㹷������ datatime
                                substr_replace(date("Y-m-d H:i"),'T',10,1), // �ѹ��� ���� ���������鹷��١���͡
                                substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //�ѹ��� ���� �ҡ�ش������͡��
                                substr_replace(date("Y-m-d H:i"),'T',10,1) //�ѹ��� ���� �����ش������͡��
                            ),      
                            new PostbackTemplateActionBuilder(
                                'Postback', // ��ͤ����ʴ�㹻���
                                http_build_query(array(
                                    'action'=>'buy',
                                    'item'=>100
                                )) // �����ŷ������� webhook ��ҹ postback event
    //                          'Postback Text'  // ��ͤ��������ʴ���觼���� ����ͤ�ԡ���͡
                            ),      
                        );
                        $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    'button template builder', // ��˹��������ͧ
                                    'Please select', // ��˹���������´
                                    $imageUrl, // ��˹� url �ػ�Ҿ
                                    $actionBuilder  // ��˹� action object
                            )
                        );              
                        break;                                          
                    case "p":
                            if(!is_null($groupId) || !is_null($roomId)){
                                if($eventObj->isGroupEvent()){
                                    $response = $bot->getGroupMemberProfile($groupId, $userId);
                                }
                                if($eventObj->isRoomEvent()){
                                    $response = $bot->getRoomMemberProfile($roomId, $userId);    
                                }
                            }else{
                                $response = $bot->getProfile($userId);
                            }
                            if ($response->isSucceeded()) {
                                $userData = $response->getJSONDecodedBody(); // return array     
                                // $userData['userId']
                                // $userData['displayName']
                                // $userData['pictureUrl']
                                // $userData['statusMessage']
                                $textReplyMessage = '���ʴդ�Ѻ �س '.$userData['displayName'];     
                            }else{
                                $textReplyMessage = '���ʴդ�Ѻ �س�����';
                            }
                            $replyData = new TextMessageBuilder($textReplyMessage);                                                 
                        break;                          
                    case "l": // ���͹䢷��ͺ������þ���� L � GROUP / ROOM ������� bot �͡�ҡ GROUP / ROOM
                            $sourceId = $eventObj->getEventSourceId();
                            if($eventObj->isGroupEvent()){
                                $bot->leaveGroup($sourceId);
                            }
                            if($eventObj->isRoomEvent()){
                                $bot->leaveRoom($sourceId);  
                            }                                               
                            $textReplyMessage = '�ԭ bot �͡�ҡ Group / Room'; 
                            $replyData = new TextMessageBuilder($textReplyMessage);                                                 
                        break;                                                                                                                                                                                                                                                                      
                    default:
                        $textReplyMessage = " �س��������� ��� �������˹�";
                        $replyData = new TextMessageBuilder($textReplyMessage);         
                        break;                                      
                }
                break;                                                  
            default:
                // �óշ��ͺ���͹����� �������������繢�ͤ���
                $textReplyMessage = '���ʴդ�Ѻ �س '.$typeMessage;         
                $replyData = new TextMessageBuilder($textReplyMessage);         
                break;  
        }
    }
}
$response = $bot->replyMessage($replyToken,$replyData);
if ($response->isSucceeded()) {
    echo 'Succeeded!';
    return;
}
// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
?>