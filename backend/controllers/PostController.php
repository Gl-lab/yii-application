<?php


namespace backend\controllers;
use common\models\Post;
use yii\rest\ActiveController;
use common\models\User;

class PostController extends ActiveController
{
    public $modelClass = 'Post';

    public function actions(){
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    /**
     * @api {post} posts/new
     * @apiName new
     * @apiGroup Post
     *
     * @apiParam {String} accessToken accessToken of the User.
     * @apiParam {String} text The text of the post.
     *
     * @apiSuccess {[]} EmptyArray  [] <- this is a square, and a square is good.
     */
    public function actionNew()
    {
        $request  = \Yii::$app->request;
        $accessToken = $request->post('accessToken');
        $text = $request->post('text');
        if ($accessToken != null && $text != null){
            $user = User::findIdentityByAccessToken($accessToken);
            if ($user != null){
                $post = new Post();
                $post->author_id = $user->getId();
                $post->title = 'title';
                $post->body = $text;
                $post->save();
                return [];
            }
            return ['error' => 'Ошибка авторизации'];
        }
        return ['error' => 'Неверные данные'];
    }
    /**
     * @api {get} posts/all
     * @apiName all
     * @apiGroup Post
     *
     * @apiParam {String} accessToken accessToken of the User.
     * @apiParam {Number} offset How many records have already been uploaded. Optional field
     * @apiParam {Number} limit How many records to return. Optional field
     *
     * @apiSuccess {Object[]} Post  Posts list
     */
    public function actionAll()
    {
        $request  = \Yii::$app->request;
        $accessToken = $request->get('accessToken');
        $offset = $request->get('offset',0);
        $limit = $request->get('limit',10);
        if ($accessToken != null){
            $user = User::findIdentityByAccessToken($accessToken);
            if ($user != null){
                return Post::find()->limit($limit)->offset($offset)->all();
            }
            return ['error' => 'Ошибка авторизации'];
        }
        return ['error' => 'Неверные данные'];
    }
    /**
     * @api {get} posts/my
     * @apiName my
     * @apiGroup Post
     *
     * @apiParam {String} accessToken accessToken of the User.
     * @apiParam {Number} offset How many records have already been uploaded. Optional field
     * @apiParam {Number} limit How many records to return. Optional field
     *
     * @apiSuccess {Object[]} Post  Posts list of user
     */
    public function actionMy()
    {
        $request  = \Yii::$app->request;
        $accessToken = $request->get('accessToken');
        $offset = $request->get('offset',0);
        $limit = $request->get('limit',10);
        if ($accessToken != null){
            $user = User::findIdentityByAccessToken($accessToken);
            if ($user != null){
                return Post::find()->where('author_id = :user_id', [':user_id' => $user->id])->limit($limit)->offset($offset)->all();
            }
            return ['error' => 'Ошибка авторизации'];
        }
        return ['error' => 'Неверные данные'];
    }
}