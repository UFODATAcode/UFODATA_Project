<?php

namespace App\Serializer;

use Nelmio\ApiDocBundle\PropertyDescriber\PropertyDescriberInterface;
use OpenApi\Annotations\Schema;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\PropertyInfo\Type;

class UuidInterfacePropertyDescriber implements PropertyDescriberInterface
{
    /**
     * @inheritDoc
     */
    public function describe(array $types, Schema $property, array $groups = null)
    {
        $property->type = 'string';
        $property->format = 'uuid';
    }

    /**
     * @inheritDoc
     */
    public function supports(array $types): bool
    {
        foreach ($types as $type) {
            if (Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()
                && \is_a($type->getClassName(), UuidInterface::class, true)
            ) {
                return true;
            }
        }

        return false;
    }
}