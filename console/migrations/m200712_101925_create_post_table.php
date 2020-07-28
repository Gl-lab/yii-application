<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m200712_101925_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'authorId' => $this->integer()->notNull(),
            'title' => $this->string(),
            'body' => $this->text(),
        ]);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-post-authorId',
            'post',
            'authorId',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('post');
    }
}
