<?php

/**
 * @name      Censored Words
 * @copyright Spuds
 * @license   WTFPL http://www.wtfpl.net/txt/copying/
 * @version 1.0
 *
 */

// If we have found SSI.php and we are outside of ELK, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('ELK'))
{
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('ELK')) // If we are outside ELK and can't find SSI.php, then throw an error
{
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as Elkarte\'s SSI.php.');
}

$db = database();

// Get current censored words
$request = $db->query('', '
	SELECT 
		value
	FROM {db_prefix}settings
	WHERE variable = {string:censor_vulgar}',
	array(
		'censor_vulgar' => 'censor_vulgar',
	)
);
$censor_vulgar = array();
$row = $db->fetch_assoc($request);
if (!empty($row['value']))
{
	$censor_vulgar = explode("\n", $row['value']);
}
$db->free_result($request);

// And the translate to values
$request = $db->query('', '
	SELECT 
		value
	FROM {db_prefix}settings
	WHERE variable = {string:censor_proper}',
	array(
		'censor_proper' => 'censor_proper',
	)
);
$censor_proper = array();
$row = $db->fetch_assoc($request);
if (!empty($row['value']))
{
	$censor_proper = explode("\n", $row['value']);
}
$db->free_result($request);

// Define what we will say instead, how about puppies cuz who does not like puppies?
$replace_word_with = 'puppies';

