<?php

/*
 * iTXTech SimpleSwFw
 *
 * Copyright (C) 2018-2019 iTX Technologies
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace iTXTech\SimpleSwFw\Http\Page;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

abstract class AbstractPage{
	public const IP_ADDR_HEADER = "X-Real-IP";
	public static $STATUS_PAGE_HEADER = "iTXTech SimpleSwFw<br>";

	public static function getClientIp(Request $request){
		return $request->header[strtolower(self::IP_ADDR_HEADER)] ?? $request->server["remote_addr"];
	}

	public static function sendJsonData(Response $response, array $data){
		$response->header("Content-Type", "application/json");
		$response->end(json_encode($data));
	}

	public abstract static function process(Request $request, Response $response, Server $server);

	public static function status(int $code, Response $response, string $additionalMsg = ""){
		$response->header("Content-Type", "text/html");
		$response->status($code);
		switch($code){
			case 403:
				$msg = "403 Forbidden";
				break;
			case 404:
				$msg = "404 Not Found";
				break;
			default:
				$msg = (string) $code;
				break;
		}
		$response->end(self::$STATUS_PAGE_HEADER . $msg . (($additionalMsg !== "") ? "<br> $additionalMsg" : ""));
	}

	protected static function generateUrl(string $uri, array $get){
		$payload = '?';
		foreach($get as $key => $content){
			$payload .= urlencode($key) . '=' . urlencode($content) . '&';
		}
		return $uri . $payload;
	}
}
