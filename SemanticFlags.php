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

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => "SemanticFlags",
	'descriptionmsg' => "semanticflags-desc",
	'version' => 0.1,
	'author' => "Renegade <Renegade@RenegadeProjects.com>",
	'url' => "https://github.com/EvilRenegade/SemanticFlags"
);
$wgExtensionMessagesFiles['SemanticFlags'] = dirname( __FILE__ ) . '/SemanticFlags.i18n.php';
$wgAutoloadClasses['SemanticFlag'] = dirname( __FILE__ ) . '/SemanticFlags.body.php';
$wgAutoloadClasses['CnCInstallment'] = dirname( __FILE__ ) . '/SemanticFlags.body.php';

$wgHooks['ParserFirstCallInit'][] = 'wfSemanticFlagsInit';

function wfSemanticFlagsInit(Parser $parser) {
	$parser->setHook( 'flag', 'wfParseFlag' );
	return true; //mandatory
}

/*
	All information is conveyed via attributes, so $args is where the info is at.
*/
function wfParseFlag( $input, array $args, Parser $parser, PPFrame $frame ) {
	$flag = new SemanticFlag($input, $args, $parser, $frame);
	return $flag->getOutput();
}