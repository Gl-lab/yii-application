<?php


namespace frontend\controllers;

use common\controllers\BaseController;
use common\models\User;


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
        $request  = \Yii::$app->request;
        $login = $request->post('login');
        $password = $request->post('password');
        $user = null;
        if (!empty($login)) {
            $user = User::findByEmail($login);
            if (!empty($user)) {
                if ($user->validatePassword($password)) {
                    return [
                        'authKey' => $user->authKey
                    ];
                }
            }
            return [
                'error' => 'Неверно указан email или пароль пользователя'
            ];
        } 
        return [
                'error' => 'Не верный формат входных параметров. Не указал email пользователя'
        ];
        
        
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
        if (!empty($name) && !empty($login) && !empty($password)) {
            $user = User::findByEmail($login);
            if (empty($user)) {
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
            return [
                'error' => 'Пользователь с указанным email уже зарегистрирован'
            ];
        }
        return [
            'error' => 'Не верный формат входных параметров. Для регистрации должены быть указаны: email, имя пользователя, пароль'
        ];
    }
}