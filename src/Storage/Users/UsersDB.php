<?php declare(strict_types=1);

namespace App\Storage\Users;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
use Ramsey\Uuid\Uuid;

class UsersDB
{
    const TABLE_NAME = 'users';
    const FIELDS = [
        'id' => 'id',
        'name' => 'varchar',
        'login' => 'varchar',
        'password' => 'varchar',
        'param1' => 'int',
        'param2' => 'int',
    ];

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getRecord(string $id):array
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select(array_keys(self::FIELDS))
            ->from(self::TABLE_NAME)
            ->where('id', ':id')
            ->setParameter(':id', $id)
            ->execute()
            ->fetchAssociative();

        return is_array($result) ? $result : array();
    }

    /**
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getList():array
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select(array_keys(self::FIELDS))
            ->from(self::TABLE_NAME)
            ->orderBy('name', 'ASC')
            ->execute()
            ->fetchAllAssociative();

        return is_array($result) ? $result : array();
    }

    public function updateRecord(array $fields): string
    {
        $id = $fields['id'] ?? '';

        if(empty($id)){
            $fields['id'] = Uuid::uuid4()->toString();
            $this->insert($fields);
        } else {
            $this->update($fields['id'], $fields);
            $id = $fields['id'];
        }
        return $id;
    }

    public function update(string $id, array $fields):void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->update(self::TABLE_NAME);
        foreach ($fields as $name => $value){
            $qb->set($name,':' . $name);
            $qb->setParameter(':'.$name, $value);
        }
        $qb->where('id = :id');
        $qb->setParameter(':id', $id);
        $qb->execute();
    }

    public function insert(array $fields):void
    {
        $qb = $this->connection->createQueryBuilder();


        $qb->insert(self::TABLE_NAME);
        foreach ($fields as $name => $value){
            $qb->setValue($name,':' . $name);
            $qb->setParameter(':'.$name, $value);
        }

        $qb->execute();
    }


    public function delete(string $id):void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->delete(self::TABLE_NAME)
            ->where('id = :id')
            ->setParameter(':id', $id)
            ->execute();
    }


}
