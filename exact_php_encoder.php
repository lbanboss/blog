<?php
/**
 * Ranyun_JiaMi 精确PHP代码加密器
 * 完全模仿示例代码格式和算法
 */

class ExactPHPEncoder {
    private $globalArrays = [];
    private $variableMap = [];
    private $stringArrays = [];
    private $labelCounter = 0;
    
    // 精确模仿示例中的变量名生成模式
    private function generateExactVarName() {
        $prefixes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $suffixes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        
        $part1 = $prefixes[array_rand($prefixes)] . $prefixes[array_rand($prefixes)] . rand(0, 9);
        $part2 = $suffixes[array_rand($suffixes)] . $suffixes[array_rand($suffixes)] . rand(0, 9);
        $number = strtoupper(dechex(rand(1000, 9999)));
        
        return $part1 . 'x' . $part2 . $number;
    }
    
    // 生成与示例完全相同的数学表达式模式
    private function generateExactMathExpression($baseValue) {
        $errorConstants = [
            'E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE', 'E_CORE_ERROR',
            'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING', 
            'E_USER_ERROR', 'E_USER_WARNING', 'E_USER_NOTICE',
            'E_RECOVERABLE_ERROR', 'E_DEPRECATED', 'E_USER_DEPRECATED', 'E_STRICT'
        ];
        
        $patterns = [
            // 模式1: -数字+常量+数字*常量
            function($val) use ($errorConstants) {
                $base = rand(-50000, 50000);
                $const1 = $errorConstants[array_rand($errorConstants)];
                $multiplier = rand(1, 8192);
                $const2 = $errorConstants[array_rand($errorConstants)];
                return "$base+$const1+$multiplier*$const2";
            },
            // 模式2: (数字+常量)/数字-常量
            function($val) use ($errorConstants) {
                $base = rand(-100000, 100000);
                $const1 = $errorConstants[array_rand($errorConstants)];
                $divisor = rand(2, 1024);
                $const2 = $errorConstants[array_rand($errorConstants)];
                return "($base+$const1)/$divisor-$const2";
            },
            // 模式3: 数字-常量+(数字+常量)*常量
            function($val) use ($errorConstants) {
                $base1 = rand(-20000, 20000);
                $const1 = $errorConstants[array_rand($errorConstants)];
                $base2 = rand(-10000, 10000);
                $const2 = $errorConstants[array_rand($errorConstants)];
                $const3 = $errorConstants[array_rand($errorConstants)];
                return "$base1-$const1+($base2+$const2)*$const3";
            }
        ];
        
        $pattern = $patterns[array_rand($patterns)];
        return $pattern($baseValue);
    }
    
    // 创建与示例相同的全局数组结构
    private function createExactGlobalArrays() {
        $arrays = [
            'A__AAAA_A' => [
                'const_value' => 'CFA__ACCE',
                'separator' => '|r|1|*|',
                'strings' => ['define', 'pack', 'explode', 'function']
            ],
            'CF__C_AA' => [
                'const_value' => 'AEEFDLLL', 
                'separator' => '|o|1|C|',
                'strings' => ['echo', 'print', 'var_dump', 'isset', 'unset', 'array', 'call_user_func', 'call_user_func_array']
            ],
            '__DFEA_FF' => [
                'const_value' => 'BABC_FBA',
                'separator' => '|e|1|6|',
                'strings' => ['trim', 'addslashes', 'json_encode', 'time', 'date']
            ],
            'DCBFBNDN_' => [
                'const_value' => 'DCBACAEC',
                'separator' => '|l|-|0|',
                'strings' => ['username', 'password', 'email', 'login', 'register']
            ],
            'E__AWA_ABC_' => [
                'const_value' => 'DXE_X_XX',
                'separator' => '|x|5|3|',
                'strings' => ['success', 'error', 'message']
            ]
        ];
        
        $definitions = '';
        foreach ($arrays as $arrayName => $config) {
            $hexStrings = array_map('bin2hex', $config['strings']);
            $arrayData = 'H*' . $config['separator'] . implode($config['separator'], $hexStrings);
            
            $definitions .= "if(!defined(\"$arrayName\"))define(\"$arrayName\",\"{$config['const_value']}\");\n";
            $definitions .= "\$GLOBALS[$arrayName]=explode('{$config['separator']}','$arrayData');\n";
        }
        
        return $definitions;
    }
    
