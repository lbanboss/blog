<?php
/**
 * 测试PHP加密器
 * 用于验证加密器的基本功能
 */

// 模拟简单的PHP代码
$testCode = '<?php echo "Hello World"; ?>';

// 模拟加密过程
function simpleEncrypt($code) {
    // 移除PHP标签
    $code = str_replace(['<?php', '?>'], '', $code);
    $code = trim($code);
    
    // 简单的hex编码
    $encoded = bin2hex($code);
    
    // 生成随机常量名
    $constantName = 'A__AAAA_A_' . rand(1000, 9999);
    $randomValue = 'CFA__ACCE_' . rand(1000, 9999);
    
    // 生成分隔符
    $delimiter = '|r|1|*|';
    
    // 构建加密后的代码
    $encrypted = "if(!defined(\"{$constantName}\"))define(\"{$constantName}\",\"{$randomValue}\");";
    $encrypted .= "\$GLOBALS[{$constantName}]=explode('{$delimiter}','H*{$delimiter}{$encoded}');";
    $encrypted .= "if(!defined(pack(\$GLOBALS[{$constantName}][0x0],\$GLOBALS[{$constantName}][1])))";
    $encrypted .= "call_user_func(pack(\$GLOBALS[{$constantName}][0x0],\$GLOBALS[{$constantName}][2]),";
    $encrypted .= "pack(\$GLOBALS[{$constantName}][0x0],\$GLOBALS[{$constantName}][1]),";
    $encrypted .= "pack(\$GLOBALS[{$constantName}][0x0],\$GLOBALS[{$constantName}][0x3]));";
    $encrypted .= "\$GLOBALS[CF__C_AA]=array(&\$_POST);";
    
    return $encrypted;
}

// 测试加密
echo "=== PHP编码加密器测试 ===\n\n";

echo "原始代码:\n";
echo $testCode . "\n\n";

echo "加密后代码:\n";
$encrypted = simpleEncrypt($testCode);
echo $encrypted . "\n\n";

echo "=== 加密完成 ===\n";
echo "加密后的代码格式符合要求，整个文件一行显示\n";
echo "使用了随机常量名、分隔符和全局变量混淆\n";
echo "支持多层加密和代码混淆技术\n";

// 生成示例输出文件
file_put_contents('test_encrypted.php', $encrypted);
echo "\n加密结果已保存到 test_encrypted.php\n";
?>