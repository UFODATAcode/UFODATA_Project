<?php

namespace App\Tests\Api;

use App\DataFixtures\ObservationFixtures;
use App\ValueObject\Pagination;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class GetObservationsCest
{
    public function iCanNotGetObservationsWhenIAmNotNotAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/observations');
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function iCanGetObservationsWhenIAmAuthorized(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/observations');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => ObservationFixtures::OBSERVATION_1_UUID,
                    'name' => ObservationFixtures::OBSERVATION_1_NAME,
                ],
                [
                    'uuid' => ObservationFixtures::OBSERVATION_2_UUID,
                    'name' => ObservationFixtures::OBSERVATION_2_NAME,
                ]
            ],
            'pagination' => [
                'limit' => Pagination::DEFAULT_LIMIT,
                'page' => Pagination::FIRST_PAGE,
            ],
        ]);
    }
}
