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
                ],
                [
                    'uuid' => ObservationFixtures::OBSERVATION_3_UUID,
                    'name' => ObservationFixtures::OBSERVATION_3_NAME,
                ],
                [
                    'uuid' => ObservationFixtures::OBSERVATION_4_UUID,
                    'name' => ObservationFixtures::OBSERVATION_4_NAME,
                ],
            ],
            'pagination' => [
                'limit' => Pagination::DEFAULT_LIMIT,
                'page' => Pagination::FIRST_PAGE,
                'numberOfPages' => Pagination::DEFAULT_NUMBER_OF_PAGES,
            ],
        ]);
    }

    public function iCanLimitReturnedObservationsNumberUsingLimitParameter(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/observations?limit=2');
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
                'limit' => 2,
                'page' => 1,
                'numberOfPages' => 2,
            ],
        ]);
    }

    public function iCanLimitReturnedObservationsNumberUsingLimitParameterAndFetchSecondPage(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->setBearerTokenForUser(ObservationFixtures::USER_1_EMAIL);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/observations?limit=2&page=2');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                [
                    'uuid' => ObservationFixtures::OBSERVATION_3_UUID,
                    'name' => ObservationFixtures::OBSERVATION_3_NAME,
                ],
                [
                    'uuid' => ObservationFixtures::OBSERVATION_4_UUID,
                    'name' => ObservationFixtures::OBSERVATION_4_NAME,
                ]
            ],
            'pagination' => [
                'limit' => 2,
                'page' => 2,
                'numberOfPages' => 2,
            ],
        ]);
    }
}