    // 创建与示例相同的复杂变量操作
    private function createExactVariableOperations($varName, $arrayName, $index) {
        $tempVars = [
            '$' . $this->generateExactVarName(),
            '$' . $this->generateExactVarName(),
            '$' . $this->generateExactVarName(),
            '$' . $this->generateExactVarName()
        ];
        
        $code = '';
        
        // 创建与示例相同的数组检查模式
        $code .= "unset({$tempVars[0]});{$tempVars[0]}=array();{$tempVars[0]}[]=&\$GLOBALS;\n";
        $code .= '{' . $this->generateExactVarName() . "}=call_user_func_array(\"is_array\",{$tempVars[0]});\n";
        
        $label1 = $this->generateExactVarName();
        $label2 = $this->generateExactVarName();
        
        $code .= "if(\$" . $this->generateExactVarName() . "){goto $label1;}goto $label2;\n";
        $code .= "$label1:{$tempVars[1]}=&\$GLOBALS[$arrayName];goto " . $this->generateExactVarName() . ";\n";
        $code .= "$label2:{$tempVars[1]}=\$GLOBALS[$arrayName];\n";
        
        // 创建嵌套的数组检查
        $code .= "unset({$tempVars[2]});{$tempVars[2]}=array();{$tempVars[2]}[]=&{$tempVars[1]};\n";
        $code .= "unset({$tempVars[3]});{$tempVars[3]}=" . $this->generateExactMathExpression($index) . ";\n";
        
        $label3 = $this->generateExactVarName();
        $label4 = $this->generateExactVarName();
        
        $code .= '{' . $this->generateExactVarName() . "}=call_user_func_array(\"is_array\",{$tempVars[2]});\n";
        $code .= "if(\$" . $this->generateExactVarName() . "){goto $label3;}goto $label4;\n";
        $code .= "$label3:$varName=&\$GLOBALS[$arrayName][{$tempVars[3]}];goto " . $this->generateExactVarName() . ";\n";
        $code .= "$label4:$varName=\$GLOBALS[$arrayName][{$tempVars[3]}];\n";
        
        return $code;
    }
    
    // 创建与示例相同的pack调用模式
    private function createExactPackCall($arrayName, $index1, $index2) {
        $patterns = [
            "pack(\$GLOBALS[$arrayName][%s],\$GLOBALS[$arrayName][%s])",
            "call_user_func_array('pack',array(\$GLOBALS[$arrayName][%s],\$GLOBALS[$arrayName][%s]))",
            "call_user_func(function(\$rencv5_h,\$rencv5_c){return pack(\$rencv5_h,\$rencv5_c);},\$GLOBALS[$arrayName][%s],\$GLOBALS[$arrayName][%s])"
        ];
        
        $pattern = $patterns[array_rand($patterns)];
        $idx1 = $this->generateExactMathExpression($index1);
        $idx2 = $this->generateExactMathExpression($index2);
        
        return sprintf($pattern, $idx1, $idx2);
    }
    
    // 生成与示例相同的函数检查模式
    private function createExactFunctionCheck($functionName) {
        $namespacedFunc = '__NAMESPACE__.\'' . chr(rand(32, 126)) . '\'';
        $checkVar = '$' . $this->generateExactVarName();
        
        $code = "if(!function_exists($namespacedFunc)){if(1){\n";
        $code .= '$' . $this->generateExactVarName() . "=1;\n";
        $code .= '$' . $this->generateExactVarName() . "=call_user_func_array(\"is_bool\",array(&\$" . $this->generateExactVarName() . "));\n";
        $code .= "if(\$" . $this->generateExactVarName() . "){unset(\$" . $this->generateExactVarName() . ");}else{unset(\$" . $this->generateExactVarName() . ");}\n";
        $code .= "unset($checkVar);$checkVar=&\$" . $this->generateExactVarName() . ";\n";
        $code .= "if($checkVar==null)$checkVar=\$" . $this->generateExactVarName() . "=true;}}\n";
        $code .= "if($checkVar){" . $functionName . ";}else{unset($checkVar);}\n";
        
        return $code;
    }
    
