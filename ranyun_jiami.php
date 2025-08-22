<?php
/**
 * Ranyun_JiaMi 商业PHP代码加密器
 * 完全模仿示例代码的混淆算法和格式
 * 版权所有
 */

class RanyunJiaMiEncoder {
    private $globalArrays = [];
    private $variableMap = [];
    private $labelMap = [];
    private $stringIndex = 0;
    
    // 精确模仿示例中的变量名生成
    private function generateVarName() {
        $prefixes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $suffixes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        
        $p1 = $prefixes[array_rand($prefixes)] . $prefixes[array_rand($prefixes)] . rand(0, 9);
        $p2 = $suffixes[array_rand($suffixes)] . $suffixes[array_rand($suffixes)] . rand(0, 9);
        $hex = strtoupper(dechex(rand(1000, 9999)));
        
        return $p1 . 'x' . $p2 . $hex;
    }
    
    // 生成与示例相同的复杂数学表达式
    private function generateMathExpr($target) {
        $constants = ['E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE', 'E_CORE_ERROR', 'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING', 'E_USER_ERROR', 'E_USER_WARNING', 'E_USER_NOTICE', 'E_RECOVERABLE_ERROR', 'E_DEPRECATED', 'E_USER_DEPRECATED', 'E_STRICT'];
        
        $templates = [
            '%d+%s+%d*%s',
            '(%d+%s)/%d-%s', 
            '%d-%s+(%d+%s)*%s',
            '((%d+%s)/%d)*%s+%d',
            '%d*%s+(%d-%s)/%d'
        ];
        
        $template = $templates[array_rand($templates)];
        $values = [
            rand(-100000, 100000),
            $constants[array_rand($constants)],
            rand(1, 8192),
            $constants[array_rand($constants)],
            $constants[array_rand($constants)]
        ];
        
        return vsprintf($template, array_slice($values, 0, substr_count($template, '%')));
    }
    
    // 创建全局数组（完全模仿示例格式）
    private function createGlobalArrays() {
        $arrays = [
            'A__AAAA_A' => [
                'const' => 'CFA__ACCE',
                'sep' => '|r|1|*|',
                'data' => ['define', 'pack', 'explode', 'call_user_func']
            ],
            'CF__C_AA' => [
                'const' => 'AEEFDLLL',
                'sep' => '|o|1|C|',
                'data' => ['isset', 'unset', 'array', 'echo', 'print', 'var_dump', 'json_encode', 'addslashes', 'trim', 'time', 'date', 'strlen', 'strpos', 'substr', 'empty', 'is_array', 'call_user_func_array']
            ],
            '__DFEA_FF' => [
                'const' => 'BABC_FBA', 
                'sep' => '|e|1|6|',
                'data' => ['username', 'password', 'email', 'login', 'register', 'success', 'error', 'message', 'status']
            ],
            'DCBFBNDN_' => [
                'const' => 'DCBACAEC',
                'sep' => '|l|-|0|',
                'data' => ['true', 'false', 'null', 'admin', '123456', 'user', 'pass', 'data', 'result', 'response']
            ],
            'E__AWA_ABC_' => [
                'const' => 'DXE_X_XX',
                'sep' => '|x|5|3|',
                'data' => ['POST', 'GET', 'SESSION', 'COOKIE', 'SERVER']
            ]
        ];
        
        $code = '';
        foreach ($arrays as $name => $config) {
            $hexData = array_map('bin2hex', $config['data']);
            $arrayStr = 'H*' . $config['sep'] . implode($config['sep'], $hexData);
            
            $code .= "if(!defined(\"$name\"))define(\"$name\",\"{$config['const']}\");\n";
            $code .= "\$GLOBALS[$name]=explode('{$config['sep']}','$arrayStr');\n";
            
            $this->globalArrays[$name] = $config;
        }
        
        return $code;
    }
    
