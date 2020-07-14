<?php


namespace backend\controllers;


class PostController extends \yii\rest\Controller
{
    public $modelClass = 'backend\models\Post';

    public function actionPosts()
    {
        return ['xz'];
    }

    public function actionMyPosts()
    {
        return ['ok'];
    }
}