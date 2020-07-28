<?php


namespace backend\rbac;


use yii\rbac\Item;

class AuthorRule extends \yii\rbac\Rule
{
    public $name = 'isAuthor';
    public function execute($user, $item, $params)
    {
        return isset($params['post']) ? $params['post']->authorId == $user : false;
    }
}