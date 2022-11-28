<?php 
namespace db;

use db\DataSource;
use model\TopicModel;
use SoapFault;

class TopicQuery {
    public static function fetchByUserId($user) {

        if(!$user->isValidId()) {
            return false;
        }

        $db = new DataSource;
        $sql = 'select * from topics where user_id = :id and del_flg != 1 order by id desc;';

        $result = $db->select($sql, [
            ':id' => $user->id
        ], DataSource::CLS, TopicModel::class);

        return $result;

    }


    public static function fetchPublishedTopics() {

        $db = new DataSource;
        $sql = '
        select 
            t.*, u.nickname 
        from topics t 
        inner join users u 
            on t.user_id = u.id 
        where t.del_flg != 1
            and u.del_flg != 1
            and t.published = 1
        order by t.id desc
        ';

        $result = $db->select($sql, [], DataSource::CLS, TopicModel::class);

        return $result;

    }

    public static function fetchById($topic) {

        if(!$topic->isValidId()) {
            return false;
        }
        
        $db = new DataSource;
        $sql = '
        select 
            t.*, u.nickname 
        from topics t 
        inner join users u 
            on t.user_id = u.id 
        where t.id = :id
            and t.del_flg != 1
            and u.del_flg != 1
        order by t.id desc
        ';

        $result = $db->selectOne($sql, [
            ':id' => $topic->id
        ], DataSource::CLS, TopicModel::class);

        return $result;

    }

    public static function incrementViewCount($topic){
        if(!$topic->isValidId()){
            return false;
        }
        $db = new DataSource;
        $sql = 'update topics set views = views +1 where id = :id;';

        return $db->execute($sql,[':id'=>$topic->id]);

    }
    public static function isUserOwnTopic($topic_id,$user) {

        if (!(TopicModel::validateId($topic_id) && $user->isValidId())) {
            return false;
        }
        
        $db = new DataSource;
        $sql = '
        select count(1) as count from pollapp.topics t
        where t.id =:topic_id
        and t.user_id =:user_id
        and t.del_flg !=1;
        ';

        $result =  $db->selectOne($sql, [
            ':topic_id' => $topic_id,
            ':user_id' => $user->id,
        ]);

        return !empty($result) && $result['count'] !=0;

    }
     public static function update($topic) {
        
        
        if (!($topic->isValidId()
        * $topic->isValidTitle()
        * $topic->isValidPublished())) {
        return false;
        }

        $db = new DataSource;
         $sql = 'update topics set published = :published,title=:title where id = :id';

         return $db->execute($sql, [
             ':published' => $topic->published,
             ':title' => $topic->title,
            ':id' => $topic->id,
        ]);

    }
    public static function insert($topic,$user) {
        if (!($user->isValidId()
        * $topic->isValidTitle()
        * $topic->isValidPublished())) {
        return false;
        }
        $db = new DataSource;
        $sql = 'insert into topics(title, published, user_id) values (:title, :published, :user_id)';

        return $db->execute($sql, [
            ':title' => $topic->title,
            ':published' => $topic->published,
            ':user_id' => $user->id,
        ]);
    }
    public static function incrementLikesOrDislikes($comment) {
        
        
        if (!($comment->isValidTopicId()
        * $comment->isValidAgree())) {
        return false;
        }

        $db = new DataSource;

        if($comment->agree){
            $sql = 'update topics set likes = likes +1 where id = :topic_id';
        }else{
            $sql = 'update topics set dislikes = dislikes +1 where id = :topic_id';
        }

         

         return $db->execute($sql, [
            ':topic_id' => $comment->topic_id,
        ]);

    } 
}