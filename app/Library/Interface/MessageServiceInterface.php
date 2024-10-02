<?php
namespace App\Library\Interface;

interface MessageServiceInterface{
    public static function MessageOfMyDevice($userId);
    public static function MessageSharedToMe($userId);
    public static function AllMessages($userId);
    public static function MessagesCount($userId);
    public static function AllMessagesNicknameList($userId);
    public static function pinMessage($userId, $message_id, $true_false);
    public static function deleteMessage($userId, $message_id);
}