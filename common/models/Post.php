<?php

namespace common\models;
use common\models\basePost;
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
class Post extends basePost
{
    
}
