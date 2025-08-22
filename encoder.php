<?php
/**
 * PHP One-line Obfuscating Encoder (Loader generator)
 *
 * Usage:
 *   php encoder.php --in /path/to/input.php --out /path/to/output.php [--key YOUR_SECRET_HEX]
 *
 * - Produces a single-line PHP loader that decrypts, inflates and executes the original code
 * - Cipher: XOR stream with user key (hex) or random key (16 bytes). Payload compressed via gzdeflate
 * - Loader uses pack('H*', ...) and randomized constants/delimiters akin to the example
 */

ini_set('display_errors', 'stderr');

function fail(string $message, int $code = 1): void {
	fwrite(STDERR, "[encoder] " . $message . "\n");
	exit($code);
}

function readCliArgs(): array {
	$opts = getopt('', [
		'in:',    // required
		'out:',   // required
		'key::',  // optional hex string. If not provided, random key will be used
	]);
	if (!isset($opts['in']) || !isset($opts['out'])) {
		fail("Missing required arguments. Example: php encoder.php --in src.php --out encoded.php [--key 001122...]");
	}
	return $opts;
}

function readFileStrict(string $path): string {
	if (!is_file($path)) {
		fail("Input file not found: " . $path);
	}
	$code = file_get_contents($path);
	if ($code === false) {
		fail("Failed to read file: " . $path);
	}
	return $code;
}

function stripPhpTags(string $code): string {
	$code = preg_replace('/^\xEF\xBB\xBF/', '', $code); // strip UTF-8 BOM
	$code = preg_replace('/^\s*<\?(php)?/i', '', $code, 1);
	$code = preg_replace('/\?>\s*$/', '', $code, 1);
	return $code;
}

function randomBytesSecure(int $len): string {
	$bytes = random_bytes($len);
	return $bytes;
}

function hexToBinStrict(string $hex): string {
	$hex = preg_replace('/[^0-9a-f]/i', '', $hex) ?? '';
	if ($hex === '') {
		return '';
	}
	$bin = @hex2bin($hex);
	if ($bin === false) {
		fail("Invalid hex for --key");
	}
	return $bin;
}

function binToHex(string $bin): string {
	return bin2hex($bin);
}

function xorBytes(string $data, string $key): string {
	if ($key === '') {
		return $data;
	}
	$out = '';
	$klen = strlen($key);
	$len = strlen($data);
	for ($i = 0; $i < $len; $i++) {
		$out .= $data[$i] ^ $key[$i % $klen];
	}
	return $out;
}

function gzDeflateStrict(string $data): string {
	$compressed = gzdeflate($data, 9);
	if ($compressed === false) {
		fail("gzdeflate failed");
	}
	return $compressed;
}

function generateConstName(): string {
	$parts = [];
	$chunks = random_int(3, 5);
	for ($i = 0; $i < $chunks; $i++) {
		$len = random_int(1, 4);
		$seg = '';
		for ($j = 0; $j < $len; $j++) {
			$seg .= chr(random_int(65, 90)); // A-Z
		}
		$parts[] = $seg;
	}
	return implode('_', $parts);
}

function generateDelimiter(): string {
	// Example style: |r|1|*|
	$r1 = chr(random_int(97, 122)); // a-z
	$r2 = chr(random_int(42, 122)); // * to z
	$digit = (string) random_int(0, 9);
	return '|' . $r1 . '|' . $digit . '|' . $r2 . '|';
}

function minifyToSingleLine(string $php): string {
	// Remove newlines and collapse tabs/spaces while being careful not to break strings
	// Simpler approach: remove all newlines and tabs; collapse multiple spaces outside strings
	$php = str_replace(["\r\n", "\n", "\r", "\t"], '', $php);
	$php = preg_replace('/\s{2,}/', ' ', $php);
	return $php;
}

function buildLoader(string $hexCipher, string $hexKey): string {
	// Randomize constants and delimiters akin to the example
	$cA = generateConstName();
	$cB = generateConstName();
	$cC = generateConstName();
	$d1 = generateDelimiter();
	$d2 = generateDelimiter();

	// Strings to embed as hex pieces decoded via pack('H*', ...)
	$piecesA = [
		'H*',                  // index 0: format
		bin2hex('pack'),       // index 1
		bin2hex('eval'),       // index 2
		bin2hex('gzinflate'),  // index 3
		$hexCipher,            // index 4
		$hexKey,               // index 5
	];

	// Additional pieces (noise + common symbols)
	$piecesB = [
		'H*',
		bin2hex('strlen'),
		bin2hex('substr'),
		bin2hex('call_user_func'),
		bin2hex('GLOBALS'),
		bin2hex('$$'), // not used
	];

	$constValA = generateConstName();
	$constValB = generateConstName();
	$constValC = generateConstName();

	$glueA = implode($d1, $piecesA);
	$glueB = implode($d2, $piecesB);

	// Build the one-line loader body
	$loader = '';
	$loader .= 'if(!defined("' . $cA . '"))define("' . $cA . '","' . $constValA . '");';
	$loader .= '$GLOBALS[' . $cA . ']=explode("' . $d1 . '",\'' . $glueA . '\');';
	$loader .= 'if(!defined("' . $cB . '"))define("' . $cB . '","' . $constValB . '");';
	$loader .= '$GLOBALS[' . $cB . ']=explode("' . $d2 . '",\'' . $glueB . '\');';
	$loader .= '$g=&$GLOBALS[' . $cA . '];';
	$loader .= '$_p=pack($g[0],$g[1]);$_e=pack($g[0],$g[2]);$_z=pack($g[0],$g[3]);$_c=pack($g[0],$g[4]);$_k=pack($g[0],$g[5]);';
	$loader .= '$_x="";for($i=0,$l=strlen($_c);$i<$l;$i++){$_x.=$_c[$i]^$_k[$i%strlen($_k)];}';
	$loader .= '$_d=$_z($_x);eval($_d);';

	// Wrap in PHP tag and ensure single line
	$code = '<?php ' . $loader;
	return minifyToSingleLine($code);
}

function main(): void {
	$opts = readCliArgs();
	$inputPath = $opts['in'];
	$outputPath = $opts['out'];
	$original = readFileStrict($inputPath);
	$body = stripPhpTags($original);
	if ($body === '') {
		fail("Input appears empty after stripping PHP tags");
	}
	$keyBin = '';
	if (isset($opts['key']) && $opts['key'] !== false && $opts['key'] !== '') {
		$keyBin = hexToBinStrict((string)$opts['key']);
		if ($keyBin === '') {
			fail("Provided --key decoded to empty");
		}
	} else {
		$keyBin = randomBytesSecure(16);
	}
	$compressed = gzDeflateStrict($body);
	$cipher = xorBytes($compressed, $keyBin);
	$hexCipher = binToHex($cipher);
	$hexKey = binToHex($keyBin);
	$loader = buildLoader($hexCipher, $hexKey);
	if (file_put_contents($outputPath, $loader) === false) {
		fail("Failed to write output file: " . $outputPath);
	}
	fwrite(STDERR, "[encoder] Wrote obfuscated loader to: " . $outputPath . " (" . strlen($loader) . " bytes)\n");
}

main();