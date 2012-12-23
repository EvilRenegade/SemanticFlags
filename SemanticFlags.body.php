<?php
/*
    SemanticFlags is a MediaWiki extension to streamline flag markup on
	ModEnc and to expose the information in a Schema.org-compatible way
	for search engines.
    Copyright (C) 2012 Renegade (RenegadeProjects.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if( !defined( 'MEDIAWIKI' ) ) {
        echo( "This is an extension to the MediaWiki package and cannot be run standalone.\n" );
        die( -1 );
}

class CnCInstallment {
	public $name = "";
	public $abbr = "";
	public $isAddOn = false;
	public $isPatch = false;
	
	private static $installments = array();
	public static function getInstallment($pAbbr) {		
		if(!array_key_exists($pAbbr, self::$installments)) {
			$inst = new CnCInstallment();
			
			switch($pAbbr) {
				case 'ra':
					$inst->name = "Red Alert";
					$inst->abbr = "RA";
					break;
				case 'cs':
					$inst->name = "Counterstrike";
					$inst->abbr = "CS";
					$inst->isAddOn = true;
					break;
				case 'am':
					$inst->name = "The Aftermath";
					$inst->abbr = "AM";
					$inst->isAddOn = true;
					break;
				case 'arda':
					$inst->name = "Alarmstufe Rot Development Advanced";
					$inst->abbr = "ARDA";
					$inst->isPatch = true;
					break;

				case 'ts':
					$inst->name = "Tiberian Sun";
					$inst->abbr = "TS";
					break;
				case 'fs':
					$inst->name = "FireStorm";
					$inst->abbr = "FS";
					$inst->isAddOn = true;
					break;
				case 'hp':
					$inst->name = "HyperPatch";
					$inst->abbr = "HP";
					$inst->isPatch = true;
					break;

				case 'ra2':
					$inst->name = "Red Alert 2";
					$inst->abbr = "RA2";
					break;
				case 'yr':
					$inst->name = "Yuri's Revenge";
					$inst->abbr = "YR";
					$inst->isAddOn = true;
					break;
				case 'rp':
					$inst->name = "RockPatch";
					$inst->abbr = "RP";
					$inst->isPatch = true;
					break;
				case 'np':
					$inst->name = "NPatch";
					$inst->abbr = "NP";
					$inst->isPatch = true;
					break;
				case 'npext':
					$inst->name = "NPatch Extended";
					$inst->abbr = "NPExt";
					$inst->isPatch = true;
					break;
				case 'ares':
					$inst->name = "Ares";
					$inst->abbr = "Ares";
					$inst->isPatch = true;
					break;
				case 'gz':
					$inst->name = "Gear Zero";
					$inst->abbr = "GZ";
					$inst->isPatch = true;
					break;
			}
			
			self::$installments[$pAbbr] = $inst;
		}
		
		return self::$installments[$pAbbr];
	}
}

class SemanticFlag {
	private $input = ""; // Input between the <sample> and </sample> tags, or null if the tag is "closed", i.e. <sample />
	private $args = array(); // Tag arguments, which are entered like HTML tag attributes; this is an associative array indexed by attribute name.
	private $parser = null;
	private $frame = null;
	
	private $name = "";
	private $values = "";
	private $default = "";
	private $special = "";
	private $installments = array();
	private $sections = "";
	
	public function __construct($pInput, $pArgs, &$pParser, &$pFrame) {
		$this->input = $pInput;
		$this->args = $pArgs;
		$this->parser = &$pParser;
		$this->frame = &$pFrame;
		$this->parse();
	}
	
	// resets the class's interpretation of the locally saved data
	/*
		currently known attributes:
		<flag
			name=""
			values=""
			  default=""
			  special=""
			availablein=""
			sections=""
		  />
	*/
	public function parse() {
		foreach($this->args as $key => $value) {
			$saneValue = htmlspecialchars($value);
			
			switch($key) {
				case "name":
					$this->name = $saneValue;
					break;
				case "values":
					$this->values = $saneValue;
					break;
				case "default":
					$this->default = $saneValue;
					break;
				case "special":
					$this->special = $saneValue;
					break;
				case "availablein":
					$selectedInstallments = explode(',', $saneValue);
					foreach($selectedInstallments as $installment) {
						$selectedInstallment = null;
						
						switch(strtolower(trim($installment))) {
							case 'ra':
							case "red alert":
							case "redalert":
								$selectedInstallment = CnCInstallment::getInstallment('ra');
								break;
							case 'cs':
							case "counterstrike":
							case "counter-strike":
							case "counter strike":
								$selectedInstallment = CnCInstallment::getInstallment('cs');
								break;
							case 'am':
							case "the aftermath":
							case "aftermath":
							case "after math":
								$selectedInstallment = CnCInstallment::getInstallment('am');
								break;
							case 'arda':
							case "alarmstufe rot development advanced":
								$selectedInstallment = CnCInstallment::getInstallment('arda');
								break;
			
							case 'ts':
							case "tiberian sun":
							case "tiberiansun":
							case "tibsun":
								$selectedInstallment = CnCInstallment::getInstallment('ts');
								break;
							case 'fs':
							case 'firestorm':
							case 'fire storm':
								$selectedInstallment = CnCInstallment::getInstallment('fs');
								break;
							case 'hp':
							case "hyperpatch":
							case "hyper patch":
								$selectedInstallment = CnCInstallment::getInstallment('hp');
								break;
			
							case 'ra2':
							case "red alert 2":
							case "redalert 2":
								$selectedInstallment = CnCInstallment::getInstallment('ra2');
								break;
							case 'yr':
							case "yuri's revenge":
							case "yuris revenge":
								$selectedInstallment = CnCInstallment::getInstallment('yr');
								break;
							case 'rp':
							case "rockpatch":
							case "rock patch":
								$selectedInstallment = CnCInstallment::getInstallment('rp');
								break;
							case 'np':
							case "npatch":
							case "n patch":
								$selectedInstallment = CnCInstallment::getInstallment('np');
								break;
							case 'npext':
							case "npatch extended":
							case "npatchextended":
							case "n patch extended":
								$selectedInstallment = CnCInstallment::getInstallment('npext');
								break;
							case 'ares':
								$selectedInstallment = CnCInstallment::getInstallment('ares');
								break;
							case 'gz':
							case "gear zero":
							case "gearzero":
								$selectedInstallment = CnCInstallment::getInstallment('gz');
								break;
						}
						
						$this->installments[] = $selectedInstallment;
					}
					break;
				case "sections":
					$this->sections = $saneValue;
					break;
			}
		}
	}
	
	// generates HTML from this flag's data
	public function getOutput() {
		$retVal = "";
		
		$games = "";
		$addons = "";
		$patches = "";
		foreach($this->installments as $installment) {
			$text = "{{icongame|".$installment->abbr."}}";
			
			if($installment->isPatch) {
				$patches .= $text;
			} elseif($installment->isAddOn) {
				$addons .= $text;
			} else {
				$games .= $text;
			}
		}
		
		$retVal = $this->name . " is a " . $this->sections . " flag on games $games, add-ons $addons and patches $patches";
		
		return $this->parser->recursiveTagParse($retVal, $this->frame);
	}
}