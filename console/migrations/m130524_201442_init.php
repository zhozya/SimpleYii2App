<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // User
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Club
        $this->createTable('{{%club}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'address' => $this->text()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updater_id' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleter_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-club-creator',
            '{{%club}}',
            'creator_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-club-updater',
            '{{%club}}',
            'updater_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-club-deleter',
            '{{%club}}',
            'deleter_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        // Client
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'is_male' => $this->boolean()->notNull(),
            'date_of_birth' => $this->date()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updater_id' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleter_id' => $this->integer(),
            'deleted_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-client-creator',
            '{{%client}}',
            'creator_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-client-updater',
            '{{%client}}',
            'updater_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-client-deleter',
            '{{%client}}',
            'deleter_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->createTable('{{%client_clubs}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'club_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-client_clubs-unique',
            '{{%client_clubs}}',
            ['client_id', 'club_id'],
            true,
        );

        $this->addForeignKey(
            'fk-client_clubs-client',
            '{{%client_clubs}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->addForeignKey(
            'fk-client_clubs-club',
            '{{%client_clubs}}',
            'club_id',
            '{{%club}}',
            'id',
            'CASCADE',
            'CASCADE',
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-client_clubs-client', '{{%client_clubs}}');
        $this->dropForeignKey('fk-client_clubs-club', '{{%client_clubs}}');
        $this->dropTable('{{%client_clubs}}');

        $this->dropForeignKey('fk-club-creator', '{{%club}}');
        $this->dropForeignKey('fk-club-updater', '{{%club}}');
        $this->dropForeignKey('fk-club-deleter', '{{%club}}');
        $this->dropTable('{{%club}}');

        $this->dropForeignKey('fk-client-creator', '{{%client}}');
        $this->dropForeignKey('fk-client-updater', '{{%client}}');
        $this->dropForeignKey('fk-client-deleter', '{{%client}}');
        $this->dropTable('{{%client}}');

        $this->dropTable('{{%user}}');
    }
}
