<?php
/**
 * Advanced PHP Encoder - Ranyun_JiaMi
 * Enhanced version with better obfuscation and error handling
 */

class AdvancedPHPEncoder {
    private $structure = '';
    private $payload = '';
    private $constants = [];
    private $tables = [];
    
    public function __construct() {
        $this->initializeConstants();
    }
    
    private function initializeConstants() {
        // Define obfuscation constants
        $this->constants = [
            'A__AAAA_A' => 'CFA__ACCE',
            'CF__C_AA' => 'AEEFDLLL', 
            '__DFEA_FF' => 'BABC_FBA',
            'DCBFBNDN_' => 'DCBACAEC',
            'E__AWA_ABC_' => 'DXE_X_XX'
        ];
    }
    
    public function encode($sourceCode) {
        // Clean and prepare source code
        $cleanCode = $this->cleanSourceCode($sourceCode);
        
        // Create payload
        $this->payload = $this->createPayload($cleanCode);
        
        // Build obfuscated structure
        $this->buildStructure();
        
        return $this->structure;
    }
    
    private function cleanSourceCode($code) {
        // Remove BOM, shebang, and PHP tags
        $code = preg_replace('/^\xEF\xBB\xBF/', '', $code);
        $code = preg_replace('/^\#\!.*/', '', $code);
        $code = preg_replace('/^\s*<\?(php)?/i', '', $code);
        $code = preg_replace('/\?>\s*$/', '', $code);
        
        // Light minification
        $code = preg_replace('/\r\n?/', "\n", $code);
        $code = preg_replace('/\n{3,}/', "\n\n", $code);
        
        return trim($code);
    }
    
    private function createPayload($code) {
        // Compress and encode payload
        $compressed = gzdeflate($code, 9);
        $encoded = base64_encode($compressed);
        return bin2hex($encoded);
    }
    
    private function buildStructure() {
        $this->structure = '';
        
        // Header
        $this->structure .= "<?php\n";
        $this->structure .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
        
        // Build constant definitions and tables
        $this->buildConstantTables();
        
        // Build payload section
        $this->buildPayloadSection();
        
        // Build runtime execution
        $this->buildRuntimeExecution();
    }
    
    private function buildConstantTables() {
        // Table A - Core functions
        $tableA = [
            'H*', 'pack', 'define', 'ini_set', 'error_reporting'
        ];
        $this->addTable('A__AAAA_A', $tableA, '|r|1|*|');
        
        // Table B - Decoding functions  
        $tableB = [
            'base64_decode', 'gzinflate', 'eval', 'require', 'call_user_func'
        ];
        $this->addTable('CF__C_AA', $tableB, '|o|1|C|');
        
        // Table C - Runtime variables
        $tableC = [
            'payload', 'build', 'run', 'code', 'result'
        ];
        $this->addTable('__DFEA_FF', $tableC, '|e|1|6|');
        
        // Table D - Headers and content
        $tableD = [
            'content-type', 'text/html; charset=utf-8', 'status', 'success'
        ];
        $this->addTable('DCBFBNDN_', $tableD, '|l|-|0|');
        
        // Table E - PHP tags
        $tableE = [
            '<?php', '?>', '<?', '?>'
        ];
        $this->addTable('E__AWA_ABC_', $tableE, '|x|5|3|');
    }
    
    private function addTable($constName, $items, $delimiter) {
        $parts = [];
        foreach ($items as $item) {
            $parts[] = 'H*' . $delimiter . bin2hex($item);
        }
        
        $tableData = 'H*' . $delimiter . implode($delimiter, 
            array_map(function($x) { return substr($x, 2); }, $parts)
        ) . $delimiter;
        
        $this->structure .= "if(!defined(\"{$constName}\"))define(\"{$constName}\",\"{$this->constants[$constName]}\");";
        $this->structure .= "\$GLOBALS[{$constName}]=explode('{$delimiter}', '{$tableData}');";
    }
    
