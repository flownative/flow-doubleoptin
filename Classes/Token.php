<?php
namespace Flownative\DoubleOptIn;

/*                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license                                           *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A Token used in the double opt-in process.
 *
 * To generate a Token instance, the generateToken() method of the Helper class should be used.
 */
class Token {

	/**
	 * @Flow\InjectConfiguration(path="presets")
	 * @var array
	 */
	protected $presets;

	/**
	 * @var string
	 */
	protected $hash;

	/**
	 * @var string
	 */
	protected $identifier;

	/**
	 * @var string
	 */
	protected $presetName;

	/**
	 * @var array
	 */
	protected $meta;

	/**
	 * Construct a Token instance - use generateToken() in Helper instead.
	 *
	 * @param string $hash
	 * @param string $identifier
	 * @param string $presetName
	 * @param array $meta
	 */
	public function __construct($hash, $identifier, $presetName, array $meta = []) {
		$this->hash = $hash;
		$this->identifier = $identifier;
		$this->presetName = $presetName;
		$this->meta = $meta;
	}

	/**
	 * Returns the hash of this Token.
	 *
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Returns the identifier stored in this Token.
	 *
	 * It can be freely chosen to reflect whatever makes sense in the customer domain.
	 *
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * Returns the configuration preset that was assigned to this Token.
	 *
	 * @return array
	 */
	public function getPreset() {
		return isset($this->presets[$this->presetName]) ? $this->presets[$this->presetName] : [];
	}

	/**
	 * Returns the meta data that was assigned to this Token.
	 *
	 * @return array
	 */
	public function getMeta() {
		return $this->meta;
	}

}