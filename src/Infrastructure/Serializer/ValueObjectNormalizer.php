<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Serializer;

use Aeviiq\ValueObject\AbstractValue;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

final class ValueObjectNormalizer implements ContextAwareNormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof AbstractValue;
    }

    /**
     * @param mixed $object
     *
     * @return scalar
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        /** @var AbstractValue<scalar> $object */
        return $object->get();
    }
}
