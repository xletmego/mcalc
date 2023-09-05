<?php declare(strict_types=1);
namespace Migrations;

use App\Data\Storage\PdfTableDB;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

final class Migration202309041741
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function migrate():void
    {
        $schema = new Schema();

        $this->createPdf_files_table($schema);
        $queries = $schema->toSql($this->connection->getDatabasePlatform());

        foreach ($queries as $query) {
            $this->connection->executeQuery($query);
        }
    }

    private function createPdf_files_table(Schema $schema):void
    {
        $table = $schema->createTable('users');

        $table->addColumn('id', Types::INTEGER, array(
            'notnull' => true,
            'autoincrement' => true,
        ));
        $table->setPrimaryKey(array('id'));

        $table->addColumn('login', Types::STRING, array(
            "length" => 255,
            'default' => '',
            'notnull' => false,
        ));

        $table->addColumn('password', Types::STRING, array(
            "length" => 255,
            'default' => '',
            'notnull' => false,
        ));

        $table->addColumn('vars', Types::STRING, array(
            "length" => 256,
            'default' => '',
            'notnull' => false,
        ));
    }
}
