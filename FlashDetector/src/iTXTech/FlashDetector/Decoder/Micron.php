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
use iTXTech\FlashDetector\Property\FlashInterface;
use iTXTech\SimpleFramework\Util\StringUtil;

class Micron extends Decoder{
	protected const PACKAGE = [
		"WP" => "48-pin TSOP I Center Package Leads (CPL) PB free",
		"WC" => "48-pin TSOP I Off-center Package Leads (OCPL) PB free",
		"C5" => "52-pad VLGA, 14 x 18 x 1.0 (SDP/DDP/QDP)",
		"G1" => "272-ball VBGA, 14 x 18 x 1.0 (SDP, DDP, 3DP, QDP)",
		"G2" => "272-ball TBGA, 14 x 18 x 1.3 (QDP, 8DP)",
		"G6" => "272-ball LBGA, 14 x 18 x 1.5 (16DP)",
		"H1" => "100-ball VBGA, 12 x 18 x 1.0",
		"H2" => "100-ball TBGA, 12 x 18 x 1.2",
		"H3" => "100-ball LBGA, 12 x 18 x 1.4 (8DP)",
		"H4" => "63-ball VFBGA, 9 x 11 x 1.0",
		"HC" => "63-ball VFBGA, 10.5 x 13 x 1.0",
		"H6" => "152-ball VBGA, 14 x 18 x 1.0 (SDP, DDP)",
		"H7" => "152-ball TBGA, 14 x 18 x 1.2 (QDP)",
		"H8" => "152-ball LBGA, 14 x 18 x 1.4 (8DP)",
		"H9" => "100-ball LBGA, 12 x 18 x 1.6 (16DP)",
		"J1" => "132-ball VBGA, 12 x 18 x 1.0 (SDP, DDP)",
		"J2" => "132-ball TBGA, 12 x 18 x 1.2 (QDP)",
		"J3" => "132-ball LBGA, 12 x 18 x 1.4 (8DP)",
		"J4" => "132-ball VBGA, 12 x 18 x 1.0 (SDP, DDP)",
		"J5" => "132-ball TBGA, 12 x 18 x 1.2 (QDP)",
		"J6" => "132-ball LBGA, 12 x 18 x 1.4 (8DP)",
		"J7" => "152-ball LBGA, 14 x 18 x 1.5 (16DP)",
		"J9" => "132-ball LBGA, 12 x 18 x 1.5 (16DP)",
		//SpecTek
		"C3" => "52-pad ULGA, 12 x 17 x 0.65",
		"C4" => "52-pad VLGA, 12 x 17 x 1.0",
		"C6" => "52-pad LLGA, 14 x 18 x 1.47",
		"C7" => "48-pad LLGA, 12 x 20 x 1.47",
		"C8" => "52-pad WLGA, 14 x 18 x 0.75",
		"D1" => "52-pad VLGA, 11 x 14 x 0.9",
		"G5" => "272-ball LFBGA, 14 x 18 x 1.4",
		"G9" => "252-ball LFBGA, 12 x 18 x 1.4",
		"H5" => "56-ball VFBGA, 12.8 x 9.5 x 1.0",
		"K3" => "100-ball VLGA 12 x 18 x 0.9",
		"K4" => "100-ball TLGA, 12 x 18 x 1.1",
		"K7" => "152-ball VLGA 14 x 18 x 0.9",
		"K8" => "152-ball TLGA 14 x 18 x 1.1",
		"K9" => "132-ball VLGA, 12 x 18 x 1.0",
		"MD" => "130-ball VFBGA, 8 x 9 x 1.0",
		"M4" => "132-ball TBGA, 12 x 18 x 1.3",
		"M5" => "132-ball LBGA, 12 x 18 x 1.5",
		"M8" => "55-ball VFBGA, 8 x 10 x 1.2",//should be M8Z
	];
	protected const DENSITY = [
		"1G" => 1 * Constants::DENSITY_GBITS,
		"2G" => 2 * Constants::DENSITY_GBITS,
		"4G" => 4 * Constants::DENSITY_GBITS,
		"8G" => 8 * Constants::DENSITY_GBITS,
		"16G" => 16 * Constants::DENSITY_GBITS,
		"32G" => 32 * Constants::DENSITY_GBITS,
		"64G" => 64 * Constants::DENSITY_GBITS,
		"128G" => 128 * Constants::DENSITY_GBITS,
		"256G" => 256 * Constants::DENSITY_GBITS,
		"384G" => 384 * Constants::DENSITY_GBITS,
		"512G" => 512 * Constants::DENSITY_GBITS,
		"1T" => Constants::DENSITY_TBITS,
		"1T2" => 1.125 * Constants::DENSITY_TBITS,
		"1HT" => 1.5 * Constants::DENSITY_TBITS,
		"2T" => 2 * Constants::DENSITY_TBITS,
		"3T" => 3 * Constants::DENSITY_TBITS,
		"4T" => 4 * Constants::DENSITY_TBITS,
		"6T" => 6 * Constants::DENSITY_TBITS,
		"8T" => 8 * Constants::DENSITY_TBITS,
		"16T" => 16 * Constants::DENSITY_TBITS,
	];
	protected const CLASSIFICATION = [
		"A" => [1, 0, 0, 1],//die, ce, rnb, ch
		"B" => [1, 1, 1, 1],
		"D" => [2, 1, 1, 1],
		"E" => [2, 2, 2, 2],
		"F" => [2, 2, 2, 1],
		"G" => [3, 3, 3, 3],
		"J" => [4, 2, 2, 1],
		"K" => [4, 2, 2, 2],
		"L" => [4, 4, 4, 4],
		"M" => [4, 4, 4, 2],
		"Q" => [8, 4, 4, 4],
		"R" => [8, 2, 2, 2],
		"T" => [16, 8, 4, 2],
		"U" => [8, 4, 4, 2],
		"V" => [16, 8, 4, 4],
		//SpecTek, no R/nB documented
		"C" => [3, 3, -1, 1],
		"H" => [4, 1, -1, 1],
		"N" => [6, 6, -1, 3],
		"P" => [8, 8, -1, 2],
		"W" => [16, 4, -1, 2],
		"X" => [4, 4, -1, 2],
		"Y" => [11, 7, -1, 3],
		"4" => [4, 4, -1, 1]
	];
	protected const INTERFACE = [
		"A" => [false, true, false],//sync, async, spi
		"B" => [true, true, false],
		"C" => [true, false, false],
		"D" => [false, false, true],
		//SpecTek
		"E" => [true, true, false],//TODO: confirm
		"F" => [true, true, false],
		"G" => [true, true, false],//TODO: confirm
		"M" => [false, false, false],//TODO: confirm
		"N" => [true, true, false]
	];

