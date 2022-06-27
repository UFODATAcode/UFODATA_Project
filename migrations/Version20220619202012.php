<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220619202012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create observation table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE observation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE observation (id INT NOT NULL, provider_id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, provided_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C576DBE0A53A8AA ON observation (provider_id)');
        $this->addSql('COMMENT ON COLUMN observation.provided_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0A53A8AA FOREIGN KEY (provider_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('COMMENT ON COLUMN observation.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C576DBE0D17F50A6 ON observation (uuid)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE observation_id_seq CASCADE');
        $this->addSql('DROP TABLE observation');
    }
}
