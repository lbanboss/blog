<?php
/**
 * 增强版PHP编码加密器Loader
 * 支持多层加密、变量混淆、控制流混淆等高级技术
 * 作者: AI Assistant
 * 版本: 2.0
 */

class AdvancedPHPEncoder {
    private $encryptionKey;
    private $layers;
    private $obfuscationLevel;
    
    public function __construct($key = null, $layers = 3, $obfuscationLevel = 'high') {
        $this->encryptionKey = $key ?: $this->generateRandomKey(64);
        $this->layers = $layers;
        $this->obfuscationLevel = $obfuscationLevel;
    }
    
    /**
     * 生成随机密钥
     */
    private function generateRandomKey($length = 64) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $key;
    }
    
    /**
     * 生成随机常量名
     */
    private function generateRandomConstant() {
        $prefixes = [
            'A__AAAA_A', 'CF__C_AA', '__DFEA_FF', 'DCBFBNDN_', 'E__AWA_ABC_',
            'X__BBBB_B', 'DG__D_BB', '__EFGB_GG', 'EDCGCNEN_', 'F__BXB_BCD_',
            'Y__CCCC_C', 'EH__E_CC', '__FGHB_HH', 'FEDHDOFO_', 'G__CYC_CDE_'
        ];
        $suffixes = [
            'CFA__ACCE', 'AEEFDLLL', 'BABC_FBA', 'DCBACAEC', 'DXE_X_XX',
            'DGB__BDDF', 'BFFGEMMM', 'CBCD_GCB', 'EDCBDBFD', 'EYF_Y_YY',
            'EHC__CEEG', 'CGGHFNNN', 'DCDE_HDC', 'FEDCECGE', 'FZG_Z_ZZ'
        ];
        return $prefixes[array_rand($prefixes)] . '_' . $suffixes[array_rand($suffixes)];
    }
    
    /**
     * 生成随机分隔符
     */
    private function generateRandomDelimiter() {
        $delimiters = [
            '|r|1|*|', '|o|1|C|', '|e|1|6|', '|l|-|0|', '|x|5|3|',
            '|a|2|#|', '|b|3|$|', '|c|4|%|', '|d|5|&|', '|f|6|@|',
            '|g|7|!|', '|h|8|~|', '|i|9|`|', '|j|0|^|', '|k|1|(|'
        ];
        return $delimiters[array_rand($delimiters)];
    }
    
    /**
     * 多层加密
     */
    private function multiLayerEncrypt($data) {
        $encrypted = $data;
        
        for ($i = 0; $i < $this->layers; $i++) {
            switch ($i % 4) {
                case 0:
                    $encrypted = bin2hex($encrypted);
                    break;
                case 1:
                    $encrypted = base64_encode($encrypted);
                    break;
                case 2:
                    $encrypted = $this->customEncode($encrypted);
                    break;
                case 3:
                    $encrypted = $this->xorEncrypt($encrypted);
                    break;
            }
        }
        
        return $encrypted;
    }
    
    /**
     * 自定义编码
     */
    private function customEncode($string) {
        $result = '';
        $len = strlen($string);
        for ($i = 0; $i < $len; $i++) {
            $result .= sprintf('%02x', ord($string[$i]));
        }
        return $result;
    }
    
    /**
     * XOR加密
     */
    private function xorEncrypt($string) {
        $result = '';
        $keyLen = strlen($this->encryptionKey);
        $len = strlen($string);
        
        for ($i = 0; $i < $len; $i++) {
            $result .= chr(ord($string[$i]) ^ ord($this->encryptionKey[$i % $keyLen]));
        }
        
        return bin2hex($result);
    }
    
    /**
     * 生成混淆的数组定义
     */
    private function generateObfuscatedArray($data, $delimiter) {
        $encoded = [];
        foreach ($data as $item) {
            $encoded[] = $this->multiLayerEncrypt($item);
        }
        return implode($delimiter, $encoded);
    }
    
    /**
     * 生成Loader代码
     */
    private function generateLoaderCode($encryptedData, $delimiter, $constantName) {
        $randomValue = $this->generateRandomKey(12);
        $loader = "if(!defined(\"{$constantName}\"))define(\"{$constantName}\",\"{$randomValue}\");";
        $loader .= "\$GLOBALS[{$constantName}]=explode('{$delimiter}','H*{$delimiter}" . $encryptedData . "');";
        return $loader;
    }
    
    /**
     * 生成复杂的解码器代码
     */
    private function generateAdvancedDecoder($constants) {
        $decoder = "";
        
        // 添加随机变量混淆
        $randomVars = [];
        for ($i = 0; $i < 5; $i++) {
            $varName = $this->generateRandomConstant();
            $randomVars[] = $varName;
            $decoder .= "if(!defined(\"{$varName}\"))define(\"{$varName}\",\"" . $this->generateRandomKey(8) . "\");";
        }
        
        // 生成解码逻辑
        foreach ($constants as $index => $const) {
            $decoder .= "if(!defined(pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][1])))";
            $decoder .= "call_user_func(pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][2]),";
            $decoder .= "pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][1]),";
            $decoder .= "pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][0x3]));";
            
            // 添加随机延迟
            if ($index % 2 == 0) {
                $randomVar = $randomVars[array_rand($randomVars)];
                $decoder .= "\$GLOBALS[{$randomVar}]=array_merge(\$GLOBALS[{$randomVar}]??[],array(rand(1,100)));";
            }
        }
        
        return $decoder;
    }
    
    /**
     * 控制流混淆
     */
    private function obfuscateControlFlow($code) {
        // 添加随机条件语句
        $obfuscated = "";
        $lines = explode(';', $code);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $randomCondition = $this->generateRandomCondition();
            $obfuscated .= "if({$randomCondition}){";
            $obfuscated .= $line . ";";
            $obfuscated .= "}else{";
            $obfuscated .= $line . ";";
            $obfuscated .= "}";
        }
        
        return $obfuscated;
    }
    
    /**
     * 生成随机条件
     */
    private function generateRandomCondition() {
        $conditions = [
            'true', 'false', '1==1', '0==0', 'rand(1,10)>5',
            'isset($_SERVER)', 'defined("PHP_VERSION")', 'function_exists("strlen")'
        ];
        return $conditions[array_rand($conditions)];
    }
    
    /**
     * 变量名混淆
     */
    private function obfuscateVariableNames($code) {
        $variables = [];
        $counter = 0;
        
        // 查找变量名
        preg_match_all('/\$([a-zA-Z_][a-zA-Z0-9_]*)/', $code, $matches);
        
        foreach ($matches[1] as $var) {
            if (!isset($variables[$var])) {
                $variables[$var] = '_' . $this->generateRandomKey(8) . '_' . $counter++;
            }
        }
        
        // 替换变量名
        foreach ($variables as $original => $obfuscated) {
            $code = preg_replace('/\$' . preg_quote($original, '/') . '\b/', '$' . $obfuscated, $code);
        }
        
        return $code;
    }
    
    /**
     * 字符串混淆
     */
    private function obfuscateStrings($code) {
        // 查找字符串
        preg_match_all('/"([^"]*)"|\'([^\']*)\'/', $code, $matches);
        
        $strings = array_merge($matches[1], $matches[2]);
        $strings = array_filter($strings); // 移除空字符串
        
        foreach ($strings as $string) {
            if (strlen($string) > 3) { // 只混淆长度大于3的字符串
                $encoded = $this->multiLayerEncrypt($string);
                $constantName = $this->generateRandomConstant();
                $delimiter = $this->generateRandomDelimiter();
                
                $loaderCode = $this->generateLoaderCode($encoded, $delimiter, $constantName);
                $code = str_replace('"' . $string . '"', "pack('H*',\$GLOBALS[{$constantName}][1])", $code);
                $code = str_replace("'" . $string . "'", "pack('H*',\$GLOBALS[{$constantName}][1])", $code);
                
                // 在代码开头添加常量定义
                $code = $loaderCode . $code;
            }
        }
        
        return $code;
    }
    
    /**
     * 主要加密方法
     */
    public function encrypt($phpCode) {
        // 清理代码
        $code = $this->cleanCode($phpCode);
        
        // 根据混淆级别应用不同的混淆技术
        if ($this->obfuscationLevel === 'high') {
            $code = $this->obfuscateVariableNames($code);
            $code = $this->obfuscateStrings($code);
            $code = $this->obfuscateControlFlow($code);
        }
        
        // 分割代码为逻辑块
        $blocks = $this->splitIntoBlocks($code);
        
        $encryptedCode = "";
        $constants = [];
        
        foreach ($blocks as $index => $block) {
            if (trim($block) === '') continue;
            
            $constantName = $this->generateRandomConstant();
            $delimiter = $this->generateRandomDelimiter();
            
            // 多层加密代码块
            $encryptedBlock = $this->multiLayerEncrypt($block);
            $arrayData = $this->generateObfuscatedArray([$encryptedBlock], $delimiter);
            
            // 生成Loader代码
            $loaderCode = $this->generateLoaderCode($arrayData, $delimiter, $constantName);
            $encryptedCode .= $loaderCode;
            
            $constants[] = [
                'name' => $constantName,
                'data' => $encryptedBlock
            ];
        }
        
        // 添加高级解码器
        $decoderCode = $this->generateAdvancedDecoder($constants);
        $encryptedCode .= $decoderCode;
        
        // 添加全局变量定义和混淆
        $globalVar = $this->generateRandomConstant();
        $encryptedCode .= "\$GLOBALS[{$globalVar}]=array(&\$_POST);";
        
        // 添加随机垃圾代码
        $encryptedCode .= $this->generateJunkCode();
        
        return $encryptedCode;
    }
    
    /**
     * 生成垃圾代码
     */
    private function generateJunkCode() {
        $junkCode = "";
        $junkCount = rand(3, 8);
        
        for ($i = 0; $i < $junkCount; $i++) {
            $varName = $this->generateRandomConstant();
            $junkCode .= "if(!defined(\"{$varName}\"))define(\"{$varName}\",\"" . $this->generateRandomKey(6) . "\");";
            $junkCode .= "\$GLOBALS[{$varName}]=array(rand(1,100),rand(1,100),rand(1,100));";
        }
        
        return $junkCode;
    }
    
    /**
     * 清理代码
     */
    private function cleanCode($code) {
        // 移除注释
        $code = preg_replace('/\/\*.*?\*\//s', '', $code);
        $code = preg_replace('/\/\/.*$/m', '', $code);
        
        // 移除多余空白
        $code = preg_replace('/\s+/', ' ', $code);
        $code = trim($code);
        
        return $code;
    }
    
    /**
     * 分割代码块
     */
    private function splitIntoBlocks($code) {
        $blocks = [];
        $currentBlock = '';
        $braceCount = 0;
        $inString = false;
        $stringChar = '';
        
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            
            if (!$inString && ($char === '"' || $char === "'")) {
                $inString = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar && $code[$i-1] !== '\\') {
                $inString = false;
            }
            
            if (!$inString) {
                if ($char === '{') $braceCount++;
                elseif ($char === '}') $braceCount--;
                elseif ($char === ';' && $braceCount === 0) {
                    $currentBlock .= $char;
                    $blocks[] = trim($currentBlock);
                    $currentBlock = '';
                    continue;
                }
            }
            
            $currentBlock .= $char;
        }
        
        if (trim($currentBlock) !== '') {
            $blocks[] = trim($currentBlock);
        }
        
        return $blocks;
    }
    
    /**
     * 加密文件
     */
    public function encryptFile($inputFile, $outputFile = null) {
        if (!file_exists($inputFile)) {
            throw new Exception("输入文件不存在: {$inputFile}");
        }
        
        $phpCode = file_get_contents($inputFile);
        $encryptedCode = $this->encrypt($phpCode);
        
        if ($outputFile === null) {
            $outputFile = pathinfo($inputFile, PATHINFO_FILENAME) . '_advanced_encrypted.php';
        }
        
        file_put_contents($outputFile, $encryptedCode);
        return $outputFile;
    }
    
    /**
     * 批量加密目录
     */
    public function encryptDirectory($inputDir, $outputDir = null) {
        if (!is_dir($inputDir)) {
            throw new Exception("输入目录不存在: {$inputDir}");
        }
        
        if ($outputDir === null) {
            $outputDir = $inputDir . '_advanced_encrypted';
        }
        
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
        
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($inputDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        $encryptedFiles = [];
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace($inputDir, '', $file->getPathname());
                $outputPath = $outputDir . $relativePath;
                
                $outputDirPath = dirname($outputPath);
                if (!is_dir($outputDirPath)) {
                    mkdir($outputDirPath, 0755, true);
                }
                
                $this->encryptFile($file->getPathname(), $outputPath);
                $encryptedFiles[] = $outputPath;
            }
        }
        
        return $encryptedFiles;
    }
}

