<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2016/04/05 04:05:09
 */
class Version20160405160507 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE ujm_audiomark CHANGE leftTolerancy leftTolerancy INT NOT NULL, 
            CHANGE rightTolerancy rightTolerancy INT NOT NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE ujm_audiomark CHANGE leftTolerancy leftTolerancy DOUBLE PRECISION NOT NULL, 
            CHANGE rightTolerancy rightTolerancy DOUBLE PRECISION NOT NULL
        ");
    }
}