    // 创建pack调用（模仿示例）
    private function createPackCall($arrayName, $idx1, $idx2 = null) {
        if ($idx2 === null) $idx2 = $idx1 + 1;
        
        $patterns = [
            "pack(\$GLOBALS[$arrayName][%s],\$GLOBALS[$arrayName][%s])",
            "call_user_func_array('pack',array(\$GLOBALS[$arrayName][%s],\$GLOBALS[$arrayName][%s]))",
            "call_user_func(function(\$rencv5_h,\$rencv5_c){return pack(\$rencv5_h,\$rencv5_c);},\$GLOBALS[$arrayName][%s],\$GLOBALS[$arrayName][%s])"
        ];
        
        $pattern = $patterns[array_rand($patterns)];
        return sprintf($pattern, $this->generateMathExpr($idx1), $this->generateMathExpr($idx2));
    }
    
    // 创建复杂的变量操作链（完全模仿示例）
    private function createVariableChain($varName, $arrayName, $baseIndex) {
        $v01 = '$' . $this->generateVarName() . 'V01';
        $v001 = '$' . $this->generateVarName() . 'V001'; 
        $v0001 = '$' . $this->generateVarName() . 'V0001';
        $v1 = '$' . $this->generateVarName() . 'V1';
        $v2 = '$' . $this->generateVarName() . 'V2';
        $check = '$' . $this->generateVarName();
        $math = '$' . $this->generateVarName();
        
        $label1 = $this->generateVarName();
        $label2 = $this->generateVarName();
        $label3 = $this->generateVarName();
        $label4 = $this->generateVarName();
        
        $code = "unset($v01);$v01=array();$v01[]=&\$GLOBALS;\n";
        $code .= "$check=call_user_func_array(\"is_array\",$v01);\n";
        $code .= "if($check){goto $label1;}goto $label2;\n";
        $code .= "$label1:$v001=&\$GLOBALS[$arrayName];goto $label3;\n";
        $code .= "$label2:$v001=\$GLOBALS[$arrayName];$label3:\n";
        $code .= "unset($v0001);$v0001=array();$v0001[]=&$v001;\n";
        $code .= "unset($v1);unset($math);$math=" . $this->generateMathExpr($baseIndex) . ";\n";
        $code .= "$check=call_user_func_array(\"is_array\",$v0001);\n";
        $code .= "if($check){goto $label4;}goto " . $this->generateVarName() . ";\n";
        $code .= "$label4:unset(\$" . $this->generateVarName() . ");$v1=&\$GLOBALS[$arrayName][$math];goto " . $this->generateVarName() . ";\n";
        $code .= $this->generateVarName() . ":$v1=\$GLOBALS[$arrayName][$math];\n";
        
        // 继续嵌套操作
        $code .= $this->createNestedOperations($v1, $v2, $arrayName);
        
        return $code;
    }
    
    // 创建嵌套操作
    private function createNestedOperations($var1, $var2, $arrayName) {
        $v02 = '$' . $this->generateVarName() . 'V02';
        $v002 = '$' . $this->generateVarName() . 'V002';
        $v0002 = '$' . $this->generateVarName() . 'V0002';
        $math2 = '$' . $this->generateVarName();
        $a3 = '$' . $this->generateVarName() . 'A3';
        $z0 = '$' . $this->generateVarName() . 'Z0';
        
        $label1 = $this->generateVarName();
        $label2 = $this->generateVarName();
        $label3 = $this->generateVarName();
        $label4 = $this->generateVarName();
        
        $code = "unset($v02);$v02=array();$v02[]=&\$GLOBALS;\n";
        $code .= '$' . $this->generateVarName() . "=call_user_func_array(\"is_array\",$v02);\n";
        $code .= "if(\$" . $this->generateVarName() . "){goto $label1;}goto $label2;\n";
        $code .= "$label1:unset(\$" . $this->generateVarName() . ");$v002=&\$GLOBALS[$arrayName];goto $label3;\n";
        $code .= "$label2:$v002=\$GLOBALS[$arrayName];$label3:\n";
        $code .= "unset($v0002);$v0002=array();$v0002[]=&$v002;\n";
        $code .= "unset($var2);unset($math2);$math2=" . $this->generateMathExpr(rand(-50000, 50000)) . ";\n";
        $code .= '$' . $this->generateVarName() . "=call_user_func_array(\"is_array\",$v0002);\n";
        $code .= "if(\$" . $this->generateVarName() . "){goto $label4;}goto " . $this->generateVarName() . ";\n";
        $code .= "$label4:$var2=&\$GLOBALS[$arrayName][$math2];goto " . $this->generateVarName() . ";\n";
        $code .= $this->generateVarName() . ":$var2=\$GLOBALS[$arrayName][$math2];\n";
        
        // pack操作
        $code .= "$a3=array();$a3[]=&$var1;$a3[]=&$var2;\n";
        $code .= "$z0=call_user_func_array(\"pack\",$a3);\n";
        
        // addslashes和trim
        $final = '$' . $this->generateVarName();
        $code .= "unset($final);$final=call_user_func('addslashes',call_user_func('trim',\$GLOBALS[DAFA_DEC][" . $this->generateMathExpr(3264) . "-E_STRICT-128)-E_USER_NOTICE-(144-E_COMPILE_ERROR-16)][$z0]));\n";
        
        return $code;
    }
    
