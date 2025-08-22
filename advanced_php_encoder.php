<?php
/**
 * Ranyun_JiaMi 高级PHP代码加密器
 * 版权所有 - 生成与示例完全相同格式的混淆代码
 */

class AdvancedPHPEncoder {
    private $globalArrays = [];
    private $obfuscatedVars = [];
    private $gotoLabels = [];
    private $stringMaps = [];
    private $currentArrayIndex = 0;
    
    // 预定义的全局数组名称模式
    private $arrayNamePatterns = [
        'A__AAAA_A', 'CF__C_AA', '__DFEA_FF', 'DCBFBNDN_', 'E__AWA_ABC_',
        'DAFA_DEC', '_CCBAA__', 'FA_AE_E_', '_BAZ_ABF', 'BAEEACFA',
        'C_EA_A_A', 'CSSSASAB', 'DA_B___D', '_AFFC_A_', 'MMMC_MC_',
        'FCD_ACAA', 'AAV_A_DA', '__A_AF__', '_DDDAB_A', 'FCEV_VV_',
        'D_FAXXA_', 'XXAXCAB_', '_A__AA_A_AFC', 'IAA__IA_', 'ZCZFA_ZA__'
    ];
    
    // 生成复杂的变量名（模仿示例格式）
    private function generateComplexVarName() {
        $patterns = [
            '%s%dx%s%d',
            '%s%dx%s%d%d',
            '%s_%s_%d',
            '%s%d_%s%d'
        ];
        
        $chars1 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $chars2 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        
        $pattern = $patterns[array_rand($patterns)];
        $part1 = $chars1[array_rand($chars1)] . $chars1[array_rand($chars1)] . rand(0, 9);
        $part2 = $chars2[array_rand($chars2)] . $chars2[array_rand($chars2)] . rand(0, 9);
        
        return sprintf($pattern, $part1, rand(1, 9), $part2, rand(1000, 9999));
    }
    
    // 生成复杂的数学表达式（模仿示例中的错误常量计算）
    private function generateComplexMathExpression($targetValue) {
        $errorConstants = [
            'E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE', 'E_CORE_ERROR',
            'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING',
            'E_USER_ERROR', 'E_USER_WARNING', 'E_USER_NOTICE',
            'E_RECOVERABLE_ERROR', 'E_DEPRECATED', 'E_USER_DEPRECATED', 'E_STRICT'
        ];
        
        $operations = [
            function($val) use ($errorConstants) {
                $base = rand(-50000, 50000);
                $multiplier = rand(1, 1000);
                $constant1 = $errorConstants[array_rand($errorConstants)];
                $constant2 = $errorConstants[array_rand($errorConstants)];
                return "($base+$constant1+$multiplier*$constant2)";
            },
            function($val) use ($errorConstants) {
                $base = rand(-100000, 100000);
                $divisor = rand(2, 100);
                $constant1 = $errorConstants[array_rand($errorConstants)];
                $constant2 = $errorConstants[array_rand($errorConstants)];
                return "(($base+$constant1)/$divisor-$constant2)";
            },
            function($val) use ($errorConstants) {
                $base1 = rand(-10000, 10000);
                $base2 = rand(-10000, 10000);
                $constant1 = $errorConstants[array_rand($errorConstants)];
                $constant2 = $errorConstants[array_rand($errorConstants)];
                $constant3 = $errorConstants[array_rand($errorConstants)];
                return "($base1-$constant1+($base2+$constant2)*$constant3)";
            }
        ];
        
        $operation = $operations[array_rand($operations)];
        return $operation($targetValue);
    }
    
    // 创建十六进制编码的字符串数组
    private function createHexStringArray($strings, $arrayName, $separator) {
        $hexStrings = [];
        foreach ($strings as $str) {
            $hexStrings[] = bin2hex($str);
        }
        
        $arrayData = 'H*' . $separator . implode($separator, $hexStrings);
        
        $this->globalArrays[$arrayName] = [
            'separator' => $separator,
            'data' => $arrayData,
            'strings' => $strings,
            'hex_strings' => $hexStrings
        ];
        
        return $arrayData;
    }
    
    // 生成分隔符
    private function generateSeparator() {
        $separators = [
            '|r|1|*|', '|o|1|C|', '|e|1|6|', '|l|-|0|', '|x|5|3|',
            '|p|)|B|', '|k|)|R|', '|m|+|7|', '|n|&|4|', '|q|#|8|'
        ];
        return $separators[array_rand($separators)];
    }
    
