<?php
/**
 * PHP编码加密器Web界面
 * 提供友好的Web界面来使用加密器
 */

require_once 'advanced_encoder.php';

class WebInterface {
    private $encoder;
    
    public function __construct() {
        $this->encoder = new AdvancedPHPEncoder();
    }
    
    public function render() {
        $message = '';
        $encryptedCode = '';
        
        if ($_POST) {
            try {
                $phpCode = $_POST['php_code'] ?? '';
                $layers = (int)($_POST['layers'] ?? 3);
                $level = $_POST['level'] ?? 'high';
                
                if (empty($phpCode)) {
                    throw new Exception('请输入PHP代码');
                }
                
                // 创建新的编码器实例
                $encoder = new AdvancedPHPEncoder(null, $layers, $level);
                $encryptedCode = $encoder->encrypt($phpCode);
                $message = '加密成功！';
                
            } catch (Exception $e) {
                $message = '错误: ' . $e->getMessage();
            }
        }
        
        $this->outputHTML($message, $encryptedCode);
    }
    
    private function outputHTML($message, $encryptedCode) {
        ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>专业PHP编码加密器</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        textarea {
            width: 100%;
            min-height: 300px;
            padding: 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            resize: vertical;
            transition: border-color 0.3s ease;
        }
        
        textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        select, input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        select:focus, input[type="number"]:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .result {
            background: #f8f9fa;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .result h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        
        .code-output {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        
        .feature {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }
        
        .feature h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .feature p {
            color: #7f8c8d;
            line-height: 1.6;
        }
        
        .copy-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 10px;
            transition: background 0.3s ease;
        }
        
        .copy-btn:hover {
            background: #229954;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .content {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 专业PHP编码加密器</h1>
            <p>强大的PHP源代码混淆加密工具，保护您的知识产权</p>
        </div>
        
        <div class="content">
            <?php if ($message): ?>
                <div class="message <?= strpos($message, '错误') !== false ? 'error' : 'success' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="php_code">📝 输入PHP源代码：</label>
                    <textarea name="php_code" id="php_code" placeholder="在此输入您要加密的PHP代码..."><?= htmlspecialchars($_POST['php_code'] ?? '') ?></textarea>
                </div>
                
                <div class="options">
                    <div class="form-group">
                        <label for="layers">🔒 加密层数：</label>
                        <input type="number" name="layers" id="layers" value="<?= $_POST['layers'] ?? 3 ?>" min="1" max="10">
                    </div>
                    
                    <div class="form-group">
                        <label for="level">🛡️ 混淆级别：</label>
                        <select name="level" id="level">
                            <option value="low" <?= ($_POST['level'] ?? 'high') === 'low' ? 'selected' : '' ?>>低 - 基础混淆</option>
                            <option value="medium" <?= ($_POST['level'] ?? 'high') === 'medium' ? 'selected' : '' ?>>中 - 标准混淆</option>
                            <option value="high" <?= ($_POST['level'] ?? 'high') === 'high' ? 'selected' : '' ?>>高 - 高级混淆</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn">🚀 开始加密</button>
            </form>
            
            <?php if ($encryptedCode): ?>
                <div class="result">
                    <h3>✅ 加密结果：</h3>
                    <div class="code-output"><?= htmlspecialchars($encryptedCode) ?></div>
                    <button class="copy-btn" onclick="copyToClipboard()">📋 复制代码</button>
                </div>
            <?php endif; ?>
            
            <div class="features">
                <div class="feature">
                    <h3>🔐 多层加密</h3>
                    <p>支持1-10层加密，每层使用不同的加密算法，确保代码安全。</p>
                </div>
                
                <div class="feature">
                    <h3>🔄 变量混淆</h3>
                    <p>自动混淆变量名、函数名，使代码难以理解和逆向工程。</p>
                </div>
                
                <div class="feature">
                    <h3>🎭 控制流混淆</h3>
                    <p>添加随机条件语句和垃圾代码，混淆程序执行流程。</p>
                </div>
                
                <div class="feature">
                    <h3>📦 字符串加密</h3>
                    <p>对字符串进行特殊加密处理，防止字符串被直接识别。</p>
                </div>
                
                <div class="feature">
                    <h3>⚡ 高性能</h3>
                    <p>优化的加密算法，确保加密后的代码执行效率。</p>
                </div>
                
                <div class="feature">
                    <h3>🛡️ 兼容性</h3>
                    <p>加密后的代码完全兼容PHP环境，无需额外依赖。</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function copyToClipboard() {
            const codeOutput = document.querySelector('.code-output');
            const text = codeOutput.textContent;
            
            navigator.clipboard.writeText(text).then(function() {
                const btn = document.querySelector('.copy-btn');
                const originalText = btn.textContent;
                btn.textContent = '✅ 已复制';
                btn.style.background = '#27ae60';
                
                setTimeout(function() {
                    btn.textContent = originalText;
                }, 2000);
            }).catch(function(err) {
                console.error('复制失败:', err);
                alert('复制失败，请手动选择并复制代码');
            });
        }
        
        // 自动调整文本框高度
        const textarea = document.getElementById('php_code');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.max(300, this.scrollHeight) + 'px';
        });
    </script>
</body>
</html>
        <?php
    }
}

// 启动Web界面
$interface = new WebInterface();
$interface->render();
?>