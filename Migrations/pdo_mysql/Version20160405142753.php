<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2016/04/05 02:27:57
 */
class Version20160405142753 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE ujm_audiomark 
            ADD leftTolerancy DOUBLE PRECISION NOT NULL, 
            ADD rightTolerancy DOUBLE PRECISION NOT NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE ujm_audiomark 
            DROP leftTolerancy, 
            DROP rightTolerancy
        ");
    }
}