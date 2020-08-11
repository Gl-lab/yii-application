<?php


namespace frontend\controllers;
use common\models\Post;
use common\controllers\BaseController;
use common\models\User;

class PostController extends BaseController
{
    public $modelClass = 'Post';
    /**
     * @api {post} posts/new
     * @apiName new
     * @apiGroup Post
     *
     * @apiParam {String} accessToken accessToken of the User.
     * @apiParam {String} text The text of the post.
     *
     * @apiSuccess {Object[]} success.
     */
    public function actionNew()
    {
        $request  = \Yii::$app->request;
        $accessToken = $request->post('accessToken');
        $text = $request->post('text');
        if (empty($accessToken) || empty($text)) 
            return [
                'error' => 'Неверные данные'
            ];
        
        $user = User::findIdentityByAccessToken($accessToken);
        if (empty($user)) 
            return [
                'error' => 'Ошибка авторизации'
            ];
        
        $post = new Post();
        $post->authorId = $user->getId();
        $post->title = 'title';
        $post->body = $text;
        $post->save();
        return [
            'success' => 'Пост успешно опубликован'
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
        if (empty($accessToken))
            return [
                'error' => 'Неверные данные'
            ];
        
        $user = User::findIdentityByAccessToken($accessToken);
        if (empty($user))
            return [
                'error' => 'Ошибка авторизации'
            ];
        
        return Post::find()
            ->limit($limit)
            ->offset($offset)
            ->all();  
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
        if (empty($accessToken)) 
            return [
                'error' => 'Неверные данные'
            ];
        
        $user = User::findIdentityByAccessToken($accessToken);
        if (empty($user)) 
            return [
                'error' => 'Ошибка авторизации'
            ];
        
        return Post::find()
            ->andWhere('authorId = :userId', [':userId' => $user->id])
            ->limit($limit)
            ->offset($offset)
            ->all();
    }
}