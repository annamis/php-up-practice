<?php

use yii\db\Migration;

/**
 * Handles adding complaints to table `post`.
 */
class m171103_125053_add_complaints_column_to_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%post}}', 'complaints', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%post}}', 'complaints');
    }
}
