<?php

declare(strict_types=1);

namespace Obfuscator;

final class Encoder
{
    public function encodeFile(string $inputPath, string $outputPath, array $options = []): void
    {
        if (!is_file($inputPath)) {
            throw new \RuntimeException("Input file not found: {$inputPath}");
        }
        $source = file_get_contents($inputPath);
        if ($source === false) {
            throw new \RuntimeException("Failed to read input file: {$inputPath}");
        }
        $encoded = $this->encodeString($source, $options + [
            'strip_comments' => false,
            'strip_whitespace' => false,
        ]);
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException("Failed to create directory: {$dir}");
            }
        }
        if (file_put_contents($outputPath, $encoded) === false) {
            throw new \RuntimeException("Failed to write output file: {$outputPath}");
        }
    }

    public function encodeString(string $phpSource, array $options = []): string
    {
        $normalizedSource = $this->normalizeSource($phpSource, (bool)($options['strip_comments'] ?? false), (bool)($options['strip_whitespace'] ?? false));

        $compressed = gzcompress($normalizedSource, 9);
        if ($compressed === false) {
            throw new \RuntimeException('Compression failed');
        }

        $key = random_bytes(32);
        $iv  = random_bytes(16);
        $cipherRaw = openssl_encrypt($compressed, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($cipherRaw === false) {
            throw new \RuntimeException('Encryption failed. Ensure openssl extension is enabled.');
        }

        $payloadHex = bin2hex($cipherRaw);
        $keyHex     = bin2hex($key);
        $ivHex      = bin2hex($iv);

        $loader = $this->buildLoader($payloadHex, $keyHex, $ivHex, $options);
        return $loader;
    }

    private function normalizeSource(string $source, bool $stripComments, bool $stripWhitespace): string
    {
        $code = ltrim($source);
        if ($stripComments) {
            $code = $this->removePhpComments($code);
        }
        if ($stripWhitespace) {
            $code = preg_replace("/\s+/, ' ', $code) ?? $code;
        }
        return $code;
    }

    private function removePhpComments(string $code): string
    {
        $tokens = token_get_all($code);
        $result = '';
        foreach ($tokens as $token) {
            if (is_array($token)) {
                [$id, $text] = $token;
                if ($id === T_COMMENT || $id === T_DOC_COMMENT) {
                    continue;
                }
                if ($id === T_WHITESPACE) {
                    $text = preg_replace('/\s+/', ' ', $text) ?? $text;
                }
                $result .= $text;
            } else {
                $result .= $token;
            }
        }
        return $result;
    }

    private function buildLoader(string $payloadHex, string $keyHex, string $ivHex, array $options): string
    {
        $cA = $this->randomConst();
        $cB = $this->randomConst();
        $delimA = $this->randomDelim();
        $delimB = $this->randomDelim();

        $hex_openssl_decrypt = bin2hex('openssl_decrypt');
        $hex_gzuncompress    = bin2hex('gzuncompress');
        $hex_eval            = bin2hex('eval');
        $hex_algo            = bin2hex('AES-256-CBC');

        $arrA = 'H*' . $delimA . $hex_openssl_decrypt . $delimA . $hex_gzuncompress . $delimA . $hex_eval;
        $arrB = 'H*' . $delimB . $hex_algo;

        $licenseCheck = '';
        if (!empty($options['license'])) {
            $licenseHex = bin2hex((string)$options['license']);
            $licenseCheck = '\n$__lic = pack($GLOBALS[' . $cA . '][0], \'' . $licenseHex . '\');' .
                '\n$__ok = isset($_SERVER[' . $this->packString('HTTP_HOST') . ']) ? $_SERVER[' . $this->packString('HTTP_HOST') . '] : \"\";' .
                '\nif (strpos($__ok, $__lic) === false) { exit; }';
        }

        $code  = '';
        $code .= "<?php\n";
        $code .= "/**\n * Commercial PHP Loader (obfuscated)\n */\n";
        $code .= 'if(!defined(' . $this->packStringLiteral($cA) . '))define(' . $this->packStringLiteral($cA) . ',' . $this->packStringLiteral($this->randomConstValue()) . ');' . "\n";
        $code .= '$GLOBALS[' . $cA . ']=explode(' . $this->packStringLiteral($delimA) . ',' . $this->packStringLiteral($arrA) . ');' . "\n";
        $code .= 'if(!defined(' . $this->packStringLiteral($cB) . '))define(' . $this->packStringLiteral($cB) . ',' . $this->packStringLiteral($this->randomConstValue()) . ');' . "\n";
        $code .= '$GLOBALS[' . $cB . ']=explode(' . $this->packStringLiteral($delimB) . ',' . $this->packStringLiteral($arrB) . ');' . "\n";
        $code .= '$__pk = $GLOBALS[' . $cA . '][0];' . "\n";
        $code .= '$__od = pack($__pk,$GLOBALS[' . $cA . '][1]);' . "\n"; // openssl_decrypt
        $code .= '$__gz = pack($__pk,$GLOBALS[' . $cA . '][2]);' . "\n"; // gzuncompress
        $code .= '$__ev = pack($__pk,$GLOBALS[' . $cA . '][3]);' . "\n"; // eval
        $code .= '$__al = pack($GLOBALS[' . $cB . '][0],$GLOBALS[' . $cB . '][1]);' . "\n"; // AES-256-CBC
        $code .= '$__ph = pack($__pk,' . $this->packStringLiteral($payloadHex) . ');' . "\n";
        $code .= '$__ky = pack($__pk,' . $this->packStringLiteral($keyHex) . ');' . "\n";
        $code .= '$__iv = pack($__pk,' . $this->packStringLiteral($ivHex) . ');' . "\n";
        $code .= '$__fl = defined(' . $this->packStringLiteral('OPENSSL_RAW_DATA') . ') ? OPENSSL_RAW_DATA : 1;' . "\n";
        $code .= '$__pl = $__od($__ph,$__al,$__ky,$__fl,$__iv);' . "\n";
        $code .= '$__pl = call_user_func($__gz,$__pl);' . "\n";
        if ($licenseCheck !== '') {
            $code .= $licenseCheck . "\n";
        }
        $code .= 'eval(' . $this->packStringLiteral('?>') . ' . $__pl);' . "\n";
        $code .= "\n";
        return $code;
    }

    private function packString(string $s): string
    {
        return "'" . addslashes($s) . "'";
    }

    private function packStringLiteral(string $s): string
    {
        return "'" . addslashes($s) . "'";
    }

    private function randomConst(): string
    {
        $len = random_int(6, 12);
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ_';
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $out .= $letters[random_int(0, strlen($letters) - 1)];
        }
        return $out;
    }

    private function randomConstValue(): string
    {
        $len = random_int(8, 16);
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ_';
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $out .= $letters[random_int(0, strlen($letters) - 1)];
        }
        return $out;
    }

    private function randomDelim(): string
    {
        $a = random_int(0, 9);
        $b = random_int(0, 9);
        $c = random_int(0, 9);
        $parts = ['|', chr(97 + $a), '|', (string)$b, '|', (string)$c, '|'];
        return implode('', $parts);
    }
}