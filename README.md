# PHP One-line Obfuscating Encoder (Loader)

Usage:

```bash
php /workspace/encoder.php --in /path/to/input.php --out /path/to/output.php [--key HEX_KEY]
```

- Produces a single-line `<?php ...` loader that decodes and executes the original code
- Payload is gzdeflate-compressed, then XORed with a key
- Key: provide `--key` as hex (any length), or omitted to generate random 16 bytes

Notes:
- The output file is a single line by design
- The loader uses `pack('H*', ...)`, randomized constants, and custom delimiters
- The original script's `<?php ?>` tags are stripped before embedding

Example:

```bash
php /workspace/encoder.php --in /workspace/sample.php --out /workspace/encoded.php
php /workspace/encoded.php
```