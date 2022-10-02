<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Tests\Functional\Describer;

use BehatNelmioDescriber\Tests\Functional\App\TestKernel;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class BehatDescriberTest extends KernelTestCase
{
    private RequestStack $requestStack;

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->requestStack = self::getContainer()->get(RequestStack::class);
    }

    /**
     * @dataProvider getDescriptionProvider
     */
    public function test(
        string $path,
        string $expectedDescription
    ): void
    {
        $request = Request::create('/api/doc.first_area.json', Request::METHOD_GET);
        $response = $this->handleRequestWithKernel($request);

        $responseArray = json_decode($response->getContent(), true);

        $description = $this->getDescriptionFromJson($path, $responseArray);
        self::assertSame($expectedDescription, $description);
    }

    private function getDescriptionFromJson(string $path, array $responseArray): string {
        return $responseArray['paths'][$path]['get']['description'];
    }

    private function handleRequestWithKernel(Request $request): Response
    {
        $response = self::createKernel()->handle($request);

        $this->requestStack->pop();
        self::createKernel()->terminate($request, $response);

        return $response;
    }

    public function getDescriptionProvider(): Generator
    {
        yield [
            '/api/first-area/test-route',
            "<details><summary>**success**</summary>\n```\n[Invalid reference]\n```\n</details>\n<details><summary>**failure**</summary>\n```\n[Invalid reference]\n```\n</details>\n"
        ];
    }
}