// Define all the new words we will censor
$add_censor_vulgar = array(
	'4r5e',
	'5h1t',
	'5hit',
	'a55',
	'anal',
	'anus',
	'ass-fucker',
	'assram',
	'assfucker',
	'assfukka',
	'assho*',
	'asswhole',
	'b!tch',
	'b00bs',
	'b17ch',
	'b1tch',
	'ballsack',
	'bastard',
	'beastial',
	'beastiality',
	'bellend',
	'bestial',
	'bestiality',
	'blow job',
	'blowjob',
	'blowjobs',
	'boiolas',
	'bollock',
	'bollok',
	'boner',
	'boob',
	'boobs',
	'booobs',
	'boooobs',
	'booooobs',
	'booooooobs',
	'breasts',
	'buceta',
	'bunny fucker',
	'buttmuch',
	'buttplug',
	'c0ck',
	'c0cksucker',
	'carpet muncher',
	'cawk',
	'chink',
	'cipa',
	'cl1t',
	'clit',
	'clitoris',
	'clits',
	'cnut',
	'cock',
	'cock-sucker',
	'cockface',
	'cockhead',
	'cockmunch',
	'cockmuncher',
	'cocks',
	'cocksuck ',
	'cocksucked ',
	'cocksucker',
	'cocksucking',
	'cocksucks ',
	'cocksuka',
	'cocksukka',
	'cok',
	'cokmuncher',
	'coksucka',
	'coon',
	'cox',
	'crap',
	'cum',
	'cummer',
	'cumming',
	'cums',
	'cumshot',
	'cunilingus',
	'cunillingus',
	'cunnilingus',
	'cunt',
	'cuntlick ',
	'cuntlicker ',
	'cuntlicking ',
	'cunts',
	'cyalis',
	'cyberfuc',
	'cyberfuck ',
	'cyberfucked ',
	'cyberfucker',
	'cyberfuckers',
	'cyberfucking ',
	'd1ck',
	'damn',
	'dickhead',
	'dildo',
	'dildos',
	'dink',
	'dinks',
	'dirsa',
	'dlck',
	'dog-fucker',
	'doggin',
	'dogging',
	'donkeyribber',
	'doosh',
	'duche',
	'dyke',
	'ejaculate',
	'ejaculated',
	'ejaculates ',
	'ejaculating ',
	'ejaculatings',
	'ejaculation',
	'ejakulate',
	'f u c k',
	'f u c k e r',
	'f4nny',
	'fag',
	'fagging',
	'faggitt',
	'faggot',
	'faggs',
	'fagot',
	'fagots',
	'fags',
	'fannyflaps',
	'fannyfucker',
	'fanyy',
	'fcuk',
	'fcuker',
	'fcuking',
	'feck',
	'fecker',
	'felching',
	'fellate',
	'fellatio',
	'fingerfuck ',
	'fingerfucked ',
	'fingerfucker ',
	'fingerfuckers',
	'fingerfucking ',
	'fingerfucks ',
	'fistfuck',
	'fistfucked ',
	'fistfucker ',
	'fistfuckers ',
	'fistfucking ',
	'fistfuckings ',
	'fistfucks ',
	'flange',
	'fook',
	'fooker',
	'fuck',
	'fucka',
	'fucked',
	'fucker',
	'fuckers',
	'fuckhead',
	'fuckheads',
	'fuckin',
	'fucking',
	'fuckings',
	'fuckingshitmotherfucker',
	'fuckme ',
	'fucks',
	'fuckwhit',
	'fuckwit',
	'fudge packer',
	'fudgepacker',
	'fuk',
	'fuker',
	'fukker',
	'fukkin',
	'fuks',
	'fukwhit',
	'fukwit',
	'fux',
	'fux0r',
	'f_u_c_k',
	'gangbang',
	'gangbanged ',
	'gangbangs ',
	'gaysex',
	'goatse',
	'hardcoresex ',
	'heshe',
	'hoar',
	'hoare',
	'hoer',
	'hore',
	'horniest',
	'horny',
	'hotsex',
	'jack-off ',
	'jackoff',
	'jap',
	'jerk-off ',
	'jism',
	'jiz ',
	'jizm ',
	'jizz',
	'kawk',
	'knob',
	'knobead',
	'knobed',
	'knobend',
	'knobhead',
	'knobjocky',
	'knobjokey',
	'kock',
	'kondum',
	'kondums',
	'kum',
	'kummer',
	'kumming',
	'kums',
	'kunilingus',
	'l3i+ch',
	'l3itch',
	'labia',
	'lust',
	'lusting',
	'm0f0',
	'm0fo',
	'm45terbate',
	'ma5terb8',
	'ma5terbate',
	'masochist',
	'master-bate',
	'masterb8',
	'masterbat*',
	'masterbat3',
	'masterbate',
	'masterbation',
	'masterbations',
	'masturbate',
	'mo-fo',
	'mof0',
	'mofo',
	'mothafuck',
	'mothafucka',
	'mothafuckas',
	'mothafuckaz',
	'mothafucked ',
	'mothafucker',
	'mothafuckers',
	'mothafuckin',
	'mothafucking ',
	'mothafuckings',
	'mothafucks',
	'mother fucker',
	'motherfuck',
	'motherfucked',
	'motherfucker',
	'motherfuckers',
	'motherfuckin',
	'motherfucking',
	'motherfuckings',
	'motherfuckka',
	'motherfucks',
	'muff',
	'mutha',
	'muthafecker',
	'muthafuckker',
	'muther',
	'mutherfucker',
	'n1gga',
	'n1gger',
	'nazi',
	'nigg3r',
	'nigg4h',
	'nigga',
	'niggah',
	'niggas',
	'niggaz',
	'nigger',
	'niggers ',
	'nob jokey',
	'nobhead',
	'nobjocky',
	'nobjokey',
	'numbnuts',
	'nutsack',
	'p0rn',
	'pawn',
	'penis',
	'penisfucker',
	'phonesex',
	'phuck',
	'phuk',
	'phuked',
	'phuking',
	'phukked',
	'phukking',
	'phuks',
	'phuq',
	'pigfucker',
	'pimpis',
	'pisser',
	'pissers',
	'pisses ',
	'pissflaps',
	'pissin ',
	'pissing',
	'pissoff ',
	'pricks ',
	'pron',
	'pube',
	'pusse',
	'pussi',
	'pussies',
	'pussy',
	'pussys ',
	'rectum',
	'rimjaw',
	'rimming',
	's hit',
	'schlong',
	'scroat',
	'scrote',
	'scrotum',
	'semen',
	'sex',
	'sh!+',
	'sh!t',
	'sh1t',
	'shit',
	'shitdick',
	'shite',
	'shited',
	'shitey',
	'shitfuck',
	'shitfull',
	'shithead',
	'shiting',
	'shitings',
	'shits',
	'shitted',
	'shitter',
	'shitters ',
	'shitting',
	'shittings',
	'shitty ',
	'*shit',
	'skank',
	'smegma',
	'smut',
	'snatch',
	'son-of-a-bitch',
	'spunk',
	's_h_i_t',
	'teets',
	'titfuck',
	'tittiefucker',
	'titties',
	'tittyfuck',
	'tittywank',
	'titwank',
	'tosser',
	'tw4t',
	'twat',
	'twathead',
	'twatty',
	'twunt',
	'twunter',
	'v14gra',
	'v1gra',
	'vagina',
	'viagra',
	'vulva',
	'w00se',
	'wang',
	'wank',
	'wanker',
	'wanky',
	'whoar',
	'whore',
);

// Installing we add
if (empty($context['uninstalling']))
{
	// Determine what we will add, keeping whatever they may already have
	$add_censor_vulgar = array_diff($add_censor_vulgar, $censor_vulgar);

	// Define what we will say instead
	$add_censor_proper = array();
	foreach ($add_censor_vulgar as $word)
	{
		$add_censor_proper[] = $replace_word_with;
	}

	// Add them to whats there
	$censor_vulgar += $add_censor_vulgar;
	$censor_proper += $add_censor_proper;
}
else
{
	// Remove the list then
	$censor_vulgar_temp = $censor_vulgar;
	$censor_proper_temp = $censor_proper;
	foreach ($censor_vulgar_temp as $i => $value)
	{
		if (in_array($value, $add_censor_vulgar) && $censor_proper_temp[$i] == $replace_word_with)
		{
			unset($censor_vulgar[$i], $censor_proper[$i]);
		}
	}
}

// Update the database with the new settings:
updateSettings(array(
	'censor_vulgar' => implode("\n", $censor_vulgar),
	'censor_proper' => implode("\n", $censor_proper),
));

if (ELK == 'SSI')
{
	echo 'Congratulations, you have successfully installed this addon';
}