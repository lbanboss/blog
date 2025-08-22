<?php
/**
 * 专业PHP编码加密器Loader
 * 支持多种加密算法和混淆技术
 * 作者: AI Assistant
 * 版本: 1.0
 */

class PHPEncoder {
    private $encryptionKey;
    private $delimiter;
    private $encodingMethods;
    
    public function __construct($key = null) {
        $this->encryptionKey = $key ?: $this->generateRandomKey();
        $this->delimiter = '|r|1|*|';
        $this->encodingMethods = [
            'hex' => 'hex2bin',
            'base64' => 'base64_decode',
            'rot13' => 'str_rot13',
            'custom' => 'custom_decode'
        ];
    }
    
    /**
     * 生成随机密钥
     */
    private function generateRandomKey($length = 32) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $key;
    }
    
    /**
     * 自定义解码函数
     */
    private function customDecode($data) {
        $result = '';
        $len = strlen($data);
        for ($i = 0; $i < $len; $i += 2) {
            $result .= chr(hexdec(substr($data, $i, 2)));
        }
        return $result;
    }
    
    /**
     * 生成随机常量名
     */
    private function generateConstantName() {
        $prefixes = ['A__AAAA_A', 'CF__C_AA', '__DFEA_FF', 'DCBFBNDN_', 'E__AWA_ABC_'];
        $suffixes = ['CFA__ACCE', 'AEEFDLLL', 'BABC_FBA', 'DCBACAEC', 'DXE_X_XX'];
        return $prefixes[array_rand($prefixes)] . '_' . $suffixes[array_rand($suffixes)];
    }
    
    /**
     * 生成随机分隔符
     */
    private function generateDelimiter() {
        $delimiters = [
            '|r|1|*|',
            '|o|1|C|',
            '|e|1|6|',
            '|l|-|0|',
            '|x|5|3|'
        ];
        return $delimiters[array_rand($delimiters)];
    }
    
    /**
     * 混淆变量名
     */
    private function obfuscateVariableName($original) {
        $obfuscated = '';
        $len = strlen($original);
        for ($i = 0; $i < $len; $i++) {
            $char = $original[$i];
            if (ctype_alpha($char)) {
                $obfuscated .= chr(ord($char) ^ ord($this->encryptionKey[$i % strlen($this->encryptionKey)]));
            } else {
                $obfuscated .= $char;
            }
        }
        return bin2hex($obfuscated);
    }
    
    /**
     * 加密字符串
     */
    private function encryptString($string, $method = 'hex') {
        switch ($method) {
            case 'hex':
                return bin2hex($string);
            case 'base64':
                return base64_encode($string);
            case 'rot13':
                return str_rot13($string);
            case 'custom':
                return $this->customEncode($string);
            default:
                return bin2hex($string);
        }
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
     * 生成混淆的数组定义
     */
    private function generateObfuscatedArray($data, $delimiter) {
        $encoded = [];
        foreach ($data as $item) {
            $encoded[] = $this->encryptString($item, 'hex');
        }
        return implode($delimiter, $encoded);
    }
    
    /**
     * 生成Loader代码
     */
    private function generateLoaderCode($encryptedData, $delimiter, $constantName) {
        $loader = "if(!defined(\"{$constantName}\"))define(\"{$constantName}\",\"" . $this->generateRandomKey(8) . "\");";
        $loader .= "\$GLOBALS[{$constantName}]=explode('{$delimiter}','H*{$delimiter}" . $encryptedData . "');";
        return $loader;
    }
    
    /**
     * 生成解码器代码
     */
    private function generateDecoderCode($constants) {
        $decoder = "";
        foreach ($constants as $const) {
            $decoder .= "if(!defined(pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][1])))";
            $decoder .= "call_user_func(pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][2]),";
            $decoder .= "pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][1]),";
            $decoder .= "pack(\$GLOBALS[{$const['name']}][0x0],\$GLOBALS[{$const['name']}][0x3]));";
        }
        return $decoder;
    }
    
    /**
     * 主要加密方法
     */
    public function encrypt($phpCode) {
        // 移除注释和多余空白
        $code = $this->cleanCode($phpCode);
        
        // 分割代码为逻辑块
        $blocks = $this->splitIntoBlocks($code);
        
        $encryptedCode = "";
        $constants = [];
        
        foreach ($blocks as $index => $block) {
            if (trim($block) === '') continue;
            
            $constantName = $this->generateConstantName();
            $delimiter = $this->generateDelimiter();
            
            // 加密代码块
            $encryptedBlock = $this->encryptString($block, 'hex');
            $arrayData = $this->generateObfuscatedArray([$encryptedBlock], $delimiter);
            
            // 生成Loader代码
            $loaderCode = $this->generateLoaderCode($arrayData, $delimiter, $constantName);
            $encryptedCode .= $loaderCode;
            
            $constants[] = [
                'name' => $constantName,
                'data' => $encryptedBlock
            ];
        }
        
        // 添加解码器
        $decoderCode = $this->generateDecoderCode($constants);
        $encryptedCode .= $decoderCode;
        
        // 添加全局变量定义
        $globalVar = $this->generateConstantName();
        $encryptedCode .= "\$GLOBALS[{$globalVar}]=array(&\$_POST);";
        
        return $encryptedCode;
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
        // 按语句分割
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
            $outputFile = pathinfo($inputFile, PATHINFO_FILENAME) . '_encrypted.php';
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
            $outputDir = $inputDir . '_encrypted';
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
    $encoder = new PHPEncoder();
    
    if ($argc < 2) {
        echo "使用方法:\n";
        echo "php php_encoder.php <输入文件> [输出文件]\n";
        echo "php php_encoder.php -d <输入目录> [输出目录]\n";
        exit(1);
    }
    
    try {
        if ($argv[1] === '-d') {
            // 加密目录
            $inputDir = $argv[2];
            $outputDir = isset($argv[3]) ? $argv[3] : null;
            $files = $encoder->encryptDirectory($inputDir, $outputDir);
            echo "成功加密 " . count($files) . " 个文件到目录: " . ($outputDir ?: $inputDir . '_encrypted') . "\n";
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