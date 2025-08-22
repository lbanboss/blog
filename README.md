# 🔐 专业PHP编码加密器

一个功能强大的PHP源代码混淆加密工具，用于保护您的PHP代码知识产权。

## ✨ 主要特性

- **🔒 多层加密**: 支持1-10层加密，每层使用不同的加密算法
- **🔄 变量混淆**: 自动混淆变量名、函数名，使代码难以理解
- **🎭 控制流混淆**: 添加随机条件语句和垃圾代码
- **📦 字符串加密**: 对字符串进行特殊加密处理
- **⚡ 高性能**: 优化的加密算法，确保执行效率
- **🛡️ 兼容性**: 加密后的代码完全兼容PHP环境

## 📁 文件结构

```
php-encoder/
├── php_encoder.php          # 基础版加密器
├── advanced_encoder.php     # 增强版加密器
├── web_interface.php        # Web界面
├── example.php             # 示例PHP文件
└── README.md               # 使用说明
```

## 🚀 快速开始

### 方法一：Web界面（推荐）

1. 启动PHP内置服务器：
```bash
php -S localhost:8000
```

2. 在浏览器中访问：
```
http://localhost:8000/web_interface.php
```

3. 在Web界面中输入PHP代码，选择加密参数，点击"开始加密"

### 方法二：命令行

#### 加密单个文件
```bash
# 基础版
php php_encoder.php example.php

# 增强版
php advanced_encoder.php example.php output.php 5 high
```

#### 批量加密目录
```bash
# 基础版
php php_encoder.php -d /path/to/php/files

# 增强版
php advanced_encoder.php -d /path/to/php/files /path/to/output 3 medium
```

## 📖 详细使用说明

### 命令行参数

#### 基础版加密器 (`php_encoder.php`)
```bash
php php_encoder.php <输入文件> [输出文件]
php php_encoder.php -d <输入目录> [输出目录]
```

#### 增强版加密器 (`advanced_encoder.php`)
```bash
php advanced_encoder.php <输入文件> [输出文件] [加密层数] [混淆级别]
php advanced_encoder.php -d <输入目录> [输出目录] [加密层数] [混淆级别]
```

**参数说明：**
- `加密层数`: 1-10，默认3
- `混淆级别`: low/medium/high，默认high

### 编程接口

#### 基础版
```php
require_once 'php_encoder.php';

$encoder = new PHPEncoder();
$encryptedCode = $encoder->encrypt($phpCode);
$encoder->encryptFile('input.php', 'output.php');
```

#### 增强版
```php
require_once 'advanced_encoder.php';

$encoder = new AdvancedPHPEncoder(null, 5, 'high');
$encryptedCode = $encoder->encrypt($phpCode);
$encoder->encryptFile('input.php', 'output.php');
```

## 🔧 加密技术详解

### 1. 多层加密算法
- **第1层**: Hex编码
- **第2层**: Base64编码
- **第3层**: 自定义编码
- **第4层**: XOR加密
- 循环使用以上算法

### 2. 变量混淆
- 自动检测变量名
- 生成随机混淆名称
- 保持代码逻辑不变

### 3. 控制流混淆
- 添加随机条件语句
- 插入垃圾代码
- 混淆执行流程

### 4. 字符串加密
- 检测字符串常量
- 多层加密处理
- 动态解码执行

## 📋 加密示例

### 原始代码
```php
<?php
function hello($name) {
    echo "Hello, " . $name . "!";
}
hello("World");
?>
```

### 加密后代码
```php
if(!defined("A__AAAA_A"))define("A__AAAA_A","CFA__ACCE");$GLOBALS[A__AAAA_A]=explode('|r|1|*|','H*|r|1|*|66756e6374696f6e2068656c6c6f28246e616d6529207b206563686f202248656c6c6f2c2022202e20246e616d65202e202221223b207d2068656c6c6f2822576f726c6422293b');if(!defined(pack($GLOBALS[A__AAAA_A][0x0],$GLOBALS[A__AAAA_A][1])))call_user_func(pack($GLOBALS[A__AAAA_A][0x0],$GLOBALS[A__AAAA_A][2]),pack($GLOBALS[A__AAAA_A][0x0],$GLOBALS[A__AAAA_A][1]),pack($GLOBALS[A__AAAA_A][0x0],$GLOBALS[A__AAAA_A][0x3]));$GLOBALS[CF__C_AA]=array(&$_POST);
```

## ⚠️ 注意事项

1. **备份原始代码**: 加密是不可逆的，请务必备份原始代码
2. **测试加密结果**: 加密后请测试代码是否正常运行
3. **性能考虑**: 加密层数越多，执行效率越低
4. **兼容性**: 确保目标环境支持PHP 7.0+

## 🔍 故障排除

### 常见问题

**Q: 加密后的代码无法运行？**
A: 检查PHP版本兼容性，确保使用PHP 7.0+

**Q: 加密速度很慢？**
A: 减少加密层数，或使用较低的混淆级别

**Q: 内存不足？**
A: 对于大文件，建议分批处理或增加PHP内存限制

**Q: 命令行参数错误？**
A: 检查参数格式，确保文件路径正确

## 📞 技术支持

如果您遇到问题或有改进建议，请：

1. 检查本文档的故障排除部分
2. 确保您的PHP环境配置正确
3. 提供详细的错误信息和复现步骤

## 📄 许可证

本项目仅供学习和研究使用。请遵守相关法律法规，不得用于非法用途。

## 🔄 更新日志

### v2.0 (增强版)
- 新增多层加密支持
- 增强变量混淆功能
- 添加控制流混淆
- 优化字符串加密
- 新增Web界面

### v1.0 (基础版)
- 基础加密功能
- 变量名混淆
- 字符串加密
- 命令行支持

---

**⚠️ 免责声明**: 本工具仅用于代码保护和合法用途。使用者需自行承担使用风险，开发者不承担任何法律责任。