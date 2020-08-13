<?php
namespace common\models;

use Yii;
use yii\base\Model;

class AuthForm extends Model
{
    public $login;
    public $password;
    public $email;


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
                ['email', 'password'], 
                'required', 
                'on' => self::SCENARIO_LOGIN, 
                'message' => 'Не верный формат входных параметров. Для входа должны быть указаны: email и пароль',
            ],
            [
                ['login', 'password', 'email'], 
                'required', 
                'on' => self::SCENARIO_REGISTER,
                'message' => 'Не верный формат входных параметров. Для регистрации должны быть указаны: email, имя пользователя, пароль',
            ],
            ['email', 'email'],
            [['password'], 'string'],
            [['login'], 'string'],
            ['password', 'validatePassword', 'on' => self::SCENARIO_LOGIN ],
            ['email','validateExistEmail', 'on' => self::SCENARIO_REGISTER ],
            ['login','validateExistLogin', 'on' => self::SCENARIO_REGISTER ],
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
            return $this->_user->authKey;
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
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['email', 'password'],
            self::SCENARIO_REGISTER => ['email', 'login', 'password'],
        ];
    }

    public function userRegistry()
    {
        if ($this->validate()) {
            return $this->_user->authKey;
        }
        
        return false;
    }

    public function validateExistEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!empty($user)) {
                $this->addError($attribute, 'Пользователь с таким email уже существует');
            }
        }
    }
    public function validateExistLogin($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!empty(User::findByUsername($this->login))) {
                $this->addError($attribute, 'Пользователь с таким логином уже существует');
            }
        }
    }

    public function registerUser()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->login;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save();
            return $user->authKey;
        }
        return false;
    }
}
