<?php

declare(strict_types=1);

namespace Th3Mouk\RethinkDB\Translator;

use r\Cursor;

class Translate
{
    public const NORMALIZER_OPTIONS = ['dateTimeToString' => true];

    /**
     * @param array<string, bool> $options
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification
     */
    public static function cursorToAssociativeArray(Cursor $cursor, array $options = []): array
    {
        return self::arrayOfArrayObjectToAssociativeArray($cursor->toArray(), $options);
    }

    /**
     * @param array<\ArrayObject> $arrayOfArrayObject
     * @param array<string, bool> $options
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification
     */
    public static function arrayOfArrayObjectToAssociativeArray(array $arrayOfArrayObject, array $options = []): array
    {
        array_walk($arrayOfArrayObject, static function (\ArrayObject &$entry) use ($options): void {
            $entry = self::normalizeValues($entry->getArrayCopy(), $options);
        });

        return $arrayOfArrayObject;
    }

    /**
     * @param array<string, bool> $options
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification
     */
    public static function arrayObjectToAssociativeArray(\ArrayObject $arrayObject, array $options = []): array
    {
        return self::normalizeValues($arrayObject->getArrayCopy(), $options);
    }

    /**
     * @param array<string, bool> $options
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableParameterTypeHintSpecification
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableReturnTypeHintSpecification
     */
    public static function normalizeValues(array $values, array $options = []): array
    {
        if (empty($options)) {
            $options = self::NORMALIZER_OPTIONS;
        }

        return array_map(static function ($value) use ($options) {
            if ($value instanceof \ArrayObject) {
                return self::arrayObjectToAssociativeArray($value, $options);
            }

            if (is_array($value)) {
                return self::normalizeValues($value, $options);
            }

            if ($options['dateTimeToString'] && $value instanceof \DateTime) {
                return $value->format(DATE_ISO8601);
            }

            return $value;
        }, $values);
    }
}
