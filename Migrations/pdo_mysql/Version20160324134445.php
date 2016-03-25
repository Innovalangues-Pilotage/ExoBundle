<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution.
 *
 * Generation date: 2016/03/24 01:44:57
 */
class Version20160324134445 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            CREATE TABLE ujm_audiomark (
                id INT AUTO_INCREMENT NOT NULL, 
                interaction_audiomark_id INT DEFAULT NULL, 
                feedback LONGTEXT DEFAULT NULL, 
                INDEX IDX_1A6DFA0061E8133E (interaction_audiomark_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE ujm_audiomark 
            ADD CONSTRAINT FK_1A6DFA0061E8133E FOREIGN KEY (interaction_audiomark_id) 
            REFERENCES ujm_interaction_audio_mark (id)
        ');
    }

    public function down(Schema $schema)
    {
        $this->addSql('
            DROP TABLE ujm_audiomark
        ');
    }
}
