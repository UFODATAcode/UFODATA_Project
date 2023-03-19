<?php

namespace App\Contract;

use App\Entity\VideoMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<VideoMetadata>
 *
 * @method VideoMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoMetadata[]    findAll()
 * @method VideoMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method VideoMetadata|null findOneByUuid(UuidInterface $uuid)
 * @method VideoMetadata|null add(VideoMetadata $measurementMetadata, bool $flush = false)
 * @method VideoMetadata|null remove(VideoMetadata $measurementMetadata, bool $flush = false)
 */
interface MeasurementMetadataRepositoryInterface
{

}
