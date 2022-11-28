<?php 
namespace controller\topic\archive;

use lib\Auth;
use lib\Msg;
use db\TopicQuery;
use model\UserModel;

function get() {

    Auth::requireLogin();

    $user = UserModel::getSession();

    $topics = TopicQuery::fetchByUserId($user);

    if($topics === false) {
        Msg::push(Msg::ERROR, 'ログインしてください。');
        redirect('login');
    }

    if(count($topics) > 0) {
        \view\topic\archive\index($topics);
    } else {
        echo '<div class="alert alert-primary">トピックを投稿してみよう。</div>';
    }
    
}