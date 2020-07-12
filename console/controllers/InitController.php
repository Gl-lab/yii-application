<?php


namespace console\controllers;


class InitController extends \yii\console\Controller
{
    public function actionInit(){
        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->assign($admin, 1);

        $user = new User();
        $user->username = "admin";
        $user->email = "admin@admin.ru";
        $user->setPassword("admin12345678");
        $user->generateAuthKey();
        $user->save();

        $auth->assign($admin, $user->getId());
    }

}