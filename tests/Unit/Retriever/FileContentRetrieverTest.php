<?php

declare(strict_types=1);

namespace BehatDoctrineFixtures\Tests\Unit\Database;

use BehatNelmioDescriber\Retriever\FileContentRetriever;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileContentRetrieverTest extends TestCase
{
    /**
     * @dataProvider getFileOptionsProvider
     */
    public function testGetFileReferenceContent(
        string $filename,
        string $anchor,
        ?string $expectedContent
    ): void
    {
        $fileContentRetriever = new FileContentRetriever('tests/Fixtures/Feature');

        $content = $fileContentRetriever->getFileReferenceContent($filename, $anchor);

        self::assertEquals($expectedContent, $content);
    }

    public function getFileOptionsProvider(): \Generator
    {
        yield [
            'test_feature.feature',
            'firstSuccess',
            "{\n    \"test\": true\n}\n"
        ];
        yield [
            'test_feature.feature',
            'secondSuccess',
            "{\n    \"test\": false\n}\n"
        ];
        yield [
            'test_feature.feature',
            'randomFlag',
            null
        ];
    }


}
