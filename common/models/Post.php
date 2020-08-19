<?php

namespace common\models;
use common\models\BasePost;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $authorId
 * @property string|null $title
 * @property string|null $body
 *
 * @property User $author
 */
class Post extends BasePost
{
    
}
