<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205100047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courrier ALTER contenu DROP NOT NULL');
        $this->addSql('ALTER TABLE courrier ALTER date_reception TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BEF47CAAAEA34913 ON courrier (reference)');
        $this->addSql('ALTER TABLE "user" ADD is_verified BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BEF47CAAAEA34913');
        $this->addSql('ALTER TABLE courrier ALTER contenu SET NOT NULL');
        $this->addSql('ALTER TABLE courrier ALTER date_reception TYPE DATE');
        $this->addSql('ALTER TABLE "user" DROP is_verified');
    }
}
