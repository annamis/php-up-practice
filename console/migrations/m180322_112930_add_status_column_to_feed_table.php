<?php

use yii\db\Migration;

/**
 * Handles adding status to table `feed`.
 */
class m180322_112930_add_status_column_to_feed_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('feed', 'status', $this->integer());
        $this->addColumn('post', 'status', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('feed', 'status');
    }
}
