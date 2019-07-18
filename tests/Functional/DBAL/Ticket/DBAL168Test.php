<?php

namespace Umbrellio\Postgres\Tests\Functional\DBAL\Ticket;

use Doctrine\DBAL\Schema\Table;
use Umbrellio\Postgres\Tests\Functional\DbalFunctionalTestCase;

/**
 * @group DBAL-168
 */
class DBAL168Test extends DbalFunctionalTestCase
{
    public function testDomainsTable() : void
    {
        if ($this->connection->getDatabasePlatform()->getName() !== 'postgresql') {
            $this->markTestSkipped('PostgreSQL only test');
        }

        $table = new Table('domains');
        $table->addColumn('id', 'integer');
        $table->addColumn('parent_id', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('domains', ['parent_id'], ['id']);

        $this->connection->getSchemaManager()->createTable($table);
        $table = $this->connection->getSchemaManager()->listTableDetails('domains');

        self::assertEquals('domains', $table->getName());
    }
}
