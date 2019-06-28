<?php

declare(strict_types=1);

namespace Th3Mouk\RethinkDB\Translator;

use r\Cursor;

class Translate
{
    /** @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification */
    public static function cursorToAssociativeArray(Cursor $cursor): array
    {
        return self::arrayOfArrayObjectToAssociativeArray($cursor->toArray());
    }

    /**
     * @param array<\ArrayObject> $arrayOfArrayObject
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification
     */
    public static function arrayOfArrayObjectToAssociativeArray(array $arrayOfArrayObject): array
    {
        array_walk($arrayOfArrayObject, static function (\ArrayObject &$entry): void {
            $entry = self::arrayObjectToAssociativeArray($entry);
        });

        return $arrayOfArrayObject;
    }

    /** @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification */
    public static function arrayObjectToAssociativeArray(\ArrayObject $arrayObject): array
    {
        return self::normalizeValues($arrayObject->getArrayCopy());
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableParameterTypeHintSpecification
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification
     */
    public static function normalizeValues(array $values): array
    {
        return array_map(static function ($value) {
            /** @var \ArrayObject $value */
            if (is_a($value, \ArrayObject::class)) {
                return self::normalizeValues($value->getArrayCopy());
            }

            /** @var \DateTime $value */
            if (is_a($value, \DateTime::class)) {
                return $value->format(DATE_ISO8601);
            }

            return $value;
        }, $values);
    }
}
