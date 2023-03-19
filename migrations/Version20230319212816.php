<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230319212816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "measurement_metadata" and "video_metadata" tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE measurement_metadata_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE measurement_metadata (id INT NOT NULL, uuid UUID NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C5929B5D17F50A6 ON measurement_metadata (uuid)');
        $this->addSql('COMMENT ON COLUMN measurement_metadata.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE video_metadata (id INT NOT NULL, format VARCHAR(8) NOT NULL, play_time_string VARCHAR(16) DEFAULT NULL, play_time_seconds DOUBLE PRECISION DEFAULT NULL, bit_rate DOUBLE PRECISION DEFAULT NULL, bit_rate_mode VARCHAR(255) DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, total_frames INT DEFAULT NULL, frame_rate DOUBLE PRECISION DEFAULT NULL, codec_name VARCHAR(255) DEFAULT NULL, pixel_aspect_ratio DOUBLE PRECISION DEFAULT NULL, bits_per_sample INT DEFAULT NULL, compression_ratio DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE video_metadata ADD CONSTRAINT FK_E79606A8BF396750 FOREIGN KEY (id) REFERENCES measurement_metadata (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE measurement ADD metadata_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D811DC9EE959 FOREIGN KEY (metadata_id) REFERENCES measurement_metadata (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2CE0D811DC9EE959 ON measurement (metadata_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement DROP CONSTRAINT FK_2CE0D811DC9EE959');
        $this->addSql('DROP SEQUENCE measurement_metadata_id_seq CASCADE');
        $this->addSql('ALTER TABLE video_metadata DROP CONSTRAINT FK_E79606A8BF396750');
        $this->addSql('DROP TABLE measurement_metadata');
        $this->addSql('DROP TABLE video_metadata');
        $this->addSql('DROP INDEX IDX_2CE0D811DC9EE959');
        $this->addSql('ALTER TABLE measurement DROP metadata_id');
    }
}
