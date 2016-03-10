<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution.
 *
 * Generation date: 2016/03/10 11:17:26
 */
class Version20160310111724 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            CREATE TABLE ujm_type_audio_mark (
                id INT AUTO_INCREMENT NOT NULL, 
                value VARCHAR(255) NOT NULL, 
                code INT NOT NULL, 
                UNIQUE INDEX UNIQ_ADFF889177153098 (code), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE ujm_interaction_audio_mark (
                id INT AUTO_INCREMENT NOT NULL, 
                type_audio_mark_id INT DEFAULT NULL, 
                question_id INT DEFAULT NULL, 
                INDEX IDX_638B5F88DCA4FC2 (type_audio_mark_id), 
                UNIQUE INDEX UNIQ_638B5F881E27F6BF (question_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            ALTER TABLE ujm_interaction_audio_mark 
            ADD CONSTRAINT FK_638B5F88DCA4FC2 FOREIGN KEY (type_audio_mark_id) 
            REFERENCES ujm_type_audio_mark (id)
        ');
        $this->addSql('
            ALTER TABLE ujm_interaction_audio_mark 
            ADD CONSTRAINT FK_638B5F881E27F6BF FOREIGN KEY (question_id) 
            REFERENCES ujm_question (id) 
            ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE ujm_interaction_audio_mark 
            DROP FOREIGN KEY FK_638B5F88DCA4FC2
        ');
        $this->addSql('
            DROP TABLE ujm_type_audio_mark
        ');
        $this->addSql('
            DROP TABLE ujm_interaction_audio_mark
        ');
    }
}
