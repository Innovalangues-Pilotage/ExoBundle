<?php

namespace UJM\ExoBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2016/03/30 10:15:15
 */
class Version20160330101442 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE ujm_exercise_question (
                exercise_id INT NOT NULL, 
                question_id INT NOT NULL, 
                ordre INT NOT NULL, 
                INDEX IDX_DB79F240E934951A (exercise_id), 
                INDEX IDX_DB79F2401E27F6BF (question_id), 
                PRIMARY KEY(exercise_id, question_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE ujm_exercise_question 
            ADD CONSTRAINT FK_DB79F240E934951A FOREIGN KEY (exercise_id) 
            REFERENCES ujm_exercise (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE ujm_exercise_question 
            ADD CONSTRAINT FK_DB79F2401E27F6BF FOREIGN KEY (question_id) 
            REFERENCES ujm_question (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE ujm_audiomark 
            ADD rightAnswer TINYINT(1) NOT NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE ujm_exercise_question
        ");
        $this->addSql("
            ALTER TABLE ujm_audiomark 
            DROP rightAnswer
        ");
    }
}