	public static function getName() : string{
		return Constants::MANUFACTURER_MICRON;
	}

	public static function check(string $partNumber) : bool{
		if(StringUtil::startsWith($partNumber, "MT")){
			return true;
		}
		return false;
	}

	public static function decode(string $partNumber) : FlashInfo{
		$flashInfo = (new FlashInfo($partNumber))->setManufacturer(self::getName());
		if(!StringUtil::startsWith($partNumber, "29")){
			$partNumber = substr($partNumber, 2);//remove MT
		}
		$partNumber = substr($partNumber, 2);//remove 29
		$extra = [
			"enterprise" => self::shiftChars($partNumber, 1) === "E"
		];
		$flashInfo
			->setType(Constants::NAND_TYPE_NAND)
			->setDensity(self::matchFromStart($partNumber, self::DENSITY, 0))
			->setDeviceWidth(self::getOrDefault(self::shiftChars($partNumber, 2), [
				"01" => 1,
				"08" => 8,
				"16" => 16
			], -1))
			->setCellLevel(self::getOrDefault(self::shiftChars($partNumber, 1), [
				"A" => 1,
				"C" => 2,
				"E" => 3,
				//TODO: confirm
				"G" => 4
			]));

		$classification = self::getOrDefault(self::shiftChars($partNumber, 1),
			self::CLASSIFICATION, [0, 0, 0, 0]);

		$flashInfo->setClassification(new Classification(
			$classification[1], $classification[3], $classification[2], $classification[0]))
			->setVoltage(self::getOrDefault(self::shiftChars($partNumber, 1), [
				"A" => "Vcc: 3.3V (2.70–3.60V), VccQ: 3.3V (2.70–3.60V)",
				"B" => "1.8V (1.70–1.95V)",
				"C" => "Vcc: 3.3V (2.70–3.60V), VccQ: 1.8V (1.70–1.95V)",
				"E" => "Vcc: 3.3V (2.70–3.60V), VccQ: 3.3V (2.70–3.60V)",
				"F" => "Vcc: 3.3V (2.50–3.60V), VccQ: 1.2V (1.14–1.26V)",
				"G" => "Vcc: 3.3V (2.60–3.60V) , VccQ: 1.8V (1.70–1.95V)",
				"H" => "Vcc: 3.3V (2.50–3.60V), VccQ: 1.2V (1.14–1.26) or 1.8V (1.70–1.95V)",
				"J" => "Vcc: 3.3V (2.50–3.60V), VccQ: 1.8V (1.70–1.95V)",
				"K" => "Vcc: 3.3V (2.60–3.60V), VccQ: 3.3V (2.60–3.60V)",
				"L" => "Vcc: 3.3V (2.60–3.60V), VccQ: 3.3V (2.60–3.60V)",
			]))
			->setGeneration(self::getOrDefault(self::shiftChars($partNumber, 1), [
				"A" => 1,
				"B" => 2,
				"C" => 3,
				"D" => 4
			]));
		self::setInterface(self::shiftChars($partNumber, 1), $flashInfo)
			->setPackage(self::getOrDefault(self::shiftChars($partNumber, 2), self::PACKAGE))
			->setExtraInfo($extra);
		//ignoring package

		return $flashInfo;
	}

	protected static function setInterface(string $interface, FlashInfo $info) : FlashInfo{
		$i = self::getOrDefault($interface, self::INTERFACE, [false, false, false]);
		return $info->setInterface((new FlashInterface(false))
			->setSync($i[0])->setAsync($i[1])->setSpi($i[2]));
	}

	public static function getFlashInfoFromFdb(string $partNumber) : ?array{
		return FlashDetector::getFdb()[strtolower(self::getName())][self::removePackage($partNumber)] ?? null;
	}

	public static function removePackage(string $pn) : string{
		$bit = strstr($pn, "08");
		if($bit !== false and strlen($bit) >= 8){
			$pn = substr($pn, 0, strlen($pn) + 7 - strlen($bit));
		}
		return $pn;
	}
}
