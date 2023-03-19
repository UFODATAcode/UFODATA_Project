<?php

namespace App\Entity;

use App\Repository\Entity\MeasurementMetadataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementMetadataRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'video' => VideoMetadata::class,
])]
abstract class MeasurementMetadata extends AbstractEntity
{
}