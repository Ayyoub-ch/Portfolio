<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251230100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des champs arrivee_etat et sortie_etat à Sejour';
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