// 命令行接口
if (php_sapi_name() === 'cli') {
    $layers = isset($argv[3]) ? (int)$argv[3] : 3;
    $level = isset($argv[4]) ? $argv[4] : 'high';
    
    $encoder = new AdvancedPHPEncoder(null, $layers, $level);
    
    if ($argc < 2) {
        echo "使用方法:\n";
        echo "php advanced_encoder.php <输入文件> [输出文件] [加密层数] [混淆级别]\n";
        echo "php advanced_encoder.php -d <输入目录> [输出目录] [加密层数] [混淆级别]\n";
        echo "混淆级别: low, medium, high (默认: high)\n";
        echo "加密层数: 1-10 (默认: 3)\n";
        exit(1);
    }
    
    try {
        if ($argv[1] === '-d') {
            // 加密目录
            $inputDir = $argv[2];
            $outputDir = isset($argv[3]) ? $argv[3] : null;
            $files = $encoder->encryptDirectory($inputDir, $outputDir);
            echo "成功加密 " . count($files) . " 个文件到目录: " . ($outputDir ?: $inputDir . '_advanced_encrypted') . "\n";
        } else {
            // 加密单个文件
            $inputFile = $argv[1];
            $outputFile = isset($argv[2]) ? $argv[2] : null;
            $result = $encoder->encryptFile($inputFile, $outputFile);
            echo "成功加密文件: {$result}\n";
        }
    } catch (Exception $e) {
        echo "错误: " . $e->getMessage() . "\n";
        exit(1);
    }
}
?>