## PHP Obfuscator Loader (Commercial-style)

This tool encodes PHP source into a single self-contained loader that decrypts and runs the original code at runtime. It uses:

- gzcompress to pack payload
- AES-256-CBC (openssl) to encrypt
- Hex + pack('H*', ...) tokens in arrays to obfuscate function names (similar style to the provided sample)

### Install

No install is required. Ensure PHP with `openssl` and `zlib` extensions is available.

### Usage

```bash
./bin/encode example/hello.php -o build/hello.encoded.php --strip-comments --strip-whitespace
php build/hello.encoded.php
```

Optional host binding (simple license-style check):

```bash
./bin/encode example/hello.php -o build/hello.encoded.php --license example.com
```

### Notes
- Output is a single PHP file containing an obfuscated loader and encrypted payload.
- The loader minimizes readable symbols and reconstructs function names via `pack` on hex tokens.
- Requires OpenSSL at runtime.