<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Tests\Functional\App\Controller;

use BehatNelmioDescriber\Attributes\BehatFeature;
use BehatNelmioDescriber\Attributes\BehatFeaturesPath;
use BehatNelmioDescriber\Enum\Status;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[BehatFeaturesPath(path: 'api/v2/internal/activation/customer_product_plan/')]
final class TestController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/first-area/test-route",
     *     name="test",
     *     defaults={"_format": "json"},
     *     methods={"GET"}
     * )
     *
     * @ApiDoc\Operation(tags={"TestRounte"})
     *
     * @OA\Parameter(name="parameter", in="header", @OA\Schema(type="string"), required=false)
     *
     */
    #[BehatFeature(status: Status::SUCCESS->value, file: 'list.feature', anchors: [
       'successByOwner',
    ])]
    #[BehatFeature(status: Status::FAILURE, file: 'list.feature', anchors: [
       'productBundleIdNotBlank',
       'productBundleIdNotFound',
       'invalidCustomerId'
    ])]
    public function getHelloWorld(): string {
        return 'Hello World';
    }

}
