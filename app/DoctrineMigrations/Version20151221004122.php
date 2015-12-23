<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151221004122 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->connection->executeQuery("INSERT INTO `word` (`id`, `word`, `translate`) VALUES
(1, 'apple', 'яблоко'),
(2, 'pear', 'груша'),
(3, 'orange', 'апельсин'),
(4, 'grape', 'виноград'),
(5, 'lemon', 'лимон'),
(6, 'pineapple', 'ананас'),
(7, 'watermelon', 'арбуз'),
(8, 'coconut', 'кокос'),
(9, 'banana', 'банан'),
(10, 'pomelo', 'помело'),
(11, 'strawberry', 'клубника'),
(12, 'raspberry', 'малина'),
(13, 'melon', 'дыня'),
(14, 'apricot', 'абрикос'),
(15, 'mango', 'манго'),
(16, 'plum', 'слива'),
(17, 'pomegranate', 'гранат'),
(18, 'cherry', 'вишня');");
        // this up() migration is auto-generated, please modify it to your needs

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
