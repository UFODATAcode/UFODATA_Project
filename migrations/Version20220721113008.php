<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220721113008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create mesaurement table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE measurement_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE measurement (id INT NOT NULL, observation_id INT NOT NULL, name VARCHAR(64) DEFAULT NULL, provided_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, uuid UUID NOT NULL, original_file_metadata_name VARCHAR(255) DEFAULT NULL, original_file_metadata_original_name VARCHAR(255) DEFAULT NULL, original_file_metadata_mime_type VARCHAR(255) DEFAULT NULL, original_file_metadata_size INT DEFAULT NULL, original_file_metadata_dimensions TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CE0D811D17F50A6 ON measurement (uuid)');
        $this->addSql('CREATE INDEX IDX_2CE0D8111409DD88 ON measurement (observation_id)');
        $this->addSql('COMMENT ON COLUMN measurement.provided_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN measurement.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN measurement.original_file_metadata_dimensions IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D8111409DD88 FOREIGN KEY (observation_id) REFERENCES observation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE measurement_id_seq CASCADE');
        $this->addSql('DROP TABLE measurement');
    }
}
