<?php

namespace App\Serializer;

use Nelmio\ApiDocBundle\PropertyDescriber\PropertyDescriberInterface;
use OpenApi\Annotations\Schema;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyInfo\Type;

class UploadedFilePropertyDescriber implements PropertyDescriberInterface
{
    /**
     * @inheritDoc
     */
    public function describe(array $types, Schema $property, array $groups = null)
    {
        $property->type = 'string';
        $property->format = 'binary';
    }

    /**
     * @inheritDoc
     */
    public function supports(array $types): bool
    {
        foreach ($types as $type) {
            if (Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()
                && \is_a($type->getClassName(), UploadedFile::class, true)
            ) {
                return true;
            }
        }

        return false;
    }
}