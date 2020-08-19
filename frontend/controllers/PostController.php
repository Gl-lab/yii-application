<?php


namespace frontend\controllers;
use common\models\Post;
use common\controllers\BaseController;
use common\models\PostForm;

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
        $model = new PostForm(['scenario' => PostForm::SCENARIO_NEW_POST]);
        $model->load(\Yii::$app->request->post(), '');
        $result = $model->createNewPost();
        if ($result) {
            return $result; 
        } else {
            return $model->errors;
        }
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
        $model = new PostForm(['scenario' => PostForm::SCENARIO_GET_POSTS]);
        $model->load(\Yii::$app->request->get(), '');
        $result = $model->getAllPosts();
        if ($result) {
            return $result; 
        } else {
            return $model->errors;
        }
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
        $model = new PostForm(['scenario' => PostForm::SCENARIO_GET_POSTS]);
        $model->load(\Yii::$app->request->get(), '');
        $result = $model->getUserPosts();
        if ($result) {
            return $result; 
        } else {
            return $model->errors;
        }
    }
}