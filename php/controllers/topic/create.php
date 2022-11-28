<?php 
namespace controller\topic\create;

use db\TopicQuery;
use model\TopicModel;
use lib\Auth;
use model\UserModel;
use Throwable;
use lib\Msg;
function get(){
    Auth::requireLogin();

    $topic = TopicModel::getSessionAndFlush();

    if(empty($topic)){

    $topic = new TopicModel;
    $topic-> id = -1;
    $topic->title = '';
    $topic-> published = 1;
    }
    $fecthedTopic = TopicQuery::fetchById($topic);
    \view\topic\edit\index($topic,true);
}
function post(){
    Auth::requireLogin();

    $topic=new TopicModel;
    $topic->id = get_param('topic_id', null);
    $topic->title = get_param('title', null);
    $topic->published = get_param('published', null);

    $user = UserModel::getSession();

    try{
        $is_success = TopicQuery::insert($topic,$user);
        
    }catch(Throwable $e){
        Msg::push(Msg::DEBUG,$e->getMessage());
        $is_success = false;
    }
    if($is_success){
        Msg::push(Msg::INFO,'トピックの登録に成功しました');
        redirect('topic/archive');
    }else{

        Msg::push(Msg::ERROR,'トピックの登録に失敗しました');
        TopicModel::setSession($topic);
        redirect(GO_REFERER);
    }

}
?>