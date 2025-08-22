#!/usr/bin/env php
<?php
/**
 * Ranyun_JiaMi Loader Builder (CLI)
 * Usage:
 *   php loader.php input.php > output.php
 *   php loader.php input.php -o output.php
 */

function exitWithUsage(string $msg = ''): void {
	if ($msg !== '') fwrite(STDERR, "{$msg}\n");
	fwrite(STDERR, "Usage: php loader.php <input.php> [-o output.php]\n");
	exit(1);
}

// Parse args
$in = null; $out = null;
for ($i=1; $i<$argc; $i++) {
	$arg = $argv[$i];
	if ($arg === '-o' && isset($argv[$i+1])) { $out = $argv[++$i]; continue; }
	if ($in === null) { $in = $arg; continue; }
}
if (!$in) exitWithUsage();
if (!is_file($in)) exitWithUsage("Input file not found: {$in}");

$src = file_get_contents($in);
if ($src === false) exitWithUsage("Failed to read: {$in}");

// Strip opening/closing tags and shebangs to reduce leakage
$src = preg_replace('/^\xEF\xBB\xBF/', '', $src); // BOM
$src = preg_replace('/^\#\!.*/', '', $src); // shebang
$src = preg_replace('/^\s*<\?(php)?/i', '', $src);
$src = preg_replace('/\?>\s*$/', '', $src);

// Optional minify (very light, preserves strings)
$src = preg_replace('/\r\n?/', "\n", $src);
$src = preg_replace('/\n{3,}/', "\n\n", $src);

// Payload: base64(deflate(code)) to keep ASCII and short
$payload = base64_encode(gzdeflate($src, 9));
$payloadHex = bin2hex($payload);

// Helper to emit explode tables in the requested style
$explodeTable = function(array $items, string $delim) {
	$parts = [];
	foreach ($items as $it) {
		$parts[] = 'H*'.$delim.bin2hex($it);
	}
	return 'H*'.$delim.implode($delim, array_map(function($x){ return substr($x, 2); }, $parts)).$delim; // keep leading H* once
};

// Constants and symbols to mimic the example structure
$C_A   = 'A__AAAA_A';     // function table A
$C_B   = 'CF__C_AA';      // function table B
$C_C   = '__DFEA_FF';     // misc table
$C_D   = 'DCBFBNDN_';     // misc table
$C_E   = 'E__AWA_ABC_';   // misc table
$ARR_A = [
	'H*',              // 0 -> pack format
	'pack',            // 1
	'define',          // 2
	'ini_set',         // 3
];
$ARR_B = [
	'base64_decode',   // 0
	'gzinflate',       // 1
	'eval',            // 2
	'require',         // 3
];
$ARR_C = [
	'payload',         // 0
	'build',           // 1
	'run',             // 2
];
$ARR_D = [
	'content-type',    // 0
	'text/html; charset=utf-8', // 1
];
$ARR_E = [
	'<?php',           // 0
	'?>',              // 1
];

$tblA = $explodeTable($ARR_A, '|r|1|*|');
$tblB = $explodeTable($ARR_B, '|o|1|C|');
$tblC = $explodeTable($ARR_C, '|e|1|6|');
$tblD = $explodeTable($ARR_D, '|l|-|0|');
$tblE = $explodeTable($ARR_E, '|x|5|3|');

$payloadMarkerConst = 'DAFA_DEC';

// Build the obfuscated loader body
$loader = '';
$loader .= "<?php\n";
$loader .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
$loader .= "if(!defined(\"{$C_A}\"))define(\"{$C_A}\",\"CFA__ACCE\");";
$loader .= "\$GLOBALS[{$C_A}]=explode('|r|1|*|', '{$tblA}');";
$loader .= "if(!defined(\"{$C_B}\"))define(\"{$C_B}\",\"AEEFDLLL\");";
$loader .= "\$GLOBALS[{$C_B}]=explode('|o|1|C|','{$tblB}');";
$loader .= "if(!defined(\"{$C_C}\"))define(\"{$C_C}\",\"BABC_FBA\");";
$loader .= "\$GLOBALS[{$C_C}]=explode('|e|1|6|','{$tblC}');";
$loader .= "if(!defined(\"{$C_D}\"))define(\"{$C_D}\",\"DCBACAEC\");";
$loader .= "\$GLOBALS[{$C_D}]=explode('|l|-|0|','{$tblD}');";
$loader .= "if(!defined(\"{$C_E}\"))define(\"{$C_E}\",\"DXE_X_XX\");";
$loader .= "\$GLOBALS[{$C_E}]=explode('|x|5|3|','{$tblE}');";

// Switch off error display (like the sample toggles ini via define/pack)
$loader .= "if(!defined(pack(\$GLOBALS[{$C_A}][0x0], '444146415f444543')))call_user_func(pack(\$GLOBALS[{$C_A}][0x0], \$GLOBALS[{$C_A}][2]),'error_reporting',0);";

// Payload array in globals (mimic)
$loader .= "\$GLOBALS[{$payloadMarkerConst}]=array();";
$loader .= "\$GLOBALS[{$payloadMarkerConst}]['H'] = '".bin2hex('H*')."';";
$loader .= "\$GLOBALS[{$payloadMarkerConst}]['P'] = '".$payloadHex."';";

// Rebuild functions via pack of hex names to look alike
$fnPackHex   = bin2hex('pack');
$fnB64Hex    = bin2hex('base64_decode');
$fnInflHex   = bin2hex('gzinflate');
$fnEvalHex   = bin2hex('eval');

$loader .= "\n";
$loader .= "// runtime\n";
$loader .= "\$h = pack(\$GLOBALS[{$C_A}][0], \$GLOBALS[{$payloadMarkerConst}]['H']);"; // 'H*'
$loader .= "\$fn_pack = pack(\$h, '{$fnPackHex}');";
$loader .= "\$fn_b64  = \$fn_pack(\$h, '{$fnB64Hex}');";
$loader .= "\$fn_inf  = \$fn_pack(\$h, '{$fnInflHex}');";
$loader .= "\$fn_eval = \$fn_pack(\$h, '{$fnEvalHex}');";
$loader .= "\$px = \$fn_pack(\$GLOBALS[{$C_A}][0], \$GLOBALS[{$payloadMarkerConst}]['P']);";
$loader .= "\$code = call_user_func(\$fn_b64, \$px);";
$loader .= "\$code = call_user_func(\$fn_inf, \$code);";
// Re-wrap with tags to ensure valid PHP file
$loader .= "\$open = pack(\$GLOBALS[{$C_E}][0], \$GLOBALS[{$C_E}][2]);"; // '<?php' (decoded by pack with 'H*' at idx0)
$loader .= "\$close = pack(\$GLOBALS[{$C_E}][0], \$GLOBALS[{$C_E}][3]);"; // '?>'
$loader .= "\$code = \$open.\$code.\$close;";
// Output via eval
$loader .= "call_user_func(\$fn_eval, \$code);";
$loader .= "\n";

if ($out) {
	file_put_contents($out, $loader);
} else {
	echo $loader;
}