<?php
namespace common\models;

use Yii;
use yii\base\Model;

class AuthForm extends Model
{
    public $login;
    public $password;
    public $name;


    private $_user;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [
                ['login', 'password'], 
                'required', 
                'on' => self::SCENARIO_LOGIN, 
                'message' => 'Не верный формат входных параметров. Для регистрации должены быть указаны: email и пароль',
            ],
            [
                ['login', 'password', 'name'], 
                'required', 
                'on' => self::SCENARIO_REGISTER,
                'message' => 'Не верный формат входных параметров. Для регистрации должены быть указаны: email, имя пользователя, пароль',
            ],
            ['login', 'email'],
            [['password'], 'string'],
            [['password'], 'string'],
            ['password', 'validatePassword', 'on' => self::SCENARIO_LOGIN ],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function getAuthKey()
    {
        if ($this->validate()) {
            return $this->getUser()->authKey;
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->login);
        }

        return $this->_user;
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['login', 'password'],
            self::SCENARIO_REGISTER => ['name', 'login', 'password'],
        ];
    }
}
