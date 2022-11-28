<?php 
namespace controller\home;

use db\TopicQuery;

function get() {

    $topics = TopicQuery::fetchPublishedTopics();
    \view\home\index($topics);
    
}
