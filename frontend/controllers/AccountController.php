<?php


namespace frontend\controllers;

use common\controllers\BaseController;
use common\models\User;
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
        $request  = \Yii::$app->request;
        $name = $request->post('name');
        $login = $request->post('login');
        $password = $request->post('password');
        if (empty($name) || empty($login) || empty($password)) 
            return [
            'error' => 'Не верный формат входных параметров. Для регистрации должены быть указаны: email, имя пользователя, пароль'
            ];
        
        $user = User::findByEmail($login);
        if (!empty($user)) 
            return [
                'error' => 'Пользователь с указанным email уже зарегистрирован'
            ];

        $user = new User();
        $user->username = $name;
        $user->email = $login;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->save();
        return [
            'authKey' => $user->authKey
        ];    
    }
}