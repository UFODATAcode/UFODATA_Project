<?php

namespace App\Repository\Entity;

use App\Contract\MeasurementMetadataRepositoryInterface;
use App\Entity\VideoMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MeasurementMetadataRepository extends ServiceEntityRepository implements MeasurementMetadataRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoMetadata::class);
    }

    public function add(VideoMetadata $observation, bool $flush = false): void
    {
        $this->getEntityManager()->persist($observation);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VideoMetadata $observation, bool $flush = false): void
    {
        $this->getEntityManager()->remove($observation);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
