<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260427093955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la référence PayPal dans paiement';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE paiement ADD reference_paypal VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE paiement DROP reference_paypal');
    }
}