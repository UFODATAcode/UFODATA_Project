<?php

namespace App\Tests\Api;

use App\DataFixtures\ObservationFixtures;
use App\Entity\Observation;
use App\Entity\User;
use App\Tests\ApiTester;
use Symfony\Component\HttpFoundation\Response;

class AddObservationCest
{
    public function canNotAddObservationWhenNotLoggedIn(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        try {
            $I->sendPost('/observations', [
                'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
                'name' => 'UAP at my place',
            ]);
        } catch (\Throwable $e) {}
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    public function canAddObservationWhenLoggedIn(ApiTester $I): void
    {
        $I->loadFixtures(ObservationFixtures::class);
        $I->amLoggedInAs($I->grabEntityFromRepository(User::class, ['email' => 'test@test.com']));
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/observations', [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'UAP at my place',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $I->seeInRepository(Observation::class, [
            'uuid' => '9ef30757-8f8e-4473-b326-b2ee487aefee',
            'name' => 'UAP at my place',
        ]);
    }
}