    // 混淆字符串为pack调用
    private function obfuscateStringToPack($str, $arrayName, $index) {
        if (rand(0, 1)) {
            // 使用复杂的数组索引表达式
            $complexIndex = $this->generateComplexMathExpression($index);
            return "pack(\$GLOBALS[$arrayName][$complexIndex],\$GLOBALS[$arrayName][" . $this->generateComplexMathExpression($index + 1) . "])";
        } else {
            // 使用call_user_func形式
            return "call_user_func(function(\$rencv5_h,\$rencv5_c){return pack(\$rencv5_h,\$rencv5_c);},\$GLOBALS[$arrayName][$index],\$GLOBALS[$arrayName][" . ($index + 1) . "])";
        }
    }
    
    // 生成复杂的条件判断和goto结构
    private function generateComplexControlFlow($code) {
        $lines = explode("\n", trim($code));
        $obfuscatedCode = '';
        
        foreach ($lines as $lineNum => $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '//') === 0 || strpos($line, '/*') === 0) {
                continue;
            }
            
            // 为每行代码添加复杂的goto结构
            $label1 = $this->generateComplexVarName();
            $label2 = $this->generateComplexVarName();
            $condVar = '$' . $this->generateComplexVarName();
            $tempVar = '$' . $this->generateComplexVarName();
            
            $obfuscatedCode .= "unset($tempVar);$tempVar=$line;if($condVar){goto $label1;}goto $label2;\n";
            $obfuscatedCode .= "$label1:unset(\$" . $this->generateComplexVarName() . ");return;goto " . $this->generateComplexVarName() . ";\n";
            $obfuscatedCode .= "$label2:\n";
            
