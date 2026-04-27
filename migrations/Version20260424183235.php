<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260424183235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout du champ image dans chambre';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE chambre ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE chambre DROP image');
    }
}