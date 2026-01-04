<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251230120001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute les colonnes arrivee_etat et sortie_etat à la table sejour';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sejour ADD arrivee_etat BOOLEAN DEFAULT FALSE NOT NULL, ADD sortie_etat BOOLEAN DEFAULT FALSE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sejour DROP arrivee_etat, DROP sortie_etat');
    }
}
