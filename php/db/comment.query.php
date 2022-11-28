<?php 
namespace db;

use db\DataSource;
use model\CommentModel;

class CommentQuery {
    
    public static function fetchByTopicId($topic) {

        if(!$topic->isValidId()) {
            return false;
        }
        
        $db = new DataSource;
        $sql = '
        select 
            c.*, u.nickname 
        from comments c
        inner join users u 
            on c.user_id = u.id 
        where c.topic_id = :id
            and c.body != ""
            and c.del_flg != 1
            and u.del_flg != 1
        order by c.id desc
        ';

        $result = $db->select($sql, [
            ':id' => $topic->id
        ], DataSource::CLS, CommentModel::class);

        return $result;

    }


    public static function insert($comment) {
        if (!($comment->isValidTopicId()
        * $comment->isValidBody()
        * $comment->isValidAgree())) {
        return false;
        }
        $db = new DataSource;
        $sql = 'insert into comments(topic_id, agree, body, user_id) values (:topic_id, :agree, :body, :user_id)';

        return $db->execute($sql, [
            ':topic_id' => $comment->topic_id,
            ':agree' => $comment->agree,
            ':body' => $comment->body,
            ':user_id' => $comment->user_id,
        ]);
    }
 }