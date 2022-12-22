<?php

namespace App\Command;

use App\Contract\AddMeasurementCommandInterface;
use App\Contract\FileUploadInterface;
use App\Contract\SynchronousCommandInterface;
use App\Contract\UserInterface;
use App\Entity\Measurement;
use App\Entity\Observation;
use App\Enum\MeasurementType;
use App\Validator\ResourceExists;
use App\Validator\ResourceNotExists;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

class AddMeasurementCommand implements AddMeasurementCommandInterface, FileUploadInterface, SynchronousCommandInterface
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
    public MeasurementType $type;

    #[Assert\NotNull]
    #[Assert\File(
        mimeTypes: ['text/plain'], //TODO: move measurement allowed mime types to dedicated class
    )]
    //TODO: add constraint to validate if this measurement type can be parsed/is valid measurement type
    public UploadedFile $file;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: Measurement::NAME_MAX_LENGTH)]
    public ?string $name = null;

    #[Ignore]
    public UserInterface $provider;

    /**
     * @inheritdoc
     */
    public static function getFilesNames(): array
    {
        return [
            'file',
        ];
    }

    public function getObservationUuid(): UuidInterface
    {
        return $this->observationUuid;
    }

    public function getType(): MeasurementType
    {
        return $this->type;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }
}
