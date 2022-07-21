<?php

namespace App\Command;

use App\Contract\CommandInterface;
use App\Contract\FileUploadInterface;
use App\Entity\Measurement;
use App\Entity\Observation;
use App\Enum\MeasurementType;
use App\Validator\ResourceExists;
use App\Validator\ResourceNotExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class AddMeasurementCommand implements CommandInterface, FileUploadInterface
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceNotExists(entityClassName: Measurement::class)]
    public UuidInterface $uuid;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ResourceExists(entityClassName: Observation::class)]
    public UuidInterface $observationUuid;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [MeasurementType::class, 'values'])]
    public MeasurementType $measurementType;

    #[Assert\NotNull]
    #[Assert\File(
        mimeTypes: ['text/plain'], //TODO: move measurement allowed mime types to dedicated class
    )]
    //TODO: add constraint to validate if this measurement type can be parsed/is valid measurement type
    public UploadedFile $measurement;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: Measurement::NAME_MAX_LENGTH)]
    public ?string $name = null;

    /**
     * @inheritdoc
     */
    public static function getFilesNames(): array
    {
        return [
            'measurement',
        ];
    }
}
