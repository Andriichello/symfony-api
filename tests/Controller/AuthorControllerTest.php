<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * Class AuthorControllerTest.
 *
 * @package App\Tests\Controller
 * @author Andrii Prykhodko <andriichello@gmail.com>
 */
final class AuthorControllerTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return 'App\Kernel';
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/author');

        self::assertResponseIsSuccessful();
    }
}
