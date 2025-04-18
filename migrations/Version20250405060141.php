<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405060141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1A76ED395');
        $this->addSql('DROP INDEX IDX_A45BDDC1A76ED395 ON application');
        $this->addSql('ALTER TABLE application CHANGE user_id portfolio_id INT NOT NULL');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1B96B5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio (id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1B96B5643 ON application (portfolio_id)');
        $this->addSql('ALTER TABLE deal_log CHANGE deal_log timestamp DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE deal_log ADD CONSTRAINT FK_9477FAFAF07EB4B0 FOREIGN KEY (sell_portfolio_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE deal_log ADD CONSTRAINT FK_9477FAFA5A99CBAB FOREIGN KEY (buy_porfolio_id) REFERENCES portfolio (id)');
        $this->addSql('CREATE INDEX IDX_9477FAFAF07EB4B0 ON deal_log (sell_portfolio_id)');
        $this->addSql('CREATE INDEX IDX_9477FAFA5A99CBAB ON deal_log (buy_porfolio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1B96B5643');
        $this->addSql('DROP INDEX IDX_A45BDDC1B96B5643 ON application');
        $this->addSql('ALTER TABLE application CHANGE portfolio_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A45BDDC1A76ED395 ON application (user_id)');
        $this->addSql('ALTER TABLE deal_log DROP FOREIGN KEY FK_9477FAFAF07EB4B0');
        $this->addSql('ALTER TABLE deal_log DROP FOREIGN KEY FK_9477FAFA5A99CBAB');
        $this->addSql('DROP INDEX IDX_9477FAFAF07EB4B0 ON deal_log');
        $this->addSql('DROP INDEX IDX_9477FAFA5A99CBAB ON deal_log');
        $this->addSql('ALTER TABLE deal_log CHANGE timestamp deal_log DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