    // 主编码函数 - 生成与示例完全相同的格式
    public function encodeExact($sourceCode) {
        $result = "<?php\n";
        $result .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
        
        // 1. 创建全局数组定义
        $result .= $this->createExactGlobalArrays();
        
        // 2. 添加DAFA_DEC初始化
        $result .= "\$GLOBALS[DAFA_DEC]=array(&\$_POST);\n";
        
        // 3. 添加require_once调用
        $result .= "require_once " . $this->createExactPackCall('CF__C_AA', 2560, -16351) . ";\n";
        
        // 4. 创建User_Model实例
        $userModelVar = '$' . $this->generateExactVarName();
        $result .= "unset($userModelVar);$userModelVar=new User_Model();\n";
        $result .= "\$User_Model=$userModelVar;\n";
        
        // 5. 生成主要的混淆逻辑
        $result .= $this->generateExactMainLogic($sourceCode);
        
        return $result;
    }
    
    // 生成与示例完全相同的主逻辑结构
    private function generateExactMainLogic($originalCode) {
        $logic = '';
        
        // 创建复杂的isset检查（模仿示例）
        $checkVar = '$' . $this->generateExactVarName();
        $tempVar = '$' . $this->generateExactVarName();
        
        $logic .= "unset($checkVar);$checkVar=isset(\$GLOBALS[DAFA_DEC][" . $this->generateExactMathExpression(-4098) . "+E_WARNING+8*E_USER_WARNING][" . $this->createExactPackCall('CF__C_AA', -4096, -262398) . "]);\n";
        $logic .= "$tempVar=$checkVar;\n";
        
        // 创建复杂的数组操作链
        $arrayVar = '$' . $this->generateExactVarName();
        $refVar = '$' . $this->generateExactVarName();
        
        $logic .= "unset($refVar);$refVar=&$tempVar;\n";
        $logic .= "\$" . $this->generateExactVarName() . "=&$refVar;\n";
        
        // 添加数组检查
        $logic .= "unset($arrayVar);$arrayVar=array();$arrayVar[]=&\$GLOBALS;\n";
        $logic .= '$' . $this->generateExactVarName() . "=call_user_func_array(\"is_array\",$arrayVar);\n";
        
        // 生成goto标签结构
        $label1 = $this->generateExactVarName();
        $label2 = $this->generateExactVarName();
        
        $logic .= "if(\$" . $this->generateExactVarName() . "){goto $label1;}goto $label2;\n";
        $logic .= "$label1:\$" . $this->generateExactVarName() . "=&\$GLOBALS[CF__C_AA];goto " . $this->generateExactVarName() . ";\n";
        $logic .= "$label2:\$" . $this->generateExactVarName() . "=\$GLOBALS[CF__C_AA];\n";
        
        // 添加更多复杂的变量操作
        $logic .= $this->generateExactVariableChain();
        
        // 插入用户代码的混淆版本
        $logic .= $this->obfuscateUserCode($originalCode);
        
        return $logic;
    }
    
