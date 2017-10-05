<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m171004_130836_create_comment_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'content' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('comment');
    }

}
