<?php declare(strict_types=1);
namespace Migrations;

use App\Data\Storage\PdfTableDB;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

final class Migration202309041741
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function migrate():void
    {
        $schema = new Schema();

        $this->createTable($schema);
        $queries = $schema->toSql($this->connection->getDatabasePlatform());

        foreach ($queries as $query) {
            $this->connection->executeQuery($query);
        }
    }

    private function createTable(Schema $schema):void
    {
        $table = $schema->createTable('users');

        $table->addColumn('id', Types::STRING, array(
            "length" => 34,
            'notnull' => true,
        ));
        $table->setPrimaryKey(array('id'));

        $table->addColumn('name', Types::STRING, array(
            "length" => 255,
            'default' => '',
            'notnull' => false,
        ));

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

        $table->addColumn('param1', Types::INTEGER, array(
            "length" => 256,
            'default' => 0,
            'notnull' => false,
        ));
        $table->addColumn('param2', Types::INTEGER, array(
            "length" => 256,
            'default' => 0,
            'notnull' => false,
        ));
    }
}
