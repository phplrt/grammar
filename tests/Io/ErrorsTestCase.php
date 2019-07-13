<?php
/**
 * This file is part of Phplrt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Phplrt\Tests\Io;

use Phplrt\Io\Exception\NotFoundException;
use Phplrt\Io\Exception\NotReadableException;
use Phplrt\Io\File;

/**
 * Class ErrorsTestCase
 */
class ErrorsTestCase extends TestCase
{
    /**
     * @return void
     * @throws NotReadableException
     */
    public function testNotFound(): void
    {
        $file = __DIR__ . '/not-exists.txt';

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('File "' . $file . '" not found');

        File::fromPathname($file);
    }

    /**
     * @requires OSFAMILY Linux|BSD|Darwin
     * @return void
     * @throws NotReadableException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\SkippedTestError
     */
    public function testNotReadable(): void
    {
        $file = __DIR__ . '/resources/locked';

        $this->expectException(NotReadableException::class);
        $this->expectExceptionMessage('Can not read the file "' . $file . '": Permission denied');

        \file_put_contents($file, '');
        \chmod($file, 0333);

        File::fromPathname($file);

        @\chmod($file, 0777);
        @\unlink($file);
    }

    /**
     * @return string
     */
    protected function getPathname(): string
    {
        return __DIR__ . '/resources/example.txt';
    }
}
