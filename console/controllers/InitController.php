<?php


namespace console\controllers;
use common\models\User;

class InitController extends \yii\console\Controller
{
    
    public function actionIndex(){
        $auth = \Yii::$app->authManager;
        $admin = $auth->getRole('admin');

       if (!$admin) {
            $admin = $auth->createRole('admin');
            $auth->add($admin);
            $auth->assign($admin, 1);
       }
       
        $user = User::findByEmail("admin@admin.ru");
        if (!$user){
            $user = new User();
            $user->username = "admin";
            $user->email = "admin@admin.ru";
            $user->status = 10;
            $user->setPassword("admin12345678");
            $user->generateAuthKey();
            $user->save();
        }
        $auth->assign($admin, $user->getId());
    }

}