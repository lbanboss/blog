<?php
/**
 * PHP编码加密器演示
 * 展示如何使用加密器功能
 */

// 包含加密器类
require_once 'advanced_encoder.php';

// 演示用的PHP代码
$demoCode = '<?php
// 用户登录函数
function userLogin($username, $password) {
    global $db_host, $db_user, $db_pass, $db_name;
    
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        return ["status" => 1, "message" => "登录成功"];
    } else {
        return ["status" => 0, "message" => "用户名或密码错误"];
    }
}

// 处理登录请求
if ($_POST["type"] === "login") {
    $result = userLogin($_POST["user"], $_POST["pass"]);
    echo json_encode($result);
}
?>';

echo "=== PHP编码加密器演示 ===\n\n";

echo "原始代码:\n";
echo $demoCode . "\n\n";

// 创建加密器实例
$encoder = new AdvancedPHPEncoder(null, 3, 'high');

try {
    // 加密代码
    $encryptedCode = $encoder->encrypt($demoCode);
    
    echo "加密后代码 (整个文件一行显示):\n";
    echo $encryptedCode . "\n\n";
    
    // 保存加密结果
    file_put_contents('demo_encrypted.php', $encryptedCode);
    echo "加密结果已保存到 demo_encrypted.php\n\n";
    
    // 显示加密特性
    echo "=== 加密特性说明 ===\n";
    echo "1. 多层加密: 使用了3层加密算法\n";
    echo "2. 变量混淆: 变量名被随机混淆\n";
    echo "3. 字符串加密: 字符串被特殊加密处理\n";
    echo "4. 控制流混淆: 添加了随机条件语句\n";
    echo "5. 全局变量混淆: 使用随机常量名\n";
    echo "6. 分隔符混淆: 使用随机分隔符\n";
    echo "7. 垃圾代码: 添加了随机垃圾代码\n\n";
    
    // 分析加密结果
    echo "=== 加密结果分析 ===\n";
    echo "代码长度: " . strlen($encryptedCode) . " 字符\n";
    echo "常量定义数量: " . substr_count($encryptedCode, 'if(!defined(') . "\n";
    echo "全局变量数量: " . substr_count($encryptedCode, '$GLOBALS[') . "\n";
    echo "分隔符使用: " . substr_count($encryptedCode, '|r|1|*|') + 
                        substr_count($encryptedCode, '|o|1|C|') + 
                        substr_count($encryptedCode, '|e|1|6|') + 
                        substr_count($encryptedCode, '|l|-|0|') + 
                        substr_count($encryptedCode, '|x|5|3|') . "\n";
    
} catch (Exception $e) {
    echo "加密过程中出现错误: " . $e->getMessage() . "\n";
}

echo "\n=== 使用说明 ===\n";
echo "1. 命令行使用: php advanced_encoder.php input.php output.php 3 high\n";
echo "2. Web界面: 访问 web_interface.php\n";
echo "3. 编程接口: 使用 AdvancedPHPEncoder 类\n";
echo "4. 批量处理: 使用 -d 参数处理整个目录\n";

echo "\n=== 安全提示 ===\n";
echo "⚠️  请务必备份原始代码，加密是不可逆的\n";
echo "⚠️  加密后请测试代码是否正常运行\n";
echo "⚠️  建议在测试环境中先验证加密结果\n";
echo "⚠️  本工具仅用于代码保护和合法用途\n";
?>