    private function buildPayloadSection() {
        $payloadConst = 'DAFA_DEC';
        
        // Initialize payload storage
        $this->structure .= "\$GLOBALS[{$payloadConst}]=array();";
        $this->structure .= "\$GLOBALS[{$payloadConst}]['H'] = '" . bin2hex('H*') . "';";
        $this->structure .= "\$GLOBALS[{$payloadConst}]['P'] = '{$this->payload}';";
        
        // Add error suppression
        $this->structure .= "if(!defined(pack(\$GLOBALS[A__AAAA_A][0x0], '444146415f444543')))";
        $this->structure .= "call_user_func(pack(\$GLOBALS[A__AAAA_A][0x0], \$GLOBALS[A__AAAA_A][2]),'error_reporting',0);";
    }
    
    private function buildRuntimeExecution() {
        $this->structure .= "\n";
        $this->structure .= "// Runtime execution\n";
        
        // Rebuild function names
        $this->structure .= "\$h = pack(\$GLOBALS[A__AAAA_A][0], \$GLOBALS[DAFA_DEC]['H']);";
        $this->structure .= "\$fn_pack = pack(\$h, '" . bin2hex('pack') . "');";
        $this->structure .= "\$fn_b64  = \$fn_pack(\$h, '" . bin2hex('base64_decode') . "');";
        $this->structure .= "\$fn_inf  = \$fn_pack(\$h, '" . bin2hex('gzinflate') . "');";
        $this->structure .= "\$fn_eval = \$fn_pack(\$h, '" . bin2hex('eval') . "');";
        
        // Decode and execute payload
        $this->structure .= "\$px = \$fn_pack(\$GLOBALS[A__AAAA_A][0], \$GLOBALS[DAFA_DEC]['P']);";
        $this->structure .= "\$code = call_user_func(\$fn_b64, \$px);";
        $this->structure .= "\$code = call_user_func(\$fn_inf, \$code);";
        
        // Wrap with PHP tags
        $this->structure .= "\$open = pack(\$GLOBALS[E__AWA_ABC_][0], \$GLOBALS[E__AWA_ABC_][2]);";
        $this->structure .= "\$close = pack(\$GLOBALS[E__AWA_ABC_][0], \$GLOBALS[E__AWA_ABC_][3]);";
        $this->structure .= "\$code = \$open.\$code.\$close;";
        
        // Execute
        $this->structure .= "call_user_func(\$fn_eval, \$code);";
        $this->structure .= "\n";
    }
    
    // CLI interface
    public static function runCLI($argv) {
        if (count($argv) < 2) {
            fwrite(STDERR, "Usage: php advanced_php_encoder.php <input.php> [-o output.php]\n");
            exit(1);
        }
        
        $inputFile = $argv[1];
        $outputFile = null;
        
        // Parse output file option
        for ($i = 2; $i < count($argv); $i++) {
            if ($argv[$i] === '-o' && isset($argv[$i + 1])) {
                $outputFile = $argv[$i + 1];
                break;
            }
        }
        
        if (!file_exists($inputFile)) {
            fwrite(STDERR, "Input file not found: {$inputFile}\n");
            exit(1);
        }
        
        $sourceCode = file_get_contents($inputFile);
        if ($sourceCode === false) {
            fwrite(STDERR, "Failed to read input file: {$inputFile}\n");
            exit(1);
        }
        
        $encoder = new self();
        $encoded = $encoder->encode($sourceCode);
        
        if ($outputFile) {
            if (file_put_contents($outputFile, $encoded) === false) {
                fwrite(STDERR, "Failed to write output file: {$outputFile}\n");
                exit(1);
            }
            echo "Encoded file saved to: {$outputFile}\n";
        } else {
            echo $encoded;
        }
    }
}

// Run CLI if called directly
if (php_sapi_name() === 'cli') {
    AdvancedPHPEncoder::runCLI($argv);
}
?>