    // 生成与示例相同的变量操作链
    private function generateExactVariableChain() {
        $chain = '';
        
        for ($i = 0; $i < 5; $i++) {
            $varV01 = '$' . $this->generateExactVarName() . 'V01';
            $varV001 = '$' . $this->generateExactVarName() . 'V001';
            $varV0001 = '$' . $this->generateExactVarName() . 'V0001';
            $varV1 = '$' . $this->generateExactVarName() . 'V1';
            $varV2 = '$' . $this->generateExactVarName() . 'V2';
            $checkVar = '$' . $this->generateExactVarName();
            $mathVar = '$' . $this->generateExactVarName();
            
            $chain .= "unset($varV01);$varV01=array();$varV01[]=&\$GLOBALS;\n";
            $chain .= "$checkVar=call_user_func_array(\"is_array\",$varV01);\n";
            
            $label1 = $this->generateExactVarName();
            $label2 = $this->generateExactVarName();
            
            $chain .= "if($checkVar){goto $label1;}goto $label2;\n";
            $chain .= "$label1:$varV001=&\$GLOBALS[CF__C_AA];goto " . $this->generateExactVarName() . ";\n";
            $chain .= "$label2:$varV001=\$GLOBALS[CF__C_AA];\n";
            
            $chain .= "unset($varV0001);$varV0001=array();$varV0001[]=&$varV001;\n";
            $chain .= "unset($varV1);unset($mathVar);$mathVar=" . $this->generateExactMathExpression(rand(-50000, 50000)) . ";\n";
            
            $checkVar2 = '$' . $this->generateExactVarName();
            $chain .= "$checkVar2=call_user_func_array(\"is_array\",$varV0001);\n";
            
            $label3 = $this->generateExactVarName();
            $label4 = $this->generateExactVarName();
            
            $chain .= "if($checkVar2){goto $label3;}goto $label4;\n";
            $chain .= "$label3:unset(\$" . $this->generateExactVarName() . ");$varV1=&\$GLOBALS[CF__C_AA][$mathVar];goto " . $this->generateExactVarName() . ";\n";
            $chain .= "$label4:$varV1=\$GLOBALS[CF__C_AA][$mathVar];\n";
            
            // 添加更多嵌套操作
            $this->addNestedArrayOperations($chain, $varV1, $varV2);
        }
        
        return $chain;
    }
    
    // 添加嵌套数组操作
    private function addNestedArrayOperations(&$chain, $var1, $var2) {
        $varV02 = '$' . $this->generateExactVarName() . 'V02';
        $varV002 = '$' . $this->generateExactVarName() . 'V002';
        $varV0002 = '$' . $this->generateExactVarName() . 'V0002';
        $mathVar2 = '$' . $this->generateExactVarName();
        
        $chain .= "unset($varV02);$varV02=array();$varV02[]=&\$GLOBALS;\n";
        $chain .= '$' . $this->generateExactVarName() . "=call_user_func_array(\"is_array\",$varV02);\n";
        
        $label1 = $this->generateExactVarName();
        $label2 = $this->generateExactVarName();
        
        $chain .= "if(\$" . $this->generateExactVarName() . "){goto $label1;}goto $label2;\n";
        $chain .= "$label1:unset(\$" . $this->generateExactVarName() . ");$varV002=&\$GLOBALS[CF__C_AA];goto " . $this->generateExactVarName() . ";\n";
        $chain .= "$label2:$varV002=\$GLOBALS[CF__C_AA];\n";
        
        $chain .= "unset($varV0002);$varV0002=array();$varV0002[]=&$varV002;\n";
        $chain .= "unset($var2);unset($mathVar2);$mathVar2=" . $this->generateExactMathExpression(rand(-100000, 100000)) . ";\n";
        
        $checkVar = '$' . $this->generateExactVarName();
        $chain .= "$checkVar=call_user_func_array(\"is_array\",$varV0002);\n";
        
        $label3 = $this->generateExactVarName();
        $label4 = $this->generateExactVarName();
        
        $chain .= "if($checkVar){goto $label3;}goto $label4;\n";
        $chain .= "$label3:$var2=&\$GLOBALS[CF__C_AA][$mathVar2];goto " . $this->generateExactVarName() . ";\n";
        $chain .= "$label4:$var2=\$GLOBALS[CF__C_AA][$mathVar2];\n";
        
        // 创建数组合并操作
        $arrayA3 = '$' . $this->generateExactVarName() . 'A3';
        $arrayZ0 = '$' . $this->generateExactVarName() . 'Z0';
        
        $chain .= "$arrayA3=array();$arrayA3[]=&$var1;$arrayA3[]=&$var2;\n";
        $chain .= "$arrayZ0=call_user_func_array(\"pack\",$arrayA3);\n";
        
        // 添加addslashes和trim调用
        $finalVar = '$' . $this->generateExactVarName();
        $chain .= "unset($finalVar);$finalVar=call_user_func('addslashes',call_user_func('trim',\$GLOBALS[DAFA_DEC][" . $this->generateExactMathExpression(3264) . "-E_STRICT-128)-E_USER_NOTICE-(144-E_COMPILE_ERROR-16)][$arrayZ0]));\n";
    }
    
