<?php
namespace Flownative\DoubleOptIn;

/*                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license                                           *
 *                                                                        */

/**
 * This exception is thrown when an invalid token (unknown or expired) is passed into any of the methods that
 * expect a token.
 */
class InvalidTokenException extends \Exception {
}