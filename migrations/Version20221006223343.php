<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221006223343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add "user_activation_link" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_activation_link (id UUID NOT NULL, user_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, was_send BOOLEAN NOT NULL, was_used BOOLEAN NOT NULL, expiration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A932897CA76ED395 ON user_activation_link (user_id)');
        $this->addSql('COMMENT ON COLUMN user_activation_link.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_activation_link.expiration_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_activation_link ADD CONSTRAINT FK_A932897CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_activation_link');
    }
}
