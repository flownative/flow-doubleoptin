<?php
namespace Flownative\DoubleOptIn;

/*
 * This file is part of the Flownative.DoubleOptIn package.
 *
 * (c) 2015, Flownative GmbH
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

/**
 * A Token used in the double opt-in process.
 *
 * To generate a Token instance, the generateToken() method of the Helper class should be used.
 */
class Token
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var array
     */
    protected $preset;

    /**
     * @var array
     */
    protected $meta;

    /**
     * Construct a Token instance - use generateToken() in Helper instead.
     *
     * @param string $hash
     * @param string $identifier
     * @param array $preset
     * @param array $meta
     */
    public function __construct($hash, $identifier, array $preset, array $meta = [])
    {
        $this->hash = $hash;
        $this->identifier = $identifier;
        $this->preset = $preset;
        $this->meta = $meta;
    }

    /**
     * Returns the hash of this Token.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Returns the identifier stored in this Token.
     *
     * It can be freely chosen to reflect whatever makes sense in the customer domain.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Returns the configuration preset that was assigned to this Token.
     *
     * @return array
     */
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * Returns the meta data that was assigned to this Token.
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
