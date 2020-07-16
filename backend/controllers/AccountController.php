<?php


namespace backend\controllers;

use yii\rest\ActiveController;
use common\models\User;


class AccountController extends ActiveController
{
    public $modelClass = 'User';

    public function actions(){
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    public function actionLogin()
    {
        $request  = \Yii::$app->request;
        $login = $request->post('login');
        $password = $request->post('password');
        $user = null;
        $result = ['error' => 'типа троу еррор'];
        if ($login != null) {
            $user = User::findByEmail($login);
            if ($user != null) {
                if ($user->validatePassword($password)){
                    $result = $user->auth_key;
                }
            }
        }
        return $result;
    }

    public function actionRegister()
    {
        $result = [];
        $request  = \Yii::$app->request;
        $name = $request->post('name');
        $login = $request->post('login');
        $password = $request->post('password');
        if ($name != null && $login != null && $password != null){
            $user = User::findByEmail($login);

            if ($user != null) {
                return ['error' => 'Пользователь уже существует'];
            } else {
                $user = new User();
                $user->username = $name;
                $user->email = $login;
                $user->setPassword($password);
                $user->generateAuthKey();
                $user->save();
                return $user->auth_key;
            }
        }
        return ['error' => 'типа троу еррор'];
    }
}