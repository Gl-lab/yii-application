<?php


namespace frontend\controllers;

use common\controllers\BaseController;
use common\models\AuthForm;


class AccountController extends BaseController
{
    public $modelClass = 'User';

    /**
     * @api {post} accounts/login
     * @apiName login
     * @apiGroup Accounts
     *
     * @apiParam {String} login Users email.
     * @apiParam {String} password Users password.
     *
     * @apiSuccess {String} accessToken  accessToken of the User.
     */
    public function actionLogin()
    {
        $model = new AuthForm(['scenario' => AuthForm::SCENARIO_LOGIN]);
        $model->load(\Yii::$app->request->post(), '');
        $authKey = $model->getAuthKey();
        if ($authKey) {
            return [
                'authKey' => $authKey
            ];      
        } else {
            return $model->errors;
        }         
    }
    
    /**
     * @api {post} accounts/register
     * @apiName register
     * @apiGroup Accounts
     *
     * @apiParam {String} name Users nickname.
     * @apiParam {String} login Users email.
     * @apiParam {String} password Users password.
     *
     * @apiSuccess {String} accessToken  accessToken of the User.
     */
    public function actionRegister()
    {
        $model = new AuthForm(['scenario' => AuthForm::SCENARIO_REGISTER]);
        $model->load(\Yii::$app->request->post(), '');
        $authKey = $model->registerUser();
        if ($authKey) {
            return [
                'authKey' => $authKey
            ];      
        } else {
            return $model->errors;
        }         
    }
}