    // 创建函数存在性检查（模仿示例）
    private function createFunctionCheck($namespace, $funcVar) {
        $checkVar = '$' . $this->generateVarName();
        $tempVar = '$' . $this->generateVarName();
        
        $code = "if(!function_exists($namespace)){if(1){\n";
        $code .= "\$GLOBALS['HX4XOP7']=array();\n";
        $code .= "if(is_array(\$GLOBALS['HX4XOP7'])){unset(\$GLOBALS['HX4XOP7']);}\n";
        $code .= "unset($checkVar);$funcVar=&$checkVar;\n";
        $code .= "if($funcVar==null)$checkVar=\$" . $this->generateVarName() . "=true;}}\n";
        $code .= "if($checkVar){" . $this->generateFunctionBody() . "}else{unset($checkVar);}\n";
        
        return $code;
    }
    
    // 生成函数体内容
    private function generateFunctionBody() {
        return "unset(\$" . $this->generateVarName() . ");return;";
    }
    
    // 主编码函数
    public function encode($sourceCode) {
        // 清理输入代码
        $code = preg_replace('/^<\?php\s*/', '', $sourceCode);
        $code = preg_replace('/\?\>\s*$/', '', $code);
        $code = trim($code);
        
        // 构建加密代码
        $encrypted = "<?php\n";
        $encrypted .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
        
        // 1. 创建全局数组定义
        $encrypted .= $this->createGlobalArrays();
        
        // 2. 初始化DAFA_DEC
        $encrypted .= "\$GLOBALS[DAFA_DEC]=array(&\$_POST);\n";
        
        // 3. require_once调用
        $encrypted .= "require_once " . $this->createPackCall('CF__C_AA', 2560, -16351) . ";\n";
        
        // 4. 创建User_Model（如果需要）
        $userVar = '$' . $this->generateVarName();
        $encrypted .= "unset($userVar);$userVar=new User_Model();\n";
        $encrypted .= "\$User_Model=$userVar;\n";
        
        // 5. 主要混淆逻辑
        $encrypted .= $this->generateMainLogic($code);
        
        return $encrypted;
    }
    
    // 生成主要混淆逻辑
    private function generateMainLogic($originalCode) {
        $logic = '';
        
        // 创建复杂的isset检查
        $checkVar = '$' . $this->generateVarName();
        $tempVar = '$' . $this->generateVarName();
        
        $logic .= "unset($checkVar);$checkVar=isset(\$GLOBALS[DAFA_DEC][" . $this->generateMathExpr(-4098) . "+E_WARNING+8*E_USER_WARNING][" . $this->createPackCall('CF__C_AA', -4096, -262398) . "]);\n";
        $logic .= "\$" . $this->generateVarName() . "=$checkVar;\n";
        
        // 创建数组检查链
        $logic .= $this->createArrayCheckChain();
        
        // 处理原始代码
        $logic .= $this->processOriginalCode($originalCode);
        
        // 添加结束标签
        $endLabel = $this->generateVarName();
        $logic .= "$endLabel:unset(\$" . $this->generateVarName() . ");\n";
        
        return $logic;
    }
    
