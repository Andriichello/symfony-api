<?php

namespace App\Tests\Repository;

use App\Enum\FilterFlag;
use App\Enum\FilterOperator;
use App\Repository\AuthorRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthorRepositoryTest.
 *
 * @package App\Tests\Repository
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
final class AuthorRepositoryTest extends TestCase
{
    protected static function getKernelClass(): string
    {
        return 'App\Kernel';
    }

    public function testResolveFilters(): void
    {
        $array = [
            'name' => [
                'eq' => 'John',
            ],
            'alias' => [
                'not' => 'Walker',
            ],
        ];

        $repo = new AuthorRepository($this->createMock('Doctrine\Persistence\ManagerRegistry'));
        $filters = $repo->resolveFilters($array);

        $this->assertEquals($filters[0]['name'], 'name');
        $this->assertEquals($filters[0]['operator'], FilterOperator::EQ);

        $this->assertEmpty($filters[0]['flags'] ?? []);

        $this->assertEquals($filters[1]['name'], 'alias');
        $this->assertEquals($filters[1]['operator'], FilterOperator::EQ);

        $this->assertNotEmpty($filters[1]['flags']);
        $this->assertEquals($filters[1]['flags'][0], FilterFlag::NOT);

        // throws an exception because there must be only one operator
        $array = [
            'name' => [
                'eq' => [
                    'eq' => 'John'
                ],
            ],
        ];

        $this->expectException(InvalidArgumentException::class);
        $repo->resolveFilters($array);
    }
}
