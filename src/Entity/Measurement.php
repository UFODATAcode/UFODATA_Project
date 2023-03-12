<?php

namespace App\Entity;

use App\Contract\ResourceInterface;
use App\Contract\UserInterface;
use App\Enum\MeasurementType;
use App\Repository\Entity\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'rfs' => RadioFrequencySpectrum::class,
    'mcdata' => MissionControlData::class,
    'mcadsb' => MissionControlAdsBFlightTracking::class,
    'mcwthr' => MissionControlWeather::class,
    'video' => Video::class,
])]
#[Vich\Uploadable]
abstract class Measurement extends AbstractEntity implements ResourceInterface
{
    public const NAME_MAX_LENGTH = 64;

    #[ORM\ManyToOne(targetEntity: Observation::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Observation $observation;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private UserInterface $provider;

    #[Vich\UploadableField(
        mapping: 'measurement',
        fileNameProperty: 'originalFileMetadata.name',
        size: 'originalFileMetadata.size',
        mimeType: 'originalFileMetadata.mimeType',
        originalName: 'originalFileMetadata.originalName',
    )]
    private File $originalFile;

    #[ORM\Column(type: 'string', length: self::NAME_MAX_LENGTH, nullable: true)]
    private ?string $name;

    #[ORM\Embedded(class: EmbeddedFile::class)]
    private EmbeddedFile $originalFileMetadata;

    #[ORM\Column(type: 'datetimetz_immutable')]
    private \DateTimeImmutable $providedAt;

    #[ORM\Column(type: 'datetimetz')]
    private \DateTimeInterface $updatedAt;

    public function __construct(
        UuidInterface $uuid,
        Observation $observation,
        UserInterface $provider,
        File $originalFile,
        ?string $name = null
    ) {
        parent::__construct($uuid);

        $this->observation = $observation;
        $this->provider = $provider;
        $this->originalFile = $originalFile;
        $this->name = $name;
        $this->originalFileMetadata = new EmbeddedFile();
        $this->providedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getObservation(): Observation
    {
        return $this->observation;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     */
    public function setOriginalFile(?File $originalFile = null)
    {
        $this->originalFile = $originalFile;

        if (null !== $originalFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getOriginalFile(): ?File
    {
        return $this->originalFile;
    }

    public function setOriginalFileMetadata(EmbeddedFile $originalFileMetadata): void
    {
        $this->originalFileMetadata = $originalFileMetadata;
    }

    public function getOriginalFileMetadata(): ?EmbeddedFile
    {
        return $this->originalFileMetadata;
    }

    public function getProvider(): UserInterface
    {
        return $this->provider;
    }

    public function getData(): array
    {
        return \array_merge(
            parent::getData(),
            [
                'observation' => $this->getObservation()->getUuid(),
                'provider' => $this->getProvider()->getUuid(),
                'name' => $this->getName(),
                'originalFileMetadata' => [
                    'name' => $this->getOriginalFileMetadata()->getName(),
                    'originalName' => $this->getOriginalFileMetadata()->getOriginalName(),
                    'size' => $this->getOriginalFileMetadata()->getSize(),
                    'dimensions' => $this->getOriginalFileMetadata()->getDimensions(),
                ],
                'providedAt' => $this->providedAt->format('c'),
                'updatedAt' => $this->updatedAt->format('c'),
            ]
        );
    }

    abstract public function getType(): MeasurementType;
}