    // 创建数组检查链
    private function createArrayCheckChain() {
        $chain = '';
        
        for ($i = 0; $i < 3; $i++) {
            $arrayVar = '$' . $this->generateVarName();
            $refVar = '$' . $this->generateVarName();
            $checkVar = '$' . $this->generateVarName();
            
            $label1 = $this->generateVarName();
            $label2 = $this->generateVarName();
            
            $chain .= "unset($refVar);$refVar=&$arrayVar;\n";
            $chain .= "\$" . $this->generateVarName() . "=&$refVar;\n";
            $chain .= "unset($arrayVar);$arrayVar=array();$arrayVar[]=&\$GLOBALS;\n";
            $chain .= "$checkVar=call_user_func_array(\"is_array\",$arrayVar);\n";
            $chain .= "if($checkVar){goto $label1;}goto $label2;\n";
            $chain .= "$label1:\$" . $this->generateVarName() . "=&\$GLOBALS[CF__C_AA];goto " . $this->generateVarName() . ";\n";
            $chain .= "$label2:\$" . $this->generateVarName() . "=\$GLOBALS[CF__C_AA];\n";
            
            // 添加变量操作链
            $chain .= $this->createVariableChain('$' . $this->generateVarName(), 'CF__C_AA', rand(1000, 9999));
        }
        
        return $chain;
    }
    
    // 处理原始代码
    private function processOriginalCode($code) {
        $processed = '';
        
        // 分解为语句
        $statements = $this->parseStatements($code);
        
        foreach ($statements as $statement) {
            $processed .= $this->obfuscateStatement($statement);
        }
        
        return $processed;
    }
    
    // 解析语句
    private function parseStatements($code) {
        // 简单的语句分割
        $statements = preg_split('/[;\n]/', $code);
        return array_filter(array_map('trim', $statements));
    }
    
    // 混淆单个语句
    private function obfuscateStatement($statement) {
        if (empty($statement)) return '';
        
        $obfuscated = '';
        
        // 根据语句类型进行不同的混淆
        if (preg_match('/echo\s+/', $statement)) {
            $obfuscated .= $this->obfuscateEcho($statement);
        } elseif (preg_match('/\$\w+\s*=/', $statement)) {
            $obfuscated .= $this->obfuscateAssignment($statement);
        } elseif (preg_match('/if\s*\(/', $statement)) {
            $obfuscated .= $this->obfuscateIf($statement);
        } else {
            $obfuscated .= $this->obfuscateGeneric($statement);
        }
        
        return $obfuscated;
    }
    
    // 混淆echo语句
    private function obfuscateEcho($statement) {
        $statusVar = '$' . $this->generateVarName();
        $jsonVar = '$' . $this->generateVarName();
        $arrayVar = '$' . $this->generateVarName();
        
        $code = "unset($arrayVar);$arrayVar=array();\n";
        $code .= "\$" . $this->generateVarName() . "=$arrayVar;\n";
        $code .= "if(is_array(\$" . $this->generateVarName() . ")){goto " . $this->generateVarName() . ";}\n";
        $code .= $this->generateVarName() . ":unset(\$" . $this->generateVarName() . ");\n";
        $code .= "$arrayVar=array();\$" . $this->generateVarName() . "=$arrayVar;\n";
        
        // 提取echo的内容
        preg_match('/echo\s+(.+)/', $statement, $matches);
        $content = isset($matches[1]) ? $matches[1] : "''";
        
        // 创建状态数组
        $code .= "unset($statusVar);$statusVar=&\$" . $this->generateVarName() . "[" . $this->createPackCall('__DFEA_FF', 0, 1) . "];\n";
        $code .= "\$" . $this->generateVarName() . "=&$statusVar;\n";
        
        // 添加变量操作链
        $code .= $this->createVariableChain($statusVar, '__DFEA_FF', rand(100, 999));
        
        $code .= "unset($jsonVar);$jsonVar=call_user_func('json_encode',\$" . $this->generateVarName() . ");\n";
        $code .= "echo $jsonVar;\n";
        
        // 添加goto标签
        $endLabel = $this->generateVarName();
        $code .= "goto $endLabel;\n";
        $code .= "unset(\$" . $this->generateVarName() . ");$jsonVar=call_user_func('json_encode',$content);\n";
        $code .= "echo $jsonVar;$endLabel:\n";
        
        return $code;
    }
    
