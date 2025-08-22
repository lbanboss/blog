<?php
/**
 * PHP编码加密器安装脚本
 * 自动检查和设置环境
 */

echo "=== PHP编码加密器安装向导 ===\n\n";

// 检查PHP版本
echo "1. 检查PHP版本...\n";
$phpVersion = PHP_VERSION;
echo "   当前PHP版本: {$phpVersion}\n";

if (version_compare($phpVersion, '7.0.0', '<')) {
    echo "   ⚠️  警告: 建议使用PHP 7.0或更高版本\n";
} else {
    echo "   ✅ PHP版本符合要求\n";
}

// 检查必要的PHP扩展
echo "\n2. 检查PHP扩展...\n";
$requiredExtensions = ['pcre', 'json', 'openssl'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✅ {$ext} 扩展已安装\n";
    } else {
        echo "   ❌ {$ext} 扩展未安装\n";
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    echo "\n   请安装以下PHP扩展:\n";
    foreach ($missingExtensions as $ext) {
        echo "   - {$ext}\n";
    }
}

// 检查文件权限
echo "\n3. 检查文件权限...\n";
$files = ['php_encoder.php', 'advanced_encoder.php', 'web_interface.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        if (is_readable($file)) {
            echo "   ✅ {$file} 可读\n";
        } else {
            echo "   ❌ {$file} 不可读\n";
        }
    } else {
        echo "   ❌ {$file} 不存在\n";
    }
}

// 创建测试目录
echo "\n4. 创建测试目录...\n";
$testDir = 'test_output';
if (!is_dir($testDir)) {
    if (mkdir($testDir, 0755, true)) {
        echo "   ✅ 创建测试目录: {$testDir}\n";
    } else {
        echo "   ❌ 无法创建测试目录: {$testDir}\n";
    }
} else {
    echo "   ✅ 测试目录已存在: {$testDir}\n";
}

// 测试加密功能
echo "\n5. 测试加密功能...\n";
$testCode = '<?php echo "Hello World"; ?>';

try {
    // 包含加密器
    require_once 'advanced_encoder.php';
    
    // 创建加密器实例
    $encoder = new AdvancedPHPEncoder(null, 2, 'medium');
    
    // 测试加密
    $encrypted = $encoder->encrypt($testCode);
    
    if (!empty($encrypted)) {
        echo "   ✅ 加密功能正常\n";
        
        // 保存测试结果
        $testFile = $testDir . '/test_encrypted.php';
        if (file_put_contents($testFile, $encrypted)) {
            echo "   ✅ 测试文件已保存: {$testFile}\n";
        } else {
            echo "   ❌ 无法保存测试文件\n";
        }
    } else {
        echo "   ❌ 加密功能异常\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ 加密测试失败: " . $e->getMessage() . "\n";
}

// 生成使用示例
echo "\n6. 生成使用示例...\n";
$exampleCode = '<?php
// 使用示例
require_once "advanced_encoder.php";

// 创建加密器实例
$encoder = new AdvancedPHPEncoder(null, 3, "high");

// 要加密的PHP代码
$phpCode = \'<?php echo "Hello World"; ?>\';

// 加密代码
$encryptedCode = $encoder->encrypt($phpCode);

// 保存加密结果
file_put_contents("encrypted_output.php", $encryptedCode);

echo "加密完成！";
?>';

$exampleFile = $testDir . '/usage_example.php';
if (file_put_contents($exampleFile, $exampleCode)) {
    echo "   ✅ 使用示例已生成: {$exampleFile}\n";
} else {
    echo "   ❌ 无法生成使用示例\n";
}

// 生成配置文件
echo "\n7. 生成配置文件...\n";
$configCode = '<?php
/**
 * PHP编码加密器配置文件
 */

return [
    // 默认加密设置
    "default_layers" => 3,
    "default_level" => "high",
    
    // 输出设置
    "output_dir" => "encrypted",
    "backup_original" => true,
    
    // 安全设置
    "max_file_size" => 1024 * 1024, // 1MB
    "allowed_extensions" => ["php"],
    
    // 日志设置
    "enable_logging" => true,
    "log_file" => "encoder.log"
];
?>';

$configFile = 'config.php';
if (file_put_contents($configFile, $configCode)) {
    echo "   ✅ 配置文件已生成: {$configFile}\n";
} else {
    echo "   ❌ 无法生成配置文件\n";
}

// 安装完成
echo "\n=== 安装完成 ===\n";
echo "✅ PHP编码加密器已成功安装！\n\n";

echo "=== 使用方法 ===\n";
echo "1. 命令行使用:\n";
echo "   php advanced_encoder.php input.php output.php\n\n";

echo "2. Web界面使用:\n";
echo "   php -S localhost:8000\n";
echo "   然后访问: http://localhost:8000/web_interface.php\n\n";

echo "3. 编程接口使用:\n";
echo "   require_once \"advanced_encoder.php\";\n";
echo "   \$encoder = new AdvancedPHPEncoder();\n";
echo "   \$result = \$encoder->encrypt(\$phpCode);\n\n";

echo "=== 文件说明 ===\n";
echo "📁 php_encoder.php - 基础版加密器\n";
echo "📁 advanced_encoder.php - 增强版加密器\n";
echo "📁 web_interface.php - Web界面\n";
echo "📁 example.php - 示例代码\n";
echo "📁 config.php - 配置文件\n";
echo "📁 test_output/ - 测试输出目录\n\n";

echo "=== 安全提醒 ===\n";
echo "⚠️  请务必备份原始代码\n";
echo "⚠️  加密是不可逆的\n";
echo "⚠️  请在测试环境中验证\n";
echo "⚠️  仅用于合法用途\n\n";

echo "=== 技术支持 ===\n";
echo "如有问题，请查看 README.md 文档\n";
echo "或检查 test_output/ 目录中的示例文件\n\n";

echo "安装向导完成！🎉\n";
?>