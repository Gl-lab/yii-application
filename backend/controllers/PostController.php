<?php


namespace backend\controllers;
use common\models\Post;
use yii\rest\ActiveController;
use common\models\User;

class PostController extends ActiveController
{
    public $modelClass = 'Post';

    public function actions()
    {
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
        if (!empty($accessToken) && !empty($text)) {
            $user = User::findIdentityByAccessToken($accessToken);
            if (!empty($user)) {
                $post = new Post();
                $post->authorId = $user->getId();
                $post->title = 'title';
                $post->body = $text;
                $post->save();
                return [
                    'success' => 'Пост успешно опубликован'
                ];
            }
            return [
                'error' => 'Ошибка авторизации'
            ];
        }
        return [
            'error' => 'Неверные данные'
        ];
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
        if (!empty($accessToken)) {
            $user = User::findIdentityByAccessToken($accessToken);
            if (!empty($user)) {
                return Post::find()
                    ->limit($limit)
                    ->offset($offset)
                    ->all();
            }
            return [
                'error' => 'Ошибка авторизации'
            ];
        }
        return [
            'error' => 'Неверные данные'
        ];
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
        if (!empty($accessToken)) {
            $user = User::findIdentityByAccessToken($accessToken);
            if (!empty($user)) {
                return Post::find()
                    ->where('authorId = :userId', [':userId' => $user->id])
                    ->limit($limit)
                    ->offset($offset)
                    ->all();
            }
            return [
                'error' => 'Ошибка авторизации'
            ];
        }
        return [
            'error' => 'Неверные данные'
        ];
    }
}