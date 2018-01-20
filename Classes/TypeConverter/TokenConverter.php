<?php
namespace Flownative\DoubleOptIn\TypeConverter;

/*
 * This file is part of the Flownative.DoubleOptIn package.
 *
 * (c) 2015, Flownative GmbH
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Flownative\DoubleOptIn\Helper;
use Flownative\DoubleOptIn\Token;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Flow\Property\TypeConverter\AbstractTypeConverter;

/**
 * Convert a string (tokenHash) into a Token object.
 */
class TokenConverter extends AbstractTypeConverter
{
    /**
     * @var array
     */
    protected $sourceTypes = ['string'];

    /**
     * @var string
     */
    protected $targetType = 'Flownative\DoubleOptIn\Token';

    /**
     * Actually convert from $source to $targetType, taking into account the fully
     * built $convertedChildProperties and $configuration.
     *
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     * @return Token|null the target type, or NULL if it was not a valid token
     * @throws \Flownative\DoubleOptIn\UnknownPresetException
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {
        $doubleOptInHelper = new Helper();

        return $doubleOptInHelper->validateTokenHash($source);
    }
}