            // 添加一些干扰代码
            if (rand(0, 2) == 0) {
                $junkVar1 = '$' . $this->generateComplexVarName();
                $junkVar2 = '$' . $this->generateComplexVarName();
                $obfuscatedCode .= "unset($junkVar1);$junkVar1=call_user_func_array(\"gettype\",array(" . rand(1, 10) . "));";
                $obfuscatedCode .= "$junkVar2=\$" . $this->generateComplexVarName() . "==\"" . chr(rand(65, 90)) . "_\";";
                $obfuscatedCode .= "if($junkVar2){unset($junkVar2);}else{unset($junkVar1);}\n";
            }
        }
        
        return $obfuscatedCode;
    }
    
    // 混淆字符串字面量
    private function obfuscateStringLiterals($code) {
        // 查找所有字符串字面量
        preg_match_all('/"([^"\\\\]*(\\\\.[^"\\\\]*)*)"/', $code, $matches);
        $strings = array_unique($matches[1]);
        
        if (!empty($strings)) {
            $arrayName = $this->arrayNamePatterns[array_rand($this->arrayNamePatterns)];
            $separator = $this->generateSeparator();
            $this->createHexStringArray($strings, $arrayName, $separator);
            
            // 替换字符串为混淆的pack调用
            foreach ($strings as $index => $str) {
                $packCall = $this->obfuscateStringToPack($str, $arrayName, $index * 2);
                $code = str_replace('"' . $str . '"', $packCall, $code);
            }
        }
        
        return $code;
    }
    
    // 创建全局数组定义代码
    private function generateGlobalArrayDefinitions() {
        $definitions = '';
        
        foreach ($this->globalArrays as $arrayName => $data) {
            $constName = strtoupper($arrayName);
            $constValue = strtoupper($this->generateComplexVarName());
            
            $definitions .= "if(!defined(\"$arrayName\"))define(\"$arrayName\",\"$constValue\");\n";
            $definitions .= "\$GLOBALS[$arrayName]=explode('{$data['separator']}','{$data['data']}');\n";
        }
        
        return $definitions;
    }
    
    // 生成复杂的变量赋值和引用结构
    private function generateComplexVariableStructure($varName, $value) {
        $tempVars = [];
        for ($i = 0; $i < rand(3, 6); $i++) {
            $tempVars[] = '$' . $this->generateComplexVarName();
        }
        
        $structure = '';
        
        // 创建复杂的赋值链
        $structure .= "unset({$tempVars[0]});{$tempVars[0]}=array();\n";
        $structure .= "{$tempVars[1]}={$tempVars[0]};\n";
        $structure .= "if(is_array({$tempVars[1]})){goto " . $this->generateComplexVarName() . ";}\n";
        $structure .= $this->generateComplexVarName() . ":unset({$tempVars[2]});\n";
        $structure .= "{$tempVars[2]}=array();{$tempVars[1]}={$tempVars[2]};\n";
        $structure .= "unset({$tempVars[3]});{$tempVars[3]}=&{$tempVars[1]}[" . $this->obfuscateStringToPack('key', $this->arrayNamePatterns[0], 0) . "];\n";
        $structure .= "$varName=&{$tempVars[3]};\n";
        
        // 添加复杂的条件检查
        $checkVar = '$' . $this->generateComplexVarName();
        $structure .= "unset({$checkVar});{$checkVar}=array();{$checkVar}[]=&\$GLOBALS;\n";
        $structure .= '$' . $this->generateComplexVarName() . "=call_user_func_array(\"is_array\",{$checkVar});\n";
        
        return $structure;
    }
    
    // 主编码函数
    public function encode($sourceCode) {
        // 移除PHP标签
        $code = preg_replace('/^<\?php\s*/', '', $sourceCode);
        $code = preg_replace('/\?\>\s*$/', '', $code);
        
        // 1. 混淆字符串字面量
        $code = $this->obfuscateStringLiterals($code);
        
        // 2. 混淆变量名
        $code = $this->obfuscateVariables($code);
        
        // 3. 添加复杂的控制流
        $code = $this->generateComplexControlFlow($code);
        
        // 4. 生成最终的混淆代码
        $finalCode = $this->buildFinalObfuscatedCode($code);
        
        return $finalCode;
    }
    
    // 构建最终的混淆代码
    private function buildFinalObfuscatedCode($code) {
        $result = "<?php\n";
        $result .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
        
        // 生成全局数组定义
        $this->initializeDefaultArrays();
        $result .= $this->generateGlobalArrayDefinitions();
        
        // 添加复杂的初始化检查
        $result .= $this->generateInitializationChecks();
        
        // 主代码体
        $result .= $this->wrapCodeInComplexStructure($code);
        
        return $result;
    }
    
    // 初始化默认数组
    private function initializeDefaultArrays() {
        // 创建一些基础字符串数组
        $basicStrings = [
            'define', 'pack', 'explode', 'function', 'call_user_func',
            'call_user_func_array', 'isset', 'unset', 'array', 'hex2bin'
        ];
        
        $arrayName = $this->arrayNamePatterns[0];
        $separator = '|r|1|*|';
        $this->createHexStringArray($basicStrings, $arrayName, $separator);
        
        // 创建更多数组
        $utilStrings = [
            'is_array', 'gettype', 'strlen', 'strpos', 'substr', 'trim',
            'addslashes', 'json_encode', 'base64_decode', 'time'
        ];
        
        $arrayName2 = $this->arrayNamePatterns[1];
        $separator2 = '|o|1|C|';
        $this->createHexStringArray($utilStrings, $arrayName2, $separator2);
    }
    
    // 生成初始化检查代码
    private function generateInitializationChecks() {
        $code = '';
        $arrayName = $this->arrayNamePatterns[0];
        
        $code .= "if(!defined(pack(\$GLOBALS[$arrayName][" . $this->generateComplexMathExpression(0) . "],\$GLOBALS[$arrayName][1])))";
        $code .= "call_user_func(pack(\$GLOBALS[$arrayName][" . $this->generateComplexMathExpression(0) . "], \$GLOBALS[$arrayName][2]),";
        $code .= "pack(\$GLOBALS[$arrayName][" . $this->generateComplexMathExpression(0) . "],\$GLOBALS[$arrayName][1]),";
        $code .= "pack(\$GLOBALS[$arrayName][" . $this->generateComplexMathExpression(0) . "], \$GLOBALS[$arrayName][" . $this->generateComplexMathExpression(3) . "]));\n";
        
        // 添加DAFA_DEC全局变量初始化
        $code .= "\$GLOBALS[DAFA_DEC]=array(&\$_POST);\n";
        
        return $code;
    }
    
    // 将代码包装在复杂结构中
    private function wrapCodeInComplexStructure($code) {
        $wrapper = '';
        
        // 添加require_once混淆调用
        $arrayName = $this->arrayNamePatterns[1];
        $wrapper .= "require_once pack(\$GLOBALS[$arrayName][" . $this->generateComplexMathExpression(2560) . "-E_USER_WARNING-2048],\$GLOBALS[$arrayName][" . $this->generateComplexMathExpression(-16351) . "-E_CORE_WARNING+8192*E_WARNING]);\n";
        
        // 添加复杂的变量初始化
        $wrapper .= "unset(\$" . $this->generateComplexVarName() . ");\$" . $this->generateComplexVarName() . "=new User_Model();\n";
        $wrapper .= "\$User_Model=\$" . $this->generateComplexVarName() . ";\n";
        
        // 添加主要的混淆逻辑
        $wrapper .= $this->generateMainObfuscatedLogic($code);
        
        return $wrapper;
    }
    
    // 生成主要的混淆逻辑
    private function generateMainObfuscatedLogic($originalCode) {
        $logic = '';
        
        // 创建复杂的条件检查
        $checkVar = '$' . $this->generateComplexVarName();
        $tempVar = '$' . $this->generateComplexVarName();
        $arrayVar = '$' . $this->generateComplexVarName();
        
        $logic .= "unset($checkVar);$checkVar=isset(\$GLOBALS[DAFA_DEC][" . $this->generateComplexMathExpression(-4098) . "+E_WARNING+8*E_USER_WARNING][pack(\$GLOBALS[" . $this->arrayNamePatterns[1] . "][" . $this->generateComplexMathExpression(-4096) . "+E_RECOVERABLE_ERROR)/1024],\$GLOBALS[" . $this->arrayNamePatterns[1] . "][" . $this->generateComplexMathExpression(-262398) . "+E_USER_ERROR+1024*E_USER_ERROR])]);\n";
        
        $logic .= "\$" . $this->generateComplexVarName() . "=$checkVar;\n";
        
        // 添加复杂的数组操作
        $logic .= "unset($arrayVar);$arrayVar=array();\$" . $this->generateComplexVarName() . "[]=\"&\$GLOBALS\";\n";
        $logic .= '$' . $this->generateComplexVarName() . "=call_user_func_array(\"is_array\",$arrayVar);\n";
        
        // 添加goto标签和跳转
        $label1 = $this->generateComplexVarName();
        $label2 = $this->generateComplexVarName();
        
        $logic .= "if(\$" . $this->generateComplexVarName() . "){goto $label1;}goto $label2;\n";
        $logic .= "$label1:\$" . $this->generateComplexVarName() . "=&\$GLOBALS[" . $this->arrayNamePatterns[1] . "];goto " . $this->generateComplexVarName() . ";\n";
        $logic .= "$label2:\$" . $this->generateComplexVarName() . "=\$GLOBALS[" . $this->arrayNamePatterns[1] . "];\n";
        
        // 插入原始代码的混淆版本
        $logic .= $this->deepObfuscateCode($originalCode);
        
        return $logic;
    }
    
    // 深度混淆代码
    private function deepObfuscateCode($code) {
        $obfuscated = '';
        
        // 将代码分解为语句
        $statements = explode(';', $code);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) continue;
            
            // 为每个语句创建复杂的包装
            $obfuscated .= $this->wrapStatementInComplexStructure($statement);
        }
        
        return $obfuscated;
    }
    
    // 将语句包装在复杂结构中
    private function wrapStatementInComplexStructure($statement) {
        $wrapper = '';
        
        // 生成复杂的变量结构
        $varName = '$' . $this->generateComplexVarName();
        $tempVar1 = '$' . $this->generateComplexVarName();
        $tempVar2 = '$' . $this->generateComplexVarName();
        $arrayVar = '$' . $this->generateComplexVarName();
        
        $wrapper .= "unset({$tempVar1});{$tempVar1}=array();{$tempVar1}[]=&\$GLOBALS;\n";
        $wrapper .= '$' . $this->generateComplexVarName() . "=call_user_func_array(\"is_array\",{$tempVar1});\n";
        
        $label1 = $this->generateComplexVarName();
        $label2 = $this->generateComplexVarName();
        
        $wrapper .= "if(\$" . $this->generateComplexVarName() . "){goto $label1;}goto $label2;\n";
        $wrapper .= "$label1:unset({$tempVar2});{$tempVar2}=&\$GLOBALS[" . $this->arrayNamePatterns[1] . "];goto " . $this->generateComplexVarName() . ";\n";
        $wrapper .= "$label2:{$tempVar2}=\$GLOBALS[" . $this->arrayNamePatterns[1] . "];\n";
        
        // 添加原始语句
        $wrapper .= $statement . ";\n";
        
        // 添加一些后续的混淆代码
        $wrapper .= "unset({$arrayVar});{$arrayVar}=array();{$arrayVar}[]=&{$tempVar2};\n";
        $wrapper .= "unset({$varName});{$varName}=" . $this->generateComplexMathExpression(rand(1000, 9999)) . ";\n";
        
        return $wrapper;
    }
    
    // 混淆变量名（增强版）
    private function obfuscateVariables($code) {
        preg_match_all('/\$([a-zA-Z_][a-zA-Z0-9_]*)/', $code, $matches);
        $variables = array_unique($matches[1]);
        
        foreach ($variables as $var) {
            if (!in_array($var, ['_GET', '_POST', '_SESSION', '_COOKIE', '_SERVER', '_FILES', 'GLOBALS'])) {
                $newVar = '$' . $this->generateComplexVarName();
                $code = str_replace('$' . $var, $newVar, $code);
                $this->obfuscatedVars[$var] = $newVar;
            }
        }
        
        return $code;
    }
    
    // 创建Loader解密器
    public function createLoader() {
        return '<?php
/**
 * Ranyun_JiaMi Loader
 * 解密器 - 用于运行加密后的PHP代码
 */

class RanyunLoader {
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // 解码十六进制字符串
    public static function hexDecode($hex) {
        return hex2bin($hex);
    }
    
    // 从全局数组中获取解码后的字符串
    public static function getDecodedString($arrayName, $index) {
        if (isset($GLOBALS[$arrayName][$index])) {
            return self::hexDecode($GLOBALS[$arrayName][$index]);
        }
        return "";
    }
    
    // 执行混淆后的代码
    public static function execute($obfuscatedCode) {
        // 设置错误报告级别
        error_reporting(0);
        
        // 执行代码
        eval($obfuscatedCode);
    }
    
    // 验证代码完整性
    public static function verifyIntegrity($code) {
        // 检查是否包含必要的混淆标记
        if (strpos($code, "Ranyun_JiaMi") === false) {
            die("Invalid encrypted code");
        }
        
        if (strpos($code, "GLOBALS") === false) {
            die("Corrupted encrypted code");
        }
        
        return true;
    }
}

// 自动加载函数
function __autoload($className) {
    $loader = RanyunLoader::getInstance();
    // 这里可以添加类文件的自动加载逻辑
}

// 如果直接访问此文件，显示版权信息
if (__FILE__ == $_SERVER["SCRIPT_FILENAME"]) {
    echo "Ranyun_JiaMi Loader - 版权所有\\n";
    echo "此文件用于加载和执行加密后的PHP代码\\n";
}
?>';
    }
    
    // 生成测试用的简单加密代码
    public function generateTestEncryption($simpleCode) {
        $testCode = "<?php\n";
        $testCode .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
        
        // 创建基础的全局数组
        $testCode .= "if(!defined(\"A__AAAA_A\"))define(\"A__AAAA_A\",\"CFA__ACCE\");\n";
        $testCode .= "\$GLOBALS[A__AAAA_A]=explode('|r|1|*|', 'H*|r|1|*|" . bin2hex('define') . "|r|1|*|" . bin2hex('pack') . "|r|1|*|" . bin2hex('explode') . "');\n";
        
        $testCode .= "if(!defined(\"CF__C_AA\"))define(\"CF__C_AA\",\"AEEFDLLL\");\n";
        $testCode .= "\$GLOBALS[CF__C_AA]=explode('|o|1|C|','H*|o|1|C|" . bin2hex('echo') . "|o|1|C|" . bin2hex('print') . "|o|1|C|" . bin2hex('var_dump') . "');\n";
        
        // 添加主要逻辑
        $mainVar = '$' . $this->generateComplexVarName();
        $testCode .= "unset($mainVar);$mainVar=0;\n";
        $testCode .= "if($mainVar){goto " . $this->generateComplexVarName() . ";}goto " . $this->generateComplexVarName() . ";\n";
        
        // 插入原始代码的简化混淆版本
        $lines = explode("\n", trim($simpleCode));
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && strpos($line, '<?php') === false && strpos($line, '?>') === false) {
                $testCode .= $this->createSimpleObfuscatedStatement($line) . "\n";
            }
        }
        
        return $testCode;
    }
    
    // 创建简单的混淆语句
    private function createSimpleObfuscatedStatement($statement) {
        $varName = '$' . $this->generateComplexVarName();
        $checkVar = '$' . $this->generateComplexVarName();
        
        $obfuscated = "unset($varName);$varName=\"$statement\";\n";
        $obfuscated .= "unset($checkVar);$checkVar=true;\n";
        $obfuscated .= "if($checkVar){eval($varName);}else{unset($varName);}\n";
        
        return $obfuscated;
    }
}

