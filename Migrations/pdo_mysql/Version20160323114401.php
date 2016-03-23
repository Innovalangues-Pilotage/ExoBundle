<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution.
 *
 * Generation date: 2016/03/23 11:44:10
 */
class Version20160323114401 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE ujm_interaction_audio_mark 
            ADD audioResource_id INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE ujm_interaction_audio_mark 
            ADD CONSTRAINT FK_638B5F886CB1B387 FOREIGN KEY (audioResource_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE SET NULL
        ');
        $this->addSql('
            CREATE INDEX IDX_638B5F886CB1B387 ON ujm_interaction_audio_mark (audioResource_id)
        ');
    }

    public function down(Schema $schema)
    {
        $this->addSql('
            ALTER TABLE ujm_interaction_audio_mark 
            DROP FOREIGN KEY FK_638B5F886CB1B387
        ');
        $this->addSql('
            DROP INDEX IDX_638B5F886CB1B387 ON ujm_interaction_audio_mark
        ');
        $this->addSql('
            ALTER TABLE ujm_interaction_audio_mark 
            DROP audioResource_id
        ');
    }
}
