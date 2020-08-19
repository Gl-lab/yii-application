<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Post;

class PostForm extends Model
{
    public $accessToken;
    public $text;
    public $offset;
    public $limit;

    private $_user;

    const SCENARIO_NEW_POST = 'new_post';
    const SCENARIO_GET_POSTS = 'get_post';

    public function scenarios()
    {
        return [
            self::SCENARIO_NEW_POST => ['accessToken', 'text'],
            self::SCENARIO_GET_POSTS => ['accessToken', 'offset', 'limit'],
        ];
    }

    public function rules()
    {
        return [
            [
                ['accessToken', 'text'], 
                'required', 
                'on' => self::SCENARIO_NEW_POST, 
                'message' => 'Не верный формат входных параметров. Создания поста должы быть указаны accessToken и текст поста',
            ],
            [
                ['accessToken'], 
                'required', 
                'on' => self::SCENARIO_GET_POSTS,
                'message' => 'Для получения доступа к постам небходимо указать accessToken',
            ],
            [['accessToken'], 'string'],
            [['text'], 'string'],
            [['offset'], 'number', 'min' => 0],
            [['limit'], 'number', 'min' => 1],
            ['accessToken', 'validateToken'],
        ];
    }

    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = User::findIdentityByAccessToken($this->accessToken);
            if (empty($this->_user)) {
                $this->addError($attribute, 'Ошибка авторизации');
            }
        }
    }

    public function createNewPost()
    {
        if ($this->validate()) {
            $post = new Post();
            $post->authorId = $this->_user->getId();
            $post->title = 'title';
            $post->body = $this->text;
            $post->save();
            return [
                'success' => 'Пост успешно опубликован'
            ]; 
        }
        return false;
    }

    public function getUserPosts()
    {
        if ($this->validate()) {
            return  $this->getBaseQuery()
                ->andWhere(['authorId' => $this->_user->id])
                ->all();
        }
        return false;
    }

    public function getAllPosts()
    {
        if ($this->validate()) {
            return $this->getBaseQuery()
                ->all();  
        }
        return false;
    }

    private function getBaseQuery()
    {
        return Post::find()
            ->limit($this->limit)
            ->offset($this->offset);
    }
}