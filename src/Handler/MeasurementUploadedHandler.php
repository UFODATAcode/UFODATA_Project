<?php

namespace App\Handler;

use App\Command\AddMeasurementCommand;
use App\Document\Measurement\RadioFrequencySpectrum;
use App\Document\Measurement\RadioFrequencyValue;
use App\Enum\MeasurementType;
use Doctrine\ODM\MongoDB\DocumentManager;

class MeasurementUploadedHandler
{
    public function __construct(
        private readonly DocumentManager $documentManager,
    ) {}

    public function __invoke(AddMeasurementCommand $command): void
    {
        $measurement = match ($command->measurementType) {
            MeasurementType::RadioFrequencySpectrum => new RadioFrequencySpectrum($command->uuid, $command->observationUuid),
        };

        $documentManager = $this->documentManager->getRepository($measurement::class)->getDocumentManager();
        $documentManager->persist($measurement);
        $documentManager->flush();

        $this->addValues($measurement, $command, $documentManager);
    }

    private function addValues(
        RadioFrequencySpectrum $measurement,
        AddMeasurementCommand $command,
        DocumentManager $dm
    ): void {
        $file = $command->measurement->openFile();
        $isFirstLine = true;

        while (!$file->eof()) {
            $line = \current($file->fgetcsv());

            if ($isFirstLine) {
                $measurement->setName(\trim($line));
                $isFirstLine = false;
                continue;
            }

            $date = null;
            $powerValues = null;
            $frequencyValues = null;

            for ($i = 0; $i < 3; ++$i) {
                switch ($i) {
                    case 0:
                        $date = \DateTimeImmutable::createFromFormat(
                            'y-m-d H:i:s',
                            \trim(\str_replace('CPU', '', $line))
                        );
                        break;
                    case 1:
                        $frequencyValues = $file->fgetcsv();

                        if (false === $frequencyValues) {
                            break 3;
                        }

                        \array_shift($frequencyValues);
                        break;
                    case 2:
                        $powerValues = $file->fgetcsv();

                        if (false === $powerValues) {
                            break 3;
                        }

                        \array_shift($powerValues);
                        break;
                }
            }

            foreach ($frequencyValues as $index => $frequencyValue) {
//                $measurement->addValue(new RadioFrequencyValue(
//                    $date,
//                    (float) $frequencyValue,
//                    (float) $powerValues[$index]
//                ));
                $value = new RadioFrequencyValue(
                    $date,
                    (float)$frequencyValue,
                    (float)$powerValues[$index]
                );
//                $dm->persist($measurement);
//                $dm->flush();
                $dm->getRepository(RadioFrequencySpectrum::class)->addMeasurementValue($measurement->getUuid(), $value);
            }
        }
//        $dm->persist($measurement);
//        $dm->flush();
//        file_put_contents(__DIR__.'/tmp.json', json_encode($measurement));
//        die();
    }
}
