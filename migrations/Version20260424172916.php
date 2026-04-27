<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260424172916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout du champ disponible dans chambre';
    }

    public function up(Schema $schema): void
    {
        //  On ajoute seulement le champ disponible
        $this->addSql('ALTER TABLE chambre ADD disponible TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        //  On supprime seulement ce champ
        $this->addSql('ALTER TABLE chambre DROP COLUMN disponible');
    }
}