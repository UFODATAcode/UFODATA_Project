<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220722212847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user-measurement relation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement ADD provider_id INT NOT NULL');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D811A53A8AA FOREIGN KEY (provider_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2CE0D811A53A8AA ON measurement (provider_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement DROP CONSTRAINT FK_2CE0D811A53A8AA');
        $this->addSql('DROP INDEX IDX_2CE0D811A53A8AA');
        $this->addSql('ALTER TABLE measurement DROP provider_id');
    }
}
