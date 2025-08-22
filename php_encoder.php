<?php
/**
 * Ranyun_JiaMi PHP代码加密器
 * 版权所有
 */

class PHPEncoder {
    private $globalVars = [];
    private $varCounter = 0;
    private $stringArrays = [];
    private $arrayCounter = 0;
    
    // 生成随机变量名
    private function generateVarName() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $name = '';
        for ($i = 0; $i < 3; $i++) {
            $name .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $name . 'x' . strtoupper(dechex(rand(1000, 9999)));
    }
    
    // 生成全局数组名
    private function generateGlobalArrayName() {
        $patterns = [
            'A__AAAA_A', 'CF__C_AA', '__DFEA_FF', 'DCBFBNDN_', 'E__AWA_ABC_',
            'DAFA_DEC', '_CCBAA__', 'FA_AE_E_', '_BAZ_ABF', 'BAEEACFA'
        ];
        return $patterns[array_rand($patterns)] . '_' . $this->arrayCounter++;
    }
    
    // 将字符串转换为十六进制
    private function stringToHex($str) {
        return bin2hex($str);
    }
    
    // 创建混淆的字符串数组
    private function createObfuscatedStringArray($strings) {
        $arrayName = $this->generateGlobalArrayName();
        $separator = '|' . chr(rand(97, 122)) . '|' . rand(1, 9) . '|' . chr(rand(65, 90)) . '|';
        
        $hexStrings = array_map([$this, 'stringToHex'], $strings);
        $arrayData = 'H*' . $separator . implode($separator, $hexStrings);
        
        $this->stringArrays[$arrayName] = [
            'separator' => $separator,
            'data' => $arrayData,
            'strings' => $strings
        ];
        
        return $arrayName;
    }
    
    // 生成复杂的数学表达式来替代简单数字
    private function obfuscateNumber($num) {
        $operations = [
            function($n) { return "(-" . ($n + 1000) . "+E_ERROR+" . (1000 + rand(1, 100)) . "*E_WARNING)"; },
            function($n) { return "(" . ($n * 2) . "-E_STRICT-" . rand(1, 100) . ")"; },
            function($n) { return "((" . ($n + 5000) . "+E_NOTICE)/" . (5000 / $n + 1) . ")"; },
            function($n) { return "(-" . ($n + 2048) . "+E_RECOVERABLE_ERROR+" . (2048 + rand(1, 100)) . "*E_COMPILE_WARNING)"; }
        ];
        
        $operation = $operations[array_rand($operations)];
        return $operation($num);
    }
    
    // 混淆变量名
    private function obfuscateVariables($code) {
        // 提取所有变量
        preg_match_all('/\$([a-zA-Z_][a-zA-Z0-9_]*)/', $code, $matches);
        $variables = array_unique($matches[1]);
        
        $varMap = [];
        foreach ($variables as $var) {
            if (!in_array($var, ['_GET', '_POST', '_SESSION', '_COOKIE', '_SERVER', '_FILES', 'GLOBALS'])) {
                $varMap['$' . $var] = '$' . $this->generateVarName();
            }
        }
        
        // 替换变量名
        foreach ($varMap as $old => $new) {
            $code = str_replace($old, $new, $code);
        }
        
        return $code;
    }
    
    // 创建goto标签混淆
    private function createGotoObfuscation($code) {
        $labels = [];
        $labelCounter = 0;
        
        // 为每个代码块创建goto标签
        $lines = explode("\n", $code);
        $obfuscatedLines = [];
        
        foreach ($lines as $line) {
            if (trim($line) && !preg_match('/^\s*(\/\/|\/\*|\*)/', $line)) {
                $label = $this->generateVarName() . ':';
                $obfuscatedLines[] = $line . 'goto ' . $this->generateVarName() . ';';
                $labels[] = $label;
            } else {
                $obfuscatedLines[] = $line;
            }
        }
        
        return implode("\n", $obfuscatedLines);
    }
    
    // 混淆函数调用
    private function obfuscateFunctionCalls($code) {
        // 将简单的函数调用转换为call_user_func_array形式
        $patterns = [
            '/\bpack\s*\(([^)]+)\)/' => 'call_user_func_array("pack",array($1))',
            '/\bexplode\s*\(([^)]+)\)/' => 'call_user_func_array("explode",array($1))',
            '/\bdefined\s*\(([^)]+)\)/' => 'call_user_func("defined",$1)',
            '/\bisset\s*\(([^)]+)\)/' => 'call_user_func_array("isset",array($1))',
            '/\bempty\s*\(([^)]+)\)/' => 'call_user_func("empty",$1)'
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $code = preg_replace($pattern, $replacement, $code);
        }
        
        return $code;
    }
    
    // 添加无用代码和条件判断
    private function addJunkCode($code) {
        $junkPatterns = [
            'unset($' . $this->generateVarName() . ');',
            '$' . $this->generateVarName() . '=call_user_func_array("gettype",array(' . rand(1, 10) . '));',
            '$' . $this->generateVarName() . '=$' . $this->generateVarName() . '=="' . chr(rand(65, 90)) . '_";',
            'if($' . $this->generateVarName() . '){unset($' . $this->generateVarName() . ');}else{unset($' . $this->generateVarName() . ');}',
        ];
        
        $lines = explode("\n", $code);
        $obfuscatedLines = [];
        
        foreach ($lines as $line) {
            $obfuscatedLines[] = $line;
            if (rand(1, 3) == 1) {
                $obfuscatedLines[] = $junkPatterns[array_rand($junkPatterns)];
            }
        }
        
        return implode("\n", $obfuscatedLines);
    }
    
