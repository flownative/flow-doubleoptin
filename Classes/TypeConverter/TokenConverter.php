<?php
namespace Flownative\DoubleOptIn\TypeConverter;

/*                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license                                           *
 *                                                                        */

use Flownative\DoubleOptIn\Helper;
use Flownative\DoubleOptIn\Token;
use Neos\Flow\Property\TypeConverter\AbstractTypeConverter;

/**
 * Convert a string (tokenHash) into a Token object.
 */
class TokenConverter extends AbstractTypeConverter {

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
	 * @param \Neos\Flow\Property\PropertyMappingConfigurationInterface $configuration
	 *
	 * @return Token|NULL the target type, or NULL if it was not a valid token
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = [], \Neos\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		$doubleOptinHelper = new Helper();
		return $doubleOptinHelper->validateTokenHash($source);
	}
}
