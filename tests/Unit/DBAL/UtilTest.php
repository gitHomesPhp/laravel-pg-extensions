<?php

namespace Umbrellio\Postgres\Tests\Unit\DBAL;

use Doctrine\DBAL\Driver\OCI8\OCI8Statement;
use Umbrellio\Postgres\Tests\TestCase;

class UtilTest extends TestCase
{
    /**
     * @return mixed[][]
     */
    public static function dataConvertPositionalToNamedParameters() : iterable
    {
        return [
            [
                'SELECT name FROM users WHERE id = ?',
                'SELECT name FROM users WHERE id = :param1',
                [1 => ':param1'],
            ],
            [
                'SELECT name FROM users WHERE id = ? AND status = ?',
                'SELECT name FROM users WHERE id = :param1 AND status = :param2',
                [1 => ':param1', 2 => ':param2'],
            ],
            [
                "UPDATE users SET name = '???', status = ?",
                "UPDATE users SET name = '???', status = :param1",
                [1 => ':param1'],
            ],
            [
                "UPDATE users SET status = ?, name = '???'",
                "UPDATE users SET status = :param1, name = '???'",
                [1 => ':param1'],
            ],
            [
                "UPDATE users SET foo = ?, name = '???', status = ?",
                "UPDATE users SET foo = :param1, name = '???', status = :param2",
                [1 => ':param1', 2 => ':param2'],
            ],
            [
                'UPDATE users SET name = "???", status = ?',
                'UPDATE users SET name = "???", status = :param1',
                [1 => ':param1'],
            ],
            [
                'UPDATE users SET status = ?, name = "???"',
                'UPDATE users SET status = :param1, name = "???"',
                [1 => ':param1'],
            ],
            [
                'UPDATE users SET foo = ?, name = "???", status = ?',
                'UPDATE users SET foo = :param1, name = "???", status = :param2',
                [1 => ':param1', 2 => ':param2'],
            ],
            [
                'SELECT * FROM users WHERE id = ? AND name = "" AND status = ?',
                'SELECT * FROM users WHERE id = :param1 AND name = "" AND status = :param2',
                [1 => ':param1', 2 => ':param2'],
            ],
            [
                "SELECT * FROM users WHERE id = ? AND name = '' AND status = ?",
                "SELECT * FROM users WHERE id = :param1 AND name = '' AND status = :param2",
                [1 => ':param1', 2 => ':param2'],
            ],
        ];
    }

    /**
     * @param mixed[] $expectedOutputParamsMap
     *
     * @dataProvider dataConvertPositionalToNamedParameters
     */
    public function testConvertPositionalToNamedParameters(string $inputSQL, string $expectedOutputSQL, array $expectedOutputParamsMap) : void
    {
        [$statement, $params] = OCI8Statement::convertPositionalToNamedPlaceholders($inputSQL);

        self::assertEquals($expectedOutputSQL, $statement);
        self::assertEquals($expectedOutputParamsMap, $params);
    }
}
