# iTXTech FlashDetector

[![License](https://img.shields.io/github/license/iTXTech/FlashDetector.svg)](https://github.com/iTXTech/FlashDetector/blob/master/LICENSE)

Universal NAND Flash Part Number Decoder

## Requirements

* [PHP 7.2](https://secure.php.net)
* [SimpleFramework 2.1](https://github.com/iTXTech/SimpleFramework)
* [SimpleHtmlDom](https://github.com/PeratX/SimpleHtmlDom)

## Supported

### Flash Manufacturers

1. `Intel`/`Micron`/`SpecTek`
1. `Samsung`
1. `SanDisk`/`Toshiba`
1. `SK hynix`

### Controllers

1. `SiliconMotion` (`SM321AC, SM321BB, SM321BC, SM324BB, SM324BC, SM325AB, SM325AC, SM3252A, SM3252B, SM3252C, SM3254AE, SM3255AA, SM3255AB, SM3255ENA1, SM3255ENAA, SM3257AA, SM3257ENAA, SM3257ENAA_8CE, SM3257ENBA, SM3257ENBB, SM3257ENLT, SM3259AA, SM3260AA, SM3260AB, SM3260AD, SM3261AA, SM3261AB, SM3263AA, SM3263AB, SM3267AA, SM3267AB, SM3267AB_COB, SM3267AC, SM3267AE, SM3268AA, SM3268AB, SM3269AA, SM3269AA_COB, SM3270AB, SM3270AC, SM3271AB, SM3280AB, SM3281AB, SM2231, SM2232, SM2240, SM2242, SM2244LT, SM2246EN, SM2246XT, SM2250, SM2256, SM2258XT, SM2258, SM2263XT`)
1. `Innostor` (`IS902E, IS902, IS903, IS916EN, IS916, IS917`)
1. `JMicron` (`JMF606, JMF608, JMF670H`)
1. `Maxiotek` (`MK8115`)
1. `SandForce` (`SF2141, SF2181, SF2281, SF2282, SF2382, SF2481, SF2241`)
1. `ChipsBank` (`CBM2092, CBM2093, CBM2093P, CBM2095, CBM2096P, CBM2096, CBM2096PT, CBM2096T, CBM2098P, CBM2098E, CBM2093E, CBM2098S, CBM2099, CBM2099E, CBM2099S, CBM2199, CBM2199S`)
1. `AlcorMicro` (`AU6987/AN, AU6989SNL, AU6989SNL-B, AU6989SN, AU6989SN-G, AU6989SN-GT, AU6989SN-GTA/B/C/D/E`)

## Web Server

There are three types of FDWebServer:

1. [FDWebServer-CGI](https://github.com/iTXTech/FlashDetector/tree/master/FDWebServer/CGI) - Compatible with Apache and PHP-FPM
1. [FDWebServer-Swoole](https://github.com/iTXTech/FlashDetector/tree/master/FDWebServer/swoole) - Extreme High Performance, using [swoole](https://github.com/swoole/swoole-src)
1. [FDWebServer-WorkerManEE](https://github.com/iTXTech/FlashDetector/tree/master/FDWebServer/WorkerManEE) - Single Thread Server for Any OS

## Usage

See files in [Scripts](https://github.com/iTXTech/FlashDetector/tree/master/Scripts).

## Flash Database

See [fdfdb](https://github.com/iTXTech/fdfdb).

## License

    Copyright (C) 2018-2019 iTX Technologies

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
