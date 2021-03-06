<?php

/*
 * iTXTech FlashDetector
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

namespace iTXTech\FlashDetector\Decoder;

use iTXTech\FlashDetector\Constants;
use iTXTech\FlashDetector\FlashDetector;
use iTXTech\FlashDetector\FlashInfo;
use iTXTech\FlashDetector\Property\Classification;
use iTXTech\SimpleFramework\Util\StringUtil;

//There is no datasheet or guide available!
//Let's guess
class SanDisk extends Decoder{
	public const CELL_LEVEL = [
		"C" => 3,
		"F" => 2,
		"G" => 2,
		"H" => 2,
		"I" => 3,
		"M" => 2,
		"N" => 3,
		"Q" => 2,
	];

	public static function getName() : string{
		return Constants::MANUFACTURER_SANDISK;
	}

	public static function check(string $partNumber) : bool{
		if(StringUtil::startsWith($partNumber, "SD")){
			return true;
		}
		return false;
	}

	public static function decode(string $partNumber) : FlashInfo{
		$flashInfo = (new FlashInfo($partNumber))->setManufacturer(self::getName());
		$partNumber = substr($partNumber, 2);//remove SD
		if(substr($partNumber, 0, 2) === "IN"){
			return $flashInfo->setType(Constants::NAND_TYPE_INAND)
				->setExtraInfo([Constants::UNSUPPORTED_REASON => Constants::SANDISK_INAND_NOT_SUPPORTED]);
		}elseif(substr($partNumber, 0, 2) === "IS"){
			return $flashInfo->setType(Constants::NAND_TYPE_INAND)
				->setExtraInfo([Constants::UNSUPPORTED_REASON => Constants::SANDISK_INAND_NOT_SUPPORTED]);
		}

		$flashInfo->setType(Constants::NAND_TYPE_NAND)
			->setPackage(self::getOrDefault(self::shiftChars($partNumber, 1), [
				"T" => "TSOP",
				"Y" => "BGA",
				"Z" => "LGA",
				//W ?
			]));
		$partNumber = substr($partNumber, 1);//remove N for NAND
		$flashInfo->setProcessNode(self::getOrDefault(self::shiftChars($partNumber, 1), [
			//F, G, H .... unknown
			"A" => "3D T22",
			"B" => "3D T23",
			"L" => "56 nm",
			"M" => "43 nm",
			"N" => "32 nm",
			"P" => "24 nm",
			"Q" => "19 nm",
			"R" => "1y nm",
			"S" => "15 nm",
		]));

		$cell = self::shiftChars($partNumber, 1);
		$flashInfo->setCellLevel(self::getOrDefault($cell, self::CELL_LEVEL))
			->setClassification(new Classification(
				Classification::UNKNOWN_PROP, Classification::UNKNOWN_PROP, Classification::UNKNOWN_PROP,
				self::getOrDefault(self::shiftChars($partNumber, 1), [
					"A" => 1,
					"B" => 2,
					"C" => 4,
					"D" => 8,
				], -1)))
			->setVoltage(self::getOrDefault(self::shiftChars($partNumber, 1), [
				"H" => "2.7V~3.6V"
			]))
			->setDeviceWidth(self::getOrDefault(self::shiftChars($partNumber, 1), [
				"E" => 8,
				"S" => 16
			], -1));

		if(StringUtil::startsWith($partNumber, "M")){
			$flashInfo->setExtraInfo([
				Constants::LEAD_FREE => true,
			]);
			$partNumber = substr($partNumber, 1);
		}
		if(StringUtil::startsWith($partNumber, "-")){
			$partNumber = substr($partNumber, 1);
			$flashInfo->setDensity(self::matchFromStart($partNumber, [
				"1024" => Constants::DENSITY_GBITS, //128M
				"2048" => 2 * Constants::DENSITY_GBITS, //256M
				"4096" => 4 * Constants::DENSITY_GBITS,
				"001G" => 8 * Constants::DENSITY_GBITS,
				"002G" => 16 * Constants::DENSITY_GBITS,
				"004G" => 32 * Constants::DENSITY_GBITS,
				"008G" => 64 * Constants::DENSITY_GBITS,
				"016G" => 128 * Constants::DENSITY_GBITS,
				"032G" => 256 * Constants::DENSITY_GBITS,
				"064G" => 512 * Constants::DENSITY_GBITS,
				"128G" => Constants::DENSITY_TBITS,
				"256G" => 2 * Constants::DENSITY_TBITS,
				"512G" => 4 * Constants::DENSITY_TBITS
			], 0));
		}

		return $flashInfo;
	}

	public static function getFlashInfoFromFdb(string $partNumber) : ?array{
		return FlashDetector::getFdb()[strtolower(self::getName())][$partNumber] ?? null;
	}
}
