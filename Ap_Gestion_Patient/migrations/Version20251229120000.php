<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251229120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des champs arriveeValidee et sortieValidee à Sejour';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sejour ADD arrivee_validee BOOLEAN DEFAULT FALSE NOT NULL, ADD sortie_validee BOOLEAN DEFAULT FALSE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sejour DROP arrivee_validee, DROP sortie_validee');
    }
}
