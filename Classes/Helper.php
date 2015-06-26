<?php
namespace Flownative\DoubleOptIn;

/*                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license                                           *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\SwiftMailer\Message;

/**
 * This class allows to easily build a double opt-in mechanism.
 *
 * You generate a token using generateToken(). This token is stored
 * together with an identifier and can be validated using validateTokenHash()
 * later.
 *
 * Any action (token generation, validation) is logged together with the
 * results, to be able to verify an orderly double opt-in.
 */
class Helper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Cache\Frontend\VariableFrontend
	 */
	protected $tokenCache;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Fluid\View\StandaloneView
	 */
	protected $fluidView;

	/**
	 * @Flow\InjectConfiguration(path="presets")
	 * @var array
	 */
	protected $presets;

	/**
	 * Generate a Token and assign it the given $identifier.
	 *
	 * A $presetName can be given to use a specific configuration preset. The given $meta data
	 * will be stored with the Token and can be retrieved again when validation of the token
	 * is done and was successful.
	 *
	 * @param string $identifier
	 * @param string $presetName
	 * @param array $meta
	 * @return Token
	 */
	public function generateToken($identifier, $presetName = 'default', array $meta = []) {
		$preset = $this->getPreset($presetName);

		$tokenHash = Algorithms::generateRandomToken($preset['tokenLength']);

		$this->tokenCache->set(
			$tokenHash,
			[
				'identifier' => $identifier,
				'presetName' => $presetName,
				'meta' => $meta
			],
			[md5($identifier)],
			$preset['lifetime']
		);

		return new Token($tokenHash, $identifier, $preset, $meta);
	}

	/**
	 * This checks if a given hash is known and still valid before returning the associated Token.
	 *
	 * If no valid Token is found for the hash, NULL is returned.
	 *
	 * @param $tokenHash
	 * @return Token
	 */
	public function validateTokenHash($tokenHash) {
		if (!$this->tokenCache->has($tokenHash)) {
			return NULL;
		}

		$tokenData = $this->tokenCache->get($tokenHash);
		$this->tokenCache->remove($tokenHash);

		return new Token($tokenHash, $tokenData['identifier'], $this->getPreset($tokenData['presetName']), $tokenData['meta']);
	}

	/**
	 * For the given $token an activation link is returned.
	 *
	 * @param Token $token
	 * @return string
	 * @throws InvalidTokenException
	 */
	public function getActivationLink(Token $token) {
		$activationConfiguration = $token->getPreset()['activation'];
		$tokenHash = $token->getHash();

		if ($activationConfiguration['uri'] === NULL) {
			throw new \RuntimeException('Building activation link failed, no uri configuration is set', 1434728943);
		} elseif (is_array($activationConfiguration['uri'])) {
			throw new \RuntimeException('Not yet implemented', 1434710910);
		} elseif (is_string($activationConfiguration['uri'])) {
			return sprintf($activationConfiguration['uri'], $tokenHash);
		} else {
			throw new \RuntimeException('Building activation link failed, uri configuration is invalid (neither array nor string)', 1434732898);
		}
	}

	/**
	 * Sends an activation mail for $token to the given $recipientAddress.
	 *
	 * The mail is built and sent according to the configuration given in the preset assigned to the $token.
	 *
	 * @param string $recipientAddress
	 * @param Token $token
	 * @param array $additionalTemplateVariables
	 * @return int
	 */
	public function sendActivationMail($recipientAddress, Token $token, array $additionalTemplateVariables = array()) {
		$preset = $token->getPreset();
		$activationLink = $this->getActivationLink($token);

		$mail = new Message();
		$mail->setFrom([$preset['mail']['from']['address'] => $preset['mail']['from']['name']])
			->setTo($recipientAddress)
			->setSubject($preset['mail']['subject']);

		$templateVariables = array_merge([
			'activationLink' => $activationLink,
			'recipientAddress' => $recipientAddress,
			'token' => $token,
			'meta' => $token->getMeta()
		], $additionalTemplateVariables);

		$this->fluidView->setTemplatePathAndFilename($preset['mail']['message']['plaintext']);
		$this->fluidView->assignMultiple($templateVariables);
		$mail->setBody($this->fluidView->render(), 'text/plain');

		if (isset($preset['mail']['html'])) {
			$this->fluidView->setTemplatePathAndFilename($preset['mail']['message']['html']);
			$this->fluidView->assignMultiple($templateVariables);
			$mail->setBody($this->fluidView->render(), 'text/html');
		}
		return $mail->send();
	}

	/**
	 * Returns the preset with the given $presetName.
	 *
	 * The preset is merged with the values of the 'default' preset before being returned.
	 *
	 * @param string $presetName
	 * @return array
	 * @throws UnknownPresetException
	 */
	protected function getPreset($presetName) {
		if (!isset($this->presets[$presetName])) {
			throw new UnknownPresetException(sprintf('Preset %s is not configured.', $presetName), 1434730924);
		}

		$default = $this->presets['default'];
		$preset = $this->presets[$presetName];

		return Arrays::arrayMergeRecursiveOverrule($default, $preset, TRUE);
	}
}