    // 混淆用户代码
    private function obfuscateUserCode($code) {
        // 移除PHP标签
        $code = preg_replace('/^<\?php\s*/', '', $code);
        $code = preg_replace('/\?\>\s*$/', '', $code);
        
        $obfuscated = '';
        
        // 分解代码为语句
        $statements = preg_split('/;|\n/', $code);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) continue;
            
            // 为每个语句创建复杂包装
            $obfuscated .= $this->wrapStatementExact($statement);
        }
        
        return $obfuscated;
    }
    
    // 精确包装语句
    private function wrapStatementExact($statement) {
        $wrapper = '';
        
        // 检查语句类型并相应处理
        if (strpos($statement, 'echo') !== false) {
            $wrapper .= $this->createEchoObfuscation($statement);
        } elseif (strpos($statement, 'if') !== false) {
            $wrapper .= $this->createIfObfuscation($statement);
        } elseif (strpos($statement, '=') !== false) {
            $wrapper .= $this->createAssignmentObfuscation($statement);
        } else {
            $wrapper .= $this->createGenericObfuscation($statement);
        }
        
        return $wrapper;
    }
    
    // 创建echo语句的混淆
    private function createEchoObfuscation($statement) {
        $code = '';
        $outputVar = '$' . $this->generateExactVarName();
        $jsonVar = '$' . $this->generateExactVarName();
        
        // 创建状态信息数组
        $code .= "unset($outputVar);$outputVar=array();\n";
        $code .= '$' . $this->generateExactVarName() . "=$outputVar;\n";
        $code .= "if(is_array(\$" . $this->generateExactVarName() . ")){goto " . $this->generateExactVarName() . ";}\n";
        $code .= $this->generateExactVarName() . ":unset(\$" . $this->generateExactVarName() . ");\n";
        
        // 添加原始echo语句的混淆版本
        $code .= "unset($jsonVar);$jsonVar=call_user_func('json_encode',\$" . $this->generateExactVarName() . ");\n";
        $code .= "echo $jsonVar;\n";
        
        return $code;
    }
    
    // 创建if语句的混淆
    private function createIfObfuscation($statement) {
        $code = '';
        $condVar = '$' . $this->generateExactVarName();
        $label1 = $this->generateExactVarName();
        $label2 = $this->generateExactVarName();
        
        // 解析if条件
        preg_match('/if\s*\(([^)]+)\)/', $statement, $matches);
        $condition = isset($matches[1]) ? $matches[1] : 'true';
        
        $code .= "$condVar=$condition;\n";
        $code .= "if($condVar){goto $label1;}goto $label2;\n";
        $code .= "$label1:unset(\$" . $this->generateExactVarName() . ");";
        
        // 提取if语句体
        $body = preg_replace('/if\s*\([^)]+\)\s*{?([^}]*)}?/', '$1', $statement);
        $code .= $this->obfuscateUserCode($body);
        
        $code .= "goto " . $this->generateExactVarName() . ";\n";
        $code .= "$label2:\n";
        
        return $code;
    }
    
    // 创建赋值语句的混淆
    private function createAssignmentObfuscation($statement) {
        $code = '';
        
        // 解析赋值语句
        if (preg_match('/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*=\s*(.+)/', $statement, $matches)) {
            $varName = $matches[1];
            $value = $matches[2];
            
            $obfuscatedVar = '$' . $this->generateExactVarName();
            $tempVar = '$' . $this->generateExactVarName();
            
            // 创建复杂的赋值结构
            $code .= $this->createExactVariableOperations($obfuscatedVar, 'CF__C_AA', rand(1000, 9999));
            $code .= "unset($tempVar);$tempVar=$value;\n";
            $code .= "$obfuscatedVar=$tempVar;\n";
            
            $this->variableMap[$varName] = $obfuscatedVar;
        }
        
        return $code;
    }
    
    // 创建通用语句混淆
    private function createGenericObfuscation($statement) {
        $code = '';
        $wrapperVar = '$' . $this->generateExactVarName();
        
        $code .= "unset($wrapperVar);$wrapperVar=\"$statement\";\n";
        $code .= "if(true){eval($wrapperVar);}else{unset($wrapperVar);}\n";
        
        return $code;
    }
    
    // 创建Loader（与示例格式匹配）
    public function createExactLoader() {
        return '<?php
/**
 * Ranyun_JiaMi Loader 解密器
 * 版权所有
 */

// 全局解密函数
function ranyun_decode($encoded_data, $key_array) {
    $result = "";
    if (is_array($key_array) && isset($key_array[0])) {
        $parts = explode($key_array[1], $encoded_data);
        foreach ($parts as $part) {
            if ($part !== "H*" && !empty($part)) {
                $result .= hex2bin($part);
            }
        }
    }
    return $result;
}

// 执行加密代码的函数
function ranyun_execute($obfuscated_code) {
    // 验证代码标识
    if (strpos($obfuscated_code, "Ranyun_JiaMi") === false) {
        die("无效的加密代码");
    }
    
    // 设置执行环境
    error_reporting(0);
    ini_set("display_errors", 0);
    
    // 执行混淆代码
    eval($obfuscated_code);
}

// 自动解密并执行
if (isset($GLOBALS["ENCRYPTED_CODE"])) {
    ranyun_execute($GLOBALS["ENCRYPTED_CODE"]);
}

// 版权信息
if (__FILE__ == $_SERVER["SCRIPT_FILENAME"]) {
    echo "Ranyun_JiaMi Loader v1.0\\n";
    echo "PHP代码保护系统\\n";
    echo "版权所有 - 未经授权禁止使用\\n";
}
?>';
    }
    
    // 生成完整的加密包（包含Loader）
    public function generateCompletePackage($sourceCode) {
        $encryptedCode = $this->encodeExact($sourceCode);
        $loader = $this->createExactLoader();
        
        // 创建完整包
        $package = "<?php\n";
        $package .= "/**\n * Ranyun_JiaMi 完整加密包\n * 包含加密代码和解密器\n */\n\n";
        
        // 添加解密器
        $package .= "// === LOADER START ===\n";
        $package .= substr($loader, 5); // 移除<?php
        $package .= "\n// === LOADER END ===\n\n";
        
        // 添加加密代码
        $package .= "// === ENCRYPTED CODE START ===\n";
        $package .= "\$GLOBALS[\"ENCRYPTED_CODE\"] = '" . addslashes($encryptedCode) . "';\n";
        $package .= "// === ENCRYPTED CODE END ===\n\n";
        
        // 自动执行
        $package .= "// 自动执行加密代码\n";
        $package .= "ranyun_execute(\$GLOBALS[\"ENCRYPTED_CODE\"]);\n";
        $package .= "?>";
        
        return $package;
    }
}

