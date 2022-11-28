<?php

namespace model;

use lib\Msg;

class TopicModel extends AbstractModel
{

    public int $id;
    public string $title;
    public int $published;
    public int $views;
    public int $likes;
    public int $dislikes;
    public string $user_id;
    public int $del_flg;
    protected static $SESSION_NAME = '_topic';

    public function isValidId()
    {
        return static::validateId($this->id);
    }

    public static function validateId($val) {
        $res = true;

        if (empty($val) || !is_numeric($val)) {

            Msg::push(Msg::ERROR, 'パラメータが不正です。');
            $res = false;

        }

        return $res;
    }

    public function isValidTitle()
    {
        return static::validateTitle($this->title);
    }

    public static function validateTitle($val) {
        $res = true;

        if (empty($val)) {

            Msg::push(Msg::ERROR, 'タイトルを入力してください。');
            $res = false;

        } else {

            if (mb_strlen($val) > 30) {

                Msg::push(Msg::ERROR, 'タイトルは30文字以内で入力してください。');
                $res = false;

            }

        }

        return $res;
    }

    public function isValidPublished()
    {
        return static::validatePublished($this->published);
    }

    public static function validatePublished($val) {
        $res = true;

        if (!isset($val)) {

            Msg::push(Msg::ERROR, '公開するか選択してください。');
            $res = false;

        } else {
            // 0、または1以外の時
            if (!($val == 0 || $val == 1)) {

                Msg::push(Msg::ERROR, '公開ステータスが不正です。');
                $res = false;

            }
        }

        return $res;
    }
}
