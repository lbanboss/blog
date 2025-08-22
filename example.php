<?php
/**
 * 示例PHP文件 - 用于测试加密器
 */

// 数据库配置
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'password';
$db_name = 'test_db';

// 连接数据库
function connectDatabase($host, $user, $pass, $name) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("连接失败: " . $e->getMessage());
    }
}

// 用户登录函数
function userLogin($username, $password) {
    global $db_host, $db_user, $db_pass, $db_name;
    
    $pdo = connectDatabase($db_host, $db_user, $db_pass, $db_name);
    
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return ['status' => 1, 'message' => '登录成功'];
    } else {
        return ['status' => 0, 'message' => '用户名或密码错误'];
    }
}

// 用户注册函数
function userRegister($username, $email, $password) {
    global $db_host, $db_user, $db_pass, $db_name;
    
    $pdo = connectDatabase($db_host, $db_user, $db_pass, $db_name);
    
    // 检查用户名是否已存在
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        return ['status' => 0, 'message' => '用户名已存在'];
    }
    
    // 检查邮箱是否已存在
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['status' => 0, 'message' => '邮箱已存在'];
    }
    
    // 创建新用户
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, created_at) VALUES (?, ?, ?, NOW())");
    
    if ($stmt->execute([$username, $email, $password_hash])) {
        return ['status' => 1, 'message' => '注册成功'];
    } else {
        return ['status' => 0, 'message' => '注册失败'];
    }
}

// 发送邮件验证码
function sendEmailCode($email) {
    $code = rand(100000, 999999);
    $subject = '邮箱验证码';
    $message = "您的验证码是: $code\n请在10分钟内完成验证。";
    $headers = 'From: noreply@example.com';
    
    if (mail($email, $subject, $message, $headers)) {
        $_SESSION['email_code'] = $code;
        $_SESSION['email_code_time'] = time();
        return ['status' => 1, 'message' => '邮件已发送，请到邮箱获取验证码'];
    } else {
        return ['status' => 0, 'message' => '邮件发送失败'];
    }
}

// 验证邮箱验证码
function verifyEmailCode($input_code) {
    if (!isset($_SESSION['email_code']) || !isset($_SESSION['email_code_time'])) {
        return ['status' => 0, 'message' => '请先获取验证码'];
    }
    
    if (time() - $_SESSION['email_code_time'] > 600) {
        unset($_SESSION['email_code']);
        unset($_SESSION['email_code_time']);
        return ['status' => 0, 'message' => '验证码已过期'];
    }
    
    if ($input_code == $_SESSION['email_code']) {
        unset($_SESSION['email_code']);
        unset($_SESSION['email_code_time']);
        return ['status' => 1, 'message' => '验证成功'];
    } else {
        return ['status' => 0, 'message' => '验证码错误'];
    }
}

// 保存用户信息
function saveUserInfo($user_id, $data) {
    global $db_host, $db_user, $db_pass, $db_name;
    
    $pdo = connectDatabase($db_host, $db_user, $db_pass, $db_name);
    
    $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?");
    return $stmt->execute([$data['name'], $data['phone'], $data['address'], $user_id]);
}

// 保存密码
function savePassword($user_id, $new_password) {
    global $db_host, $db_user, $db_pass, $db_name;
    
    $pdo = connectDatabase($db_host, $db_user, $db_pass, $db_name);
    
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    return $stmt->execute([$password_hash, $user_id]);
}

// 获取用户IP
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// 检查用户是否持久登录
function isPersistentLogin() {
    return isset($_COOKIE['remember_token']) && !empty($_COOKIE['remember_token']);
}

// 处理登录请求
if ($_POST['type'] === 'login') {
    $result = userLogin($_POST['user'], $_POST['pass']);
    echo json_encode($result);
}

// 处理注册请求
if ($_POST['type'] === 'register') {
    $result = userRegister($_POST['user'], $_POST['mail'], $_POST['pass']);
    echo json_encode($result);
}

// 处理邮件验证码请求
if ($_POST['type'] === 'mailregcode') {
    $result = sendEmailCode($_POST['email']);
    echo json_encode($result);
}

// 处理邮箱验证码验证
if ($_POST['type'] === 'emailcode') {
    $result = verifyEmailCode($_POST['emailcode']);
    echo json_encode($result);
}

// 处理用户信息保存
if ($_POST['type'] === 'saveuser') {
    $result = saveUserInfo($_SESSION['user_id'], $_POST);
    echo json_encode(['status' => $result ? 1 : 0, 'message' => $result ? '保存成功' : '保存失败']);
}

// 处理密码保存
if ($_POST['type'] === 'savepass') {
    $result = savePassword($_SESSION['user_id'], $_POST['pass']);
    echo json_encode(['status' => $result ? 1 : 0, 'message' => $result ? '密码保存成功' : '密码保存失败']);
}

// 处理IP获取
if ($_POST['type'] === 'ip') {
    echo json_encode(['ip' => getUserIP()]);
}

// 处理持久登录检查
if ($_POST['type'] === 'status') {
    echo json_encode(['status' => isPersistentLogin() ? 1 : 0]);
}
?>