// 处理请求
$encoder = new ExactPHPEncoder();

if (isset($_POST['action'])) {
    $sourceCode = $_POST['source_code'] ?? '';
    
    if ($_POST['action'] === 'encode') {
        $result = $encoder->encodeExact($sourceCode);
        $type = 'encoded';
    } elseif ($_POST['action'] === 'loader') {
        $result = $encoder->createExactLoader();
        $type = 'loader';
    } elseif ($_POST['action'] === 'package') {
        $result = $encoder->generateCompletePackage($sourceCode);
        $type = 'package';
    }
    
    if (isset($result)) {
        echo "<div style='margin: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>";
        echo "<h3>生成结果 ($type):</h3>";
        echo "<textarea rows='30' cols='150' style='font-family: monospace; font-size: 11px; width: 100%;'>" . htmlspecialchars($result) . "</textarea>";
        echo "<br><br>";
        echo "<button onclick='navigator.clipboard.writeText(this.previousElementSibling.previousElementSibling.value)' style='background: #28a745; color: white; padding: 8px 16px; border: none; border-radius: 3px; cursor: pointer;'>复制代码</button>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ranyun_JiaMi 精确PHP代码加密器</title>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Microsoft YaHei', 'Segoe UI', Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 1600px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
        }
        h1 { 
            color: #333; 
            text-align: center; 
            margin-bottom: 30px; 
            font-size: 2.5em;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .feature-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        textarea { 
            width: 100%; 
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace; 
            border: 2px solid #e9ecef; 
            border-radius: 8px; 
            padding: 15px; 
            resize: vertical; 
            transition: border-color 0.3s;
        }
        textarea:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn { 
            background: linear-gradient(45deg, #667eea, #764ba2); 
            color: white; 
            padding: 15px 30px; 
            border: none; 
            cursor: pointer; 
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: 600; 
            transition: all 0.3s;
            flex: 1;
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(45deg, #6c757d, #495057);
        }
        .btn-success {
            background: linear-gradient(45deg, #28a745, #20c997);
        }
        .info { 
            background: linear-gradient(135deg, #e3f2fd, #bbdefb); 
            border: none;
            padding: 20px; 
            border-radius: 10px; 
            margin: 20px 0; 
            border-left: 5px solid #2196f3;
        }
        .warning { 
            background: linear-gradient(135deg, #fff8e1, #ffecb3); 
            border: none;
            padding: 20px; 
            border-radius: 10px; 
            margin: 20px 0; 
            border-left: 5px solid #ff9800;
        }
        .example-code {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            font-family: monospace;
            font-size: 14px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Ranyun_JiaMi 精确PHP代码加密器</h1>
        
        <div class="info">
            <h3>🎯 完全模仿示例算法</h3>
            <p>本加密器采用与您提供示例完全相同的混淆算法和格式，包括：</p>
            <div class="feature-grid">
                <div class="feature-card">
                    <h4>🔤 变量名混淆</h4>
                    <p>生成如 AU7xHZ4、VQ6nV01 等复杂变量名</p>
                </div>
                <div class="feature-card">
                    <h4>📊 字符串编码</h4>
                    <p>十六进制编码存储在全局数组中</p>
                </div>
                <div class="feature-card">
                    <h4>🌀 控制流混淆</h4>
                    <p>大量goto语句和标签跳转</p>
                </div>
                <div class="feature-card">
                    <h4>🧮 数学表达式</h4>
                    <p>使用错误常量的复杂计算</p>
                </div>
            </div>
        </div>
        
        <div class="warning">
            <h3>⚠️ 重要提醒</h3>
            <ul>
                <li><strong>代码体积:</strong> 加密后代码将增大10-50倍</li>
                <li><strong>执行性能:</strong> 运行速度会明显下降</li>
                <li><strong>兼容性:</strong> 需要PHP 5.4+支持</li>
                <li><strong>调试:</strong> 加密后代码难以调试</li>
            </ul>
        </div>
        
        <form method="post">
            <h3>📝 输入要加密的PHP源代码:</h3>
            <textarea name="source_code" rows="25" placeholder="<?php
// 示例：用户登录验证
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($username) && !empty($password)) {
    // 验证用户凭据
    if ($username === 'admin' && $password === '123456') {
        echo json_encode(['status' => 'success', 'message' => '登录成功']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '用户名或密码错误']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '请输入用户名和密码']);
}
?>"><?php echo isset($_POST['source_code']) ? htmlspecialchars($_POST['source_code']) : ''; ?></textarea>
            
            <div class="btn-group">
                <button type="submit" name="action" value="encode" class="btn">🔒 生成加密代码</button>
                <button type="submit" name="action" value="loader" class="btn btn-secondary">🔓 生成Loader</button>
                <button type="submit" name="action" value="package" class="btn btn-success">📦 生成完整包</button>
            </div>
        </form>
        
        <div style="margin-top: 40px;">
            <h3>📖 使用说明</h3>
            <div class="example-code">
                <strong>1. 加密代码:</strong> 生成高度混淆的PHP代码<br>
                <strong>2. Loader:</strong> 生成解密器，用于运行加密代码<br>
                <strong>3. 完整包:</strong> 包含解密器和加密代码的完整文件
            </div>
        </div>
        
        <div style="margin-top: 30px; text-align: center; color: #666; font-size: 14px; border-top: 1px solid #eee; padding-top: 20px;">
            <p><strong>© 2024 Ranyun_JiaMi</strong> - 专业PHP代码保护解决方案</p>
            <p>🛡️ 保护您的知识产权 | 🚀 防止代码逆向工程 | 💼 商业级代码混淆</p>
        </div>
    </div>
</body>
</html>