// 创建编码器实例
$encoder = new AdvancedPHPEncoder();

// 处理POST请求
if (isset($_POST['source_code']) && !empty($_POST['source_code'])) {
    $sourceCode = $_POST['source_code'];
    $encodedCode = $encoder->encode($sourceCode);
    $loader = $encoder->createLoader();
    
    echo "<div style='margin: 20px;'>";
    echo "<h3>加密后的代码:</h3>";
    echo "<textarea rows='25' cols='120' style='font-family: monospace; font-size: 12px;'>" . htmlspecialchars($encodedCode) . "</textarea>";
    
    echo "<h3>Loader解密器代码:</h3>";
    echo "<textarea rows='15' cols='120' style='font-family: monospace; font-size: 12px;'>" . htmlspecialchars($loader) . "</textarea>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ranyun_JiaMi PHP高级代码加密器</title>
    <style>
        body { 
            font-family: 'Microsoft YaHei', Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f5f5f5; 
        }
        .container { 
            max-width: 1400px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        h1 { 
            color: #333; 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #007cba; 
            padding-bottom: 10px; 
        }
        textarea { 
            width: 100%; 
            font-family: 'Consolas', 'Monaco', monospace; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            padding: 10px; 
            resize: vertical; 
        }
        .btn { 
            background: linear-gradient(45deg, #007cba, #005a87); 
            color: white; 
            padding: 12px 30px; 
            border: none; 
            cursor: pointer; 
            border-radius: 5px; 
            font-size: 16px; 
            font-weight: bold; 
        }
        .btn:hover { 
            background: linear-gradient(45deg, #005a87, #003d5c); 
            transform: translateY(-1px); 
        }
        .info { 
            background: #e7f3ff; 
            border: 1px solid #b3d9ff; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 20px 0; 
        }
        .warning { 
            background: #fff3cd; 
            border: 1px solid #ffeaa7; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 20px 0; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Ranyun_JiaMi PHP高级代码加密器</h1>
        
        <div class="info">
            <strong>功能特点:</strong>
            <ul>
                <li>🎯 完全模仿示例代码的混淆格式和算法</li>
                <li>🔄 变量名高度混淆，使用复杂的命名规则</li>
                <li>📊 字符串十六进制编码，存储在全局数组中</li>
                <li>🌀 复杂的goto控制流混淆</li>
                <li>🧮 数字替换为复杂的数学表达式</li>
                <li>🔧 函数调用转换为call_user_func_array形式</li>
                <li>🗃️ 生成配套的Loader解密器</li>
            </ul>
        </div>
        
        <div class="warning">
            <strong>⚠️ 注意事项:</strong>
            <ul>
                <li>加密后的代码体积会显著增大</li>
                <li>执行性能会有所下降</li>
                <li>请保存好Loader解密器代码</li>
                <li>建议先用简单代码测试</li>
            </ul>
        </div>
        
        <form method="post">
            <h3>📝 输入要加密的PHP代码:</h3>
            <textarea name="source_code" rows="20" placeholder="<?php
// 示例代码
$username = $_POST['username'];
$password = $_POST['password'];

if (!empty($username) && !empty($password)) {
    echo 'Login successful';
} else {
    echo 'Please enter username and password';
}
?>"><?php echo isset($_POST['source_code']) ? htmlspecialchars($_POST['source_code']) : ''; ?></textarea>
            <br><br>
            <button type="submit" class="btn">🚀 开始加密</button>
        </form>
        
        <div style="margin-top: 30px; text-align: center; color: #666; font-size: 14px;">
            <p>© 2024 Ranyun_JiaMi - PHP代码保护专家</p>
        </div>
    </div>
</body>
</html>