    // 主加密函数
    public function encode($sourceCode) {
        // 移除PHP开始标签
        $code = preg_replace('/^<\?php\s*/', '', $sourceCode);
        
        // 1. 混淆变量名
        $code = $this->obfuscateVariables($code);
        
        // 2. 混淆函数调用
        $code = $this->obfuscateFunctionCalls($code);
        
        // 3. 添加无用代码
        $code = $this->addJunkCode($code);
        
        // 4. 创建全局数组定义
        $globalArrays = $this->generateGlobalArrayDefinitions();
        
        // 5. 组装最终代码
        $finalCode = "<?php\n";
        $finalCode .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
        $finalCode .= $globalArrays;
        $finalCode .= $this->wrapInObfuscatedStructure($code);
        
        return $finalCode;
    }
    
    // 生成全局数组定义
    private function generateGlobalArrayDefinitions() {
        $definitions = '';
        
        // 创建多个混淆的全局数组
        for ($i = 0; $i < 5; $i++) {
            $arrayName = $this->generateGlobalArrayName();
            $constName = strtoupper($arrayName);
            
            // 创建一些示例字符串
            $strings = [
                'function', 'define', 'pack', 'explode', 'isset', 'unset', 'array',
                'call_user_func', 'call_user_func_array', 'hex2bin', 'bin2hex'
            ];
            
            $separator = '|' . chr(rand(97, 122)) . '|' . rand(1, 9) . '|' . chr(rand(65, 90)) . '|';
            $hexStrings = array_map([$this, 'stringToHex'], $strings);
            $arrayData = 'H*' . $separator . implode($separator, $hexStrings);
            
            $definitions .= "if(!defined(\"$constName\"))define(\"$constName\",\"" . strtoupper($this->generateVarName()) . "\");\n";
            $definitions .= "\$GLOBALS[$constName]=explode('$separator','$arrayData');\n";
        }
        
        return $definitions;
    }
    
    // 将代码包装在混淆结构中
    private function wrapInObfuscatedStructure($code) {
        $wrapperStart = '';
        $wrapperEnd = '';
        
        // 创建复杂的条件判断和goto结构
        $labelStart = $this->generateVarName();
        $labelEnd = $this->generateVarName();
        $varCheck = '$' . $this->generateVarName();
        
        $wrapperStart .= "$varCheck=0;if($varCheck){goto $labelStart;}goto $labelEnd;\n";
        $wrapperStart .= "$labelStart:unset(\$" . $this->generateVarName() . ");return;goto " . $this->generateVarName() . ";\n";
        $wrapperStart .= "$labelEnd:\n";
        
        // 添加一些混淆的检查
        $wrapperStart .= "if((int)true){\$" . $this->generateVarName() . "=1655649816;\$" . $this->generateVarName() . "='10:43:36';";
        $wrapperStart .= "if(!(int)false)\$" . $this->generateVarName() . "=&\$" . $this->generateVarName() . ";else unset(\$" . $this->generateVarName() . ");";
        $wrapperStart .= "\$" . $this->generateVarName() . "=\$" . $this->generateVarName() . "<\$" . $this->generateVarName() . ";";
        $wrapperStart .= "if(\$" . $this->generateVarName() . "){unset(\$" . $this->generateVarName() . ");}else{\$" . $this->generateVarName() . "=\$" . $this->generateVarName() . ";}}\n";
        
        return $wrapperStart . $code . $wrapperEnd;
    }
    
    // 创建解码函数
    public function createDecoder() {
        return '
function decode_string($encoded, $key) {
    $decoded = "";
    $keyLen = strlen($key);
    for ($i = 0; $i < strlen($encoded); $i++) {
        $decoded .= chr(ord($encoded[$i]) ^ ord($key[$i % $keyLen]));
    }
    return $decoded;
}

function hex_decode($hex) {
    return hex2bin($hex);
}

function array_decode($arrayName, $index) {
    global $GLOBALS;
    if (isset($GLOBALS[$arrayName][$index])) {
        return hex_decode($GLOBALS[$arrayName][$index]);
    }
    return "";
}
';
    }
}

// 使用示例
if (isset($_POST['source_code'])) {
    $encoder = new PHPEncoder();
    $sourceCode = $_POST['source_code'];
    $encodedCode = $encoder->encode($sourceCode);
    
    echo "<h3>加密后的代码:</h3>";
    echo "<textarea rows='20' cols='100'>" . htmlspecialchars($encodedCode) . "</textarea>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP代码加密器</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        textarea { width: 100%; font-family: monospace; }
        .btn { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .btn:hover { background: #005a87; }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP代码加密器 - Ranyun_JiaMi</h1>
        <form method="post">
            <h3>输入要加密的PHP代码:</h3>
            <textarea name="source_code" rows="15" placeholder="<?php
// 在这里输入您的PHP代码
echo 'Hello World';
?>"><?php echo isset($_POST['source_code']) ? htmlspecialchars($_POST['source_code']) : ''; ?></textarea>
            <br><br>
            <button type="submit" class="btn">加密代码</button>
        </form>
    </div>
</body>
</html>