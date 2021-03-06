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

//Flash Database Generator

require_once "env.php";

use iTXTech\FlashDetector\FDBGen\FDBGen;
use iTXTech\FlashDetector\FDBGen\IDDBGen;
use iTXTech\SimpleFramework\Console\Option\Exception\ParseException;
use iTXTech\SimpleFramework\Console\Option\HelpFormatter;
use iTXTech\SimpleFramework\Console\Option\OptionBuilder;
use iTXTech\SimpleFramework\Console\Option\OptionGroup;
use iTXTech\SimpleFramework\Console\Option\Options;
use iTXTech\SimpleFramework\Console\Option\Parser;
use iTXTech\SimpleFramework\Util\Util;

global $moduleManager;
loadModule($moduleManager, "FDBGen");

FDBGen::init();

$options = new Options();
$group = new OptionGroup();
$group->addOption((new OptionBuilder("f"))->longOpt("fdb")->desc("Generate Flash Database")->build())
	->addOption((new OptionBuilder("i"))->longOpt("iddb")->desc("Generate Flash Id Database")->build());
$group->setRequired(true);
$options->addOptionGroup($group);
$options->addOption((new OptionBuilder("v"))->longOpt("version")
	->desc("FDB file version, optional")->hasArg(true)->argName("ver")->build())
	->addOption((new OptionBuilder("d"))->longOpt("input")->required()
		->desc("Input dir or file")->hasArg(true)->argName("file")->build())
	->addOption((new OptionBuilder("o"))->longOpt("output")->required()
		->desc("Output file")->hasArg(true)->argName("file")->build())
	->addOption((new OptionBuilder("p"))->longOpt("pretty")->desc("JSON pretty output")->build())
	->addOption((new OptionBuilder("e"))->longOpt("extra")->desc("Include Extra.json")->build());

try{
	$cmd = (new Parser())->parse($options, $argv);
	if($cmd->hasOption("f")){
		$fdb = FDBGen::generate($cmd->getOptionValue("v", "Undefined"),
			$cmd->getOptionValue("d"), $cmd->hasOption("e"));
		if($cmd->hasOption("p")){
			$fdb = json_encode($fdb, JSON_PRETTY_PRINT);
		}else{
			$fdb = json_encode($fdb);
		}
		file_put_contents($cmd->getOptionValue("o"), $fdb);
	}
	if($cmd->hasOption("i")){
		$iddb = IDDBGen::generate(json_decode(file_get_contents($cmd->getOptionValue("d")), true));
		if($cmd->hasOption("p")){
			$iddb = json_encode($iddb, JSON_PRETTY_PRINT);
		}else{
			$iddb = json_encode($iddb);
		}
		file_put_contents($cmd->getOptionValue("o"), $iddb);
	}
}catch(ParseException $e){
	Util::println($e->getMessage());
	echo((new HelpFormatter())->generateHelp("fdbgen", $options));
}
