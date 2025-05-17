<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250503060006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, portfolio_id INT NOT NULL, stock_id INT NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, action VARCHAR(255) NOT NULL, INDEX IDX_A45BDDC1B96B5643 (portfolio_id), INDEX IDX_A45BDDC1DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal_log (id INT AUTO_INCREMENT NOT NULL, stock_id INT NOT NULL, sell_portfolio_id INT NOT NULL, buy_porfolio_id INT NOT NULL, timestamp DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, INDEX IDX_9477FAFADCD6110 (stock_id), INDEX IDX_9477FAFAF07EB4B0 (sell_portfolio_id), INDEX IDX_9477FAFA5A99CBAB (buy_porfolio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depositary (id INT AUTO_INCREMENT NOT NULL, stock_id INT NOT NULL, portfolio_id INT NOT NULL, quantity INT NOT NULL, freeze_quantity INT NOT NULL, INDEX IDX_7CD08B68DCD6110 (stock_id), INDEX IDX_7CD08B68B96B5643 (portfolio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hello (id INT AUTO_INCREMENT NOT NULL, lucky_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE portfolio (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, balance DOUBLE PRECISION NOT NULL, freeze_balance DOUBLE PRECISION NOT NULL, name VARCHAR(63) NOT NULL, INDEX IDX_A9ED1062A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ticker VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1B96B5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE deal_log ADD CONSTRAINT FK_9477FAFADCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE deal_log ADD CONSTRAINT FK_9477FAFAF07EB4B0 FOREIGN KEY (sell_portfolio_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE deal_log ADD CONSTRAINT FK_9477FAFA5A99CBAB FOREIGN KEY (buy_porfolio_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE depositary ADD CONSTRAINT FK_7CD08B68DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE depositary ADD CONSTRAINT FK_7CD08B68B96B5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE portfolio ADD CONSTRAINT FK_A9ED1062A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1B96B5643');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1DCD6110');
        $this->addSql('ALTER TABLE deal_log DROP FOREIGN KEY FK_9477FAFADCD6110');
        $this->addSql('ALTER TABLE deal_log DROP FOREIGN KEY FK_9477FAFAF07EB4B0');
        $this->addSql('ALTER TABLE deal_log DROP FOREIGN KEY FK_9477FAFA5A99CBAB');
        $this->addSql('ALTER TABLE depositary DROP FOREIGN KEY FK_7CD08B68DCD6110');
        $this->addSql('ALTER TABLE depositary DROP FOREIGN KEY FK_7CD08B68B96B5643');
        $this->addSql('ALTER TABLE portfolio DROP FOREIGN KEY FK_A9ED1062A76ED395');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE deal_log');
        $this->addSql('DROP TABLE depositary');
        $this->addSql('DROP TABLE hello');
        $this->addSql('DROP TABLE portfolio');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE user');
    }
}