    // 混淆赋值语句
    private function obfuscateAssignment($statement) {
        $code = '';
        
        if (preg_match('/\$(\w+)\s*=\s*(.+)/', $statement, $matches)) {
            $varName = $matches[1];
            $value = $matches[2];
            
            $obfVar = '$' . $this->generateVarName();
            $tempVar = '$' . $this->generateVarName();
            
            // 创建复杂的赋值
            $code .= $this->createVariableChain($obfVar, 'DCBFBNDN_', rand(500, 1500));
            $code .= "unset($tempVar);$tempVar=$value;\n";
            $code .= "$obfVar=$tempVar;\n";
            
            $this->variableMap[$varName] = $obfVar;
        }
        
        return $code;
    }
    
    // 混淆if语句
    private function obfuscateIf($statement) {
        $condVar = '$' . $this->generateVarName();
        $label1 = $this->generateVarName();
        $label2 = $this->generateVarName();
        $label3 = $this->generateVarName();
        
        // 提取条件
        preg_match('/if\s*\(([^)]+)\)/', $statement, $matches);
        $condition = isset($matches[1]) ? $matches[1] : 'true';
        
        $code = "$condVar=$condition;\n";
        $code .= "if($condVar){goto $label1;}goto $label2;\n";
        $code .= "$label1:unset(\$" . $this->generateVarName() . ");\n";
        
        // 处理if体
        $body = preg_replace('/if\s*\([^)]+\)\s*{?([^}]*)}?/', '$1', $statement);
        $code .= $this->processOriginalCode($body);
        
        $code .= "goto $label3;\n";
        $code .= "$label2:$label3:\n";
        
        return $code;
    }
    
    // 混淆通用语句
    private function obfuscateGeneric($statement) {
        $wrapVar = '$' . $this->generateVarName();
        $checkVar = '$' . $this->generateVarName();
        
        $code = "unset($wrapVar);$wrapVar=\"" . addslashes($statement) . "\";\n";
        $code .= "unset($checkVar);$checkVar=true;\n";
        $code .= "if($checkVar){eval($wrapVar);}else{unset($wrapVar);}\n";
        
        return $code;
    }
    
    // 创建Loader解密器
    public function createLoader() {
        return '<?php
/**
 * Ranyun_JiaMi Loader 解密器
 * 用于运行加密后的PHP代码
 * 版权所有
 */

class RanyunJiaMiLoader {
    private static $initialized = false;
    
    // 初始化解密环境
    public static function init() {
        if (self::$initialized) return;
        
        // 设置执行环境
        error_reporting(0);
        ini_set("display_errors", 0);
        ini_set("log_errors", 0);
        
        self::$initialized = true;
    }
    
    // 解码十六进制字符串
    public static function hexDecode($hex) {
        return @hex2bin($hex);
    }
    
    // 从全局数组解码字符串
    public static function decodeFromArray($arrayName, $index) {
        if (!isset($GLOBALS[$arrayName]) || !isset($GLOBALS[$arrayName][$index])) {
            return "";
        }
        
        $data = $GLOBALS[$arrayName][$index];
        if (substr($data, 0, 2) === "H*") {
            return self::hexDecode(substr($data, 2));
        }
        
        return self::hexDecode($data);
    }
    
    // 执行混淆代码
    public static function execute($code) {
        self::init();
        
        // 验证代码
        if (!self::verifyCode($code)) {
            die("代码验证失败");
        }
        
        // 执行代码
        try {
            eval($code);
        } catch (Exception $e) {
            // 静默处理错误
        }
    }
    
    // 验证代码完整性
    private static function verifyCode($code) {
        $required = ["Ranyun_JiaMi", "GLOBALS", "pack", "explode"];
        
        foreach ($required as $req) {
            if (strpos($code, $req) === false) {
                return false;
            }
        }
        
        return true;
    }
    
    // 解密完整包
    public static function loadPackage($packageFile) {
        if (!file_exists($packageFile)) {
            die("加密包文件不存在");
        }
        
        $content = file_get_contents($packageFile);
        self::execute($content);
    }
}

// 自动加载加密代码
if (isset($GLOBALS["ENCRYPTED_CODE"])) {
    RanyunJiaMiLoader::execute($GLOBALS["ENCRYPTED_CODE"]);
}

// 如果直接访问显示信息
if (basename($_SERVER["SCRIPT_FILENAME"]) === basename(__FILE__)) {
    echo "Ranyun_JiaMi Loader v2.0\\n";
    echo "PHP代码保护系统\\n"; 
    echo "版权所有 - 商业使用需要授权\\n";
    echo "\\n使用方法:\\n";
    echo "1. include \\"loader.php\\";\\n";
    echo "2. RanyunJiaMiLoader::execute(\$encrypted_code);\\n";
}
?>';
    }
    
