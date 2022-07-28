<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220729083013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "deleted_resource" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE deleted_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE deleted_resource (id INT NOT NULL, deleted_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, resource_class VARCHAR(64) NOT NULL, data JSON NOT NULL, original_internal_id INT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F1D8757D17F50A6 ON deleted_resource (uuid)');
        $this->addSql('COMMENT ON COLUMN deleted_resource.deleted_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN deleted_resource.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "user" ALTER active DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE deleted_resource_id_seq CASCADE');
        $this->addSql('DROP TABLE deleted_resource');
        $this->addSql('ALTER TABLE "user" ALTER active SET DEFAULT true');
    }
}
