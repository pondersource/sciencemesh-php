<?php
/**
 * @copyright Copyright (c) 2021, PonderSource
 *
 * @author Yvo Brevoort <yvo@pondersource.nl>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\ScienceMesh;

use OCP\IConfig;

/**
 * Class RevaHttpClient
 *
 * This class is a helper to handle the outbound HTTP connections from Nextcloud to Reva
 *
 * @package OCA\ScienceMesh\RevaHttpClient
 */
class RevaHttpClient {
	private $client;
	private $revaUrl;
	private $revaUser;
	private $revaLoopbackSecret;
		
	/**
	 * RevaHttpClient constructor.
	 *
	 */
	public function __construct(IConfig $config) {
		$this->config = $config;
		$this->serverConfig = new \OCA\ScienceMesh\ServerConfig($config);
		$this->revaUrl = $this->serverConfig->getIopUrl();
		$this->revaLoopbackSecret = $this->serverConfig->getRevaLoopbackSecret();
		$this->curlDebug = true;
	}

	private function curlGet($url, $user, $params = []) {
		$ch = curl_init();
		if (sizeof($params)) {
			$url .= "?" . http_build_query($params);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->revaUser && $this->revaLoopbackSecret) {
			curl_setopt($ch, CURLOPT_USERPWD, $user.":".$this->revaLoopbackSecret);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		}

		if ($this->curlDebug) {
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			$streamVerboseHandle = fopen('php://temp', 'w+');
			curl_setopt($ch, CURLOPT_STDERR, $streamVerboseHandle);
		}
		
		$output = curl_exec($ch);

		if ($this->curlDebug) {
			rewind($streamVerboseHandle);
			$verboseLog = stream_get_contents($streamVerboseHandle);
			$output = $verboseLog . $output;
		}
		
		return $output;
	}
	private function curlPost($url, $user, $params = []) {
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params, JSON_PRETTY_PRINT));
		if ($this->revaLoopbackSecret) {
			curl_setopt($ch, CURLOPT_USERPWD, $user.":".$this->revaLoopbackSecret);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		}
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		error_log('curl output:' . var_export($output, true) . ' info: ' . var_export($info, true));
		curl_close($ch);
		return $output;
	}

	private function revaGet($method, $user, $params = []) {
		$url = $this->revaUrl . $method;
		return $this->curlGet($url, $user, $params);
	}
		
	private function revaPost($method, $user, $params = []) {
		$url = $this->revaUrl . $method;
		return $this->curlPost($url, $user, $params);
	}

	public function createShare($user, $params) {
		if (!isset($params['sourcePath'])) {
			throw new \Exception("Missing sourcePath", 400);
		}
		if (!isset($params['targetPath'])) {
			throw new \Exception("Missing targetPath", 400);
		}
		if (!isset($params['type'])) {
			throw new \Exception("Missing type", 400);
		}
		if (!isset($params['recipientUsername'])) {
			throw new \Exception("Missing recipientUsername", 400);
		}
		if (!isset($params['recipientHost'])) {
			throw new \Exception("Missing recipientHost", 400);
		}
		$params["loginType"] = "basic";
		$params["loginUsername"] = $user;
		$params["loginPassword"] = $this->revaLoopbackSecret;
		return $this->revaPost('ocm/send', $user, $params);
	}

	public function ocmProvider() {
		return $this->revaGet('ocm/ocm-provider');
	}

	public function findAcceptedUsers($userId) {
		$users = $this->revaPost('ocm/invites/find-accepted-users', $userId);
		return $users;
	}

	public function getAcceptTokenFromReva($providerDomain, $token, $userId) {
		$tokenFromReva = $this->revaPost('ocm/invites/forward', $userId, [
			'providerDomain' => $providerDomain,
			'token' => $token
		]);
		return $tokenFromReva;
	}

	public function generateTokenFromReva($userId) {
		$tokenFromReva = $this->revaPost('ocm/invites/generate', $userId);
		error_log('Got token from reva!' . $tokenFromReva);
		return json_decode($tokenFromReva, true);
	}
}