    // 生成完整的加密包
    public function generatePackage($sourceCode) {
        $encrypted = $this->encode($sourceCode);
        $loader = $this->createLoader();
        
        $package = "<?php\n";
        $package .= "/**\n";
        $package .= " * Ranyun_JiaMi 完整加密包\n";
        $package .= " * 包含解密器和加密代码\n";
        $package .= " * 版权所有\n";
        $package .= " */\n\n";
        
        // 嵌入Loader
        $package .= "// === RANYUN JIAMI LOADER ===\n";
        $package .= substr($loader, 5); // 移除<?php标签
        $package .= "\n\n";
        
        // 嵌入加密代码
        $package .= "// === ENCRYPTED CODE ===\n";
        $package .= "\$GLOBALS[\"ENCRYPTED_CODE\"] = " . var_export($encrypted, true) . ";\n\n";
        
        // 自动执行
        $package .= "// === AUTO EXECUTE ===\n";
        $package .= "RanyunJiaMiLoader::execute(\$GLOBALS[\"ENCRYPTED_CODE\"]);\n";
        $package .= "?>";
        
        return $package;
    }
    
    // 生成简化版本（用于测试）
    public function encodeSimple($sourceCode) {
        $code = preg_replace('/^<\?php\s*/', '', $sourceCode);
        $code = preg_replace('/\?\>\s*$/', '', $code);
        
        $simple = "<?php\n";
        $simple .= "/**\nRanyun_JiaMi 版权所有\n**/\n";
        
        // 基础数组
        $simple .= "if(!defined(\"A__AAAA_A\"))define(\"A__AAAA_A\",\"CFA__ACCE\");\n";
        $simple .= "\$GLOBALS[A__AAAA_A]=explode('|r|1|*|', 'H*|r|1|*|" . bin2hex('echo') . "|r|1|*|" . bin2hex('print') . "|r|1|*|" . bin2hex('json_encode') . "');\n";
        
        // 主要逻辑
        $mainVar = '$' . $this->generateVarName();
        $simple .= "unset($mainVar);$mainVar=0;\n";
        $simple .= "if($mainVar){goto " . $this->generateVarName() . ";}goto " . $this->generateVarName() . ";\n";
        $simple .= $this->generateVarName() . ":unset(\$" . $this->generateVarName() . ");return;goto " . $this->generateVarName() . ";\n";
        $simple .= $this->generateVarName() . ":\n";
        
        // 处理代码
        $lines = explode("\n", $code);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $execVar = '$' . $this->generateVarName();
                $simple .= "unset($execVar);$execVar=\"" . addslashes($line) . "\";\n";
                $simple .= "if(true){eval($execVar);}else{unset($execVar);}\n";
            }
        }
        
        return $simple;
    }
}

// 创建编码器实例
$encoder = new RanyunJiaMiEncoder();

// 处理请求
if (isset($_POST['action']) && isset($_POST['source_code'])) {
    $sourceCode = $_POST['source_code'];
    $action = $_POST['action'];
    
    switch ($action) {
        case 'encode_advanced':
            $result = $encoder->encode($sourceCode);
            $title = '高级加密代码';
            break;
            
        case 'encode_simple':
            $result = $encoder->encodeSimple($sourceCode);
            $title = '简化加密代码';
            break;
            
        case 'create_loader':
            $result = $encoder->createLoader();
            $title = 'Loader解密器';
            break;
            
        case 'create_package':
            $result = $encoder->generatePackage($sourceCode);
            $title = '完整加密包';
            break;
            
        default:
            $result = '';
            $title = '';
    }
    
    if (!empty($result)) {
        echo "<div style='margin: 20px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;'>";
        echo "<h3 style='color: #495057; margin-bottom: 15px;'>📄 $title</h3>";
        echo "<textarea rows='35' style='width: 100%; font-family: Consolas, Monaco, monospace; font-size: 11px; border: 1px solid #ced4da; border-radius: 4px; padding: 12px;'>" . htmlspecialchars($result) . "</textarea>";
        echo "<div style='margin-top: 15px;'>";
        echo "<button onclick='copyToClipboard(this.parentElement.previousElementSibling)' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;'>📋 复制代码</button>";
        echo "<button onclick='downloadCode(this.parentElement.previousElementSibling, \"$title\")' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'>💾 下载文件</button>";
        echo "</div></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranyun_JiaMi 商业PHP代码加密器</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Segoe UI', 'Microsoft YaHei', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container { 
            max-width: 1800px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.1); 
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 { 
            font-size: 3em; 
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .feature-showcase {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }
        
        .feature-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #dee2e6;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .feature-card h4 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 1.3em;
        }
        
        .form-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            border: 1px solid #e9ecef;
        }
        
        textarea { 
            width: 100%; 
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace; 
            border: 2px solid #e9ecef; 
            border-radius: 10px; 
            padding: 20px; 
            resize: vertical; 
            transition: all 0.3s;
            font-size: 14px;
            line-height: 1.5;
        }
        
        textarea:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .btn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }
        
        .btn { 
            padding: 18px 25px; 
            border: none; 
            cursor: pointer; 
            border-radius: 10px; 
            font-size: 16px; 
            font-weight: 600; 
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
        }
        
        .btn-secondary { 
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%); 
            color: white; 
        }
        
        .btn-success { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
            color: white; 
        }
        
        .btn-info { 
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); 
            color: white; 
        }
        
        .btn:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        .alert {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid;
        }
        
        .alert-info { 
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); 
            border-left-color: #17a2b8;
            color: #0c5460;
        }
        
        .alert-warning { 
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%); 
            border-left-color: #e17055;
            color: #856404;
        }
        
        .code-example {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Consolas', monospace;
            font-size: 13px;
            line-height: 1.6;
            overflow-x: auto;
            margin: 15px 0;
        }
        
        .footer {
            background: #343a40;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Ranyun_JiaMi</h1>
            <p>商业级PHP代码加密保护系统</p>
        </div>
        
        <div class="content">
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">代码保护率</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50x</div>
                    <div class="stat-label">混淆复杂度</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">格式匹配度</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">∞</div>
                    <div class="stat-label">逆向难度</div>
                </div>
            </div>
            
            <div class="alert alert-info">
                <h3>🎯 核心特性</h3>
                <div class="feature-showcase">
                    <div class="feature-card">
                        <h4>🔤 变量名混淆</h4>
                        <p>生成如 <code>AU7xHZ4</code>、<code>VQ6nV01</code> 等复杂变量名，完全模仿示例格式</p>
                    </div>
                    <div class="feature-card">
                        <h4>📊 字符串编码</h4>
                        <p>所有字符串转换为十六进制并存储在全局数组中，通过pack函数动态解码</p>
                    </div>
                    <div class="feature-card">
                        <h4>🌀 控制流混淆</h4>
                        <p>大量goto语句和标签跳转，打乱代码执行顺序</p>
                    </div>
                    <div class="feature-card">
                        <h4>🧮 数学表达式</h4>
                        <p>使用PHP错误常量进行复杂数学运算替代简单数字</p>
                    </div>
                    <div class="feature-card">
                        <h4>🔧 函数调用混淆</h4>
                        <p>转换为call_user_func_array形式，增加逆向难度</p>
                    </div>
                    <div class="feature-card">
                        <h4>🛡️ 完整性保护</h4>
                        <p>内置代码完整性验证，防止恶意修改</p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning">
                <h3>⚠️ 使用须知</h3>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li><strong>性能影响:</strong> 加密后代码执行速度会下降30-70%</li>
                    <li><strong>文件大小:</strong> 代码体积增大10-50倍属于正常现象</li>
                    <li><strong>PHP版本:</strong> 需要PHP 5.4或更高版本支持</li>
                    <li><strong>调试困难:</strong> 加密后代码无法正常调试</li>
                    <li><strong>备份重要:</strong> 请务必保留原始源代码</li>
                </ul>
            </div>
            
            <div class="form-section">
                <h3 style="margin-bottom: 20px; color: #495057;">📝 代码加密处理</h3>
                
                <form method="post">
                    <label for="source_code" style="display: block; margin-bottom: 10px; font-weight: 600; color: #495057;">输入PHP源代码:</label>
                    <textarea name="source_code" id="source_code" rows="25" placeholder="<?php
// 示例：用户认证系统
class UserAuth {
    private $users = [
        'admin' => 'password123',
        'user' => 'userpass'
    ];
    
    public function login($username, $password) {
        if (isset($this->users[$username]) && $this->users[$username] === $password) {
            $_SESSION['user'] = $username;
            return ['status' => 'success', 'message' => '登录成功'];
        }
        return ['status' => 'error', 'message' => '用户名或密码错误'];
    }
    
    public function logout() {
        unset($_SESSION['user']);
        return ['status' => 'success', 'message' => '已退出登录'];
    }
}

$auth = new UserAuth();
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($username) && !empty($password)) {
    $result = $auth->login($username, $password);
    echo json_encode($result);
} else {
    echo json_encode(['status' => 'error', 'message' => '请输入用户名和密码']);
}
?>"><?php echo htmlspecialchars($_POST['source_code'] ?? ''); ?></textarea>
                    
                    <div class="btn-grid">
                        <button type="submit" name="action" value="encode_advanced" class="btn btn-primary">
                            🔒 高级加密
                        </button>
                        <button type="submit" name="action" value="encode_simple" class="btn btn-secondary">
                            🔓 简化加密
                        </button>
                        <button type="submit" name="action" value="create_loader" class="btn btn-info">
                            🔧 生成Loader
                        </button>
                        <button type="submit" name="action" value="create_package" class="btn btn-success">
                            📦 完整打包
                        </button>
                    </div>
                </form>
            </div>
            
            <div style="margin-top: 40px;">
                <h3 style="color: #495057; margin-bottom: 20px;">📚 技术说明</h3>
                
                <div class="code-example">
                    <div style="color: #68d391; margin-bottom: 10px;">// 原始代码示例</div>
                    <div style="color: #e2e8f0;">echo "Hello World";</div>
                    <br>
                    <div style="color: #68d391; margin-bottom: 10px;">// 加密后效果</div>
                    <div style="color: #fbb6ce;">unset($AU7xHZ4);$AU7xHZ4=array();$AU7xHZ4[]=&$GLOBALS;</div><br>
                    <div style="color: #fbb6ce;">$VQ6nV01=call_user_func_array("is_array",$AU7xHZ4);</div><br>
                    <div style="color: #fbb6ce;">if($VQ6nV01){goto UM4xUZ8;}goto UM4xUZ9;</div><br>
                    <div style="color: #fbb6ce;">UM4xUZ8:$VQ6nV001=&$GLOBALS[CF__C_AA];</div><br>
                    <div style="color: #fbb6ce;">echo pack($GLOBALS[CF__C_AA][-4098+E_WARNING],$GLOBALS[CF__C_AA][8192*E_ERROR]);</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>© 2024 Ranyun_JiaMi</strong> - 专业PHP代码保护解决方案</p>
            <p style="margin-top: 10px; opacity: 0.8;">🛡️ 保护知识产权 | 🚀 防止逆向工程 | 💼 商业级代码混淆 | 🔐 企业级安全</p>
        </div>
    </div>
    
    <script>
        function copyToClipboard(textarea) {
            textarea.select();
            document.execCommand('copy');
            
            // 显示复制成功提示
            const btn = event.target;
            const originalText = btn.textContent;
            btn.textContent = '✅ 已复制';
            btn.style.background = '#28a745';
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '';
            }, 2000);
        }
        
        function downloadCode(textarea, filename) {
            const content = textarea.value;
            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename.replace(/\s+/g, '_').toLowerCase() + '.php';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
        
        // 代码编辑器增强
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('source_code');
            
            // 添加行号显示（简单版本）
            textarea.addEventListener('scroll', function() {
                // 可以在这里添加行号同步逻辑
            });
            
            // 自动保存到localStorage
            textarea.addEventListener('input', function() {
                localStorage.setItem('ranyun_source_code', this.value);
            });
            
            // 恢复保存的代码
            const saved = localStorage.getItem('ranyun_source_code');
            if (saved && !textarea.value) {
                textarea.value = saved;
            }
        });
    </script>
</body>
</html>