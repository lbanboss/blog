# 🔐 Ranyun_JiaMi PHP代码加密器

## 📋 项目简介

Ranyun_JiaMi 是一个商业级PHP代码加密保护系统，能够将PHP源代码转换为高度混淆的格式，完全模仿您提供的示例代码算法和格式。本系统采用多层混淆技术，有效防止代码逆向工程和知识产权盗用。

## ✨ 核心特性

### 🎯 完全匹配示例格式
- ✅ **变量名混淆**: 生成如 `AU7xHZ4`、`VQ6nV01` 等复杂变量名
- ✅ **字符串编码**: 十六进制编码存储在全局数组中
- ✅ **控制流混淆**: 大量goto语句和标签跳转
- ✅ **数学表达式**: 使用PHP错误常量的复杂计算
- ✅ **函数调用混淆**: 转换为call_user_func_array形式
- ✅ **完整性保护**: 内置代码验证机制

### 🛡️ 安全特性
- 🔒 **多层加密**: 变量名、字符串、控制流三重混淆
- 🌀 **动态解码**: 运行时动态解码字符串和函数调用
- 🧮 **复杂计算**: 数字替换为复杂的数学表达式
- 🔧 **函数重写**: 标准函数调用转换为混淆形式
- 📊 **数据隐藏**: 敏感数据编码存储

## 📁 文件结构

```
workspace/
├── ranyun_jiami.php          # 主加密器（完整版本）
├── advanced_php_encoder.php  # 高级加密器
├── exact_php_encoder.php     # 精确模仿版本
├── php_encoder.php           # 基础版本
├── test_encoder.php          # 测试文件
└── README.md                 # 说明文档
```

## 🚀 快速开始

### 1. 基础使用

```php
<?php
require_once 'ranyun_jiami.php';

// 创建加密器实例
$encoder = new RanyunJiaMiEncoder();

// 读取源代码
$sourceCode = file_get_contents('your_source.php');

// 生成加密代码
$encryptedCode = $encoder->encode($sourceCode);

// 保存加密后的代码
file_put_contents('encrypted.php', $encryptedCode);

echo "代码加密完成！";
?>
```

### 2. 生成完整包

```php
<?php
// 生成包含Loader的完整包
$package = $encoder->generatePackage($sourceCode);
file_put_contents('complete_package.php', $package);

// 完整包可以直接运行，无需额外配置
?>
```

### 3. 使用Loader解密器

```php
<?php
// 生成独立的Loader
$loader = $encoder->createLoader();
file_put_contents('loader.php', $loader);

// 使用Loader执行加密代码
require_once 'loader.php';
RanyunJiaMiLoader::execute($encryptedCode);
?>
```

## 🌐 Web界面使用

1. 将文件上传到Web服务器
2. 访问 `ranyun_jiami.php`
3. 在文本框中输入要加密的PHP代码
4. 选择加密模式：
   - **🔒 高级加密**: 生成最复杂的混淆代码
   - **🔓 简化加密**: 生成相对简单的混淆代码
   - **🔧 生成Loader**: 创建解密器
   - **📦 完整打包**: 生成可直接运行的完整包

## 📊 加密效果对比

### 原始代码
```php
<?php
$username = $_POST['username'];
if (!empty($username)) {
    echo "Hello " . $username;
}
?>
```

### 加密后代码（示例片段）
```php
<?php
/**
Ranyun_JiaMi 版权所有
**/
if(!defined("A__AAAA_A"))define("A__AAAA_A","CFA__ACCE");
$GLOBALS[A__AAAA_A]=explode('|r|1|*|','H*|r|1|*|646566696E65|r|1|*|7061636B|r|1|*|6578706C6F6465');
unset($AU7xHZ4);$AU7xHZ4=array();$AU7xHZ4[]=&$GLOBALS;
$VQ6nV01=call_user_func_array("is_array",$AU7xHZ4);
if($VQ6nV01){goto UM4xUZ8;}goto UM4xUZ9;
UM4xUZ8:$VQ6nV001=&$GLOBALS[CF__C_AA];goto UM4xUZ10;
UM4xUZ9:$VQ6nV001=$GLOBALS[CF__C_AA];
// ... 更多混淆代码
?>
```

## ⚙️ 配置选项

### 加密级别
- **简化模式**: 基础混淆，适合快速测试
- **标准模式**: 平衡混淆和性能
- **高级模式**: 最高级别混淆，最强保护

### 自定义配置
```php
$encoder = new RanyunJiaMiEncoder();

// 可以通过修改类属性来自定义配置
// 例如：修改全局数组名称模式、分隔符等
```

## 📈 性能指标

| 指标 | 数值 | 说明 |
|------|------|------|
| 代码膨胀率 | 10-50x | 加密后代码体积增长倍数 |
| 执行性能 | 30-70% 下降 | 运行速度影响 |
| 混淆复杂度 | 极高 | 逆向工程难度 |
| 兼容性 | PHP 5.4+ | 最低版本要求 |

## 🔧 技术原理

### 1. 变量名混淆
- 使用复杂的命名规则生成难以理解的变量名
- 格式：`[A-Z]{2}[0-9]x[A-Z]{2}[0-9A-F]{4}`
- 示例：`AU7xHZ4`、`VQ6nV01`、`UM4xUZ8`

### 2. 字符串编码
- 所有字符串转换为十六进制格式
- 存储在全局数组中，运行时动态解码
- 使用不同的分隔符模式：`|r|1|*|`、`|o|1|C|`、`|e|1|6|`等

### 3. 控制流混淆
- 大量使用goto语句打乱执行顺序
- 创建复杂的标签跳转网络
- 添加无用的条件判断和循环

### 4. 数学表达式混淆
- 简单数字替换为复杂的数学运算
- 使用PHP错误常量进行计算
- 示例：`-4098+E_WARNING+8*E_USER_WARNING`

### 5. 函数调用混淆
- 标准函数调用转换为`call_user_func_array`形式
- 添加匿名函数包装
- 使用复杂的参数传递方式

## ⚠️ 注意事项

### 性能影响
- **执行速度**: 加密后代码运行速度会下降30-70%
- **内存占用**: 由于复杂的变量结构，内存占用会增加
- **文件大小**: 代码体积会增大10-50倍

### 兼容性要求
- **PHP版本**: 需要PHP 5.4或更高版本
- **函数支持**: 需要支持`pack`、`unpack`、`hex2bin`等函数
- **错误常量**: 依赖PHP错误常量的正确定义

### 使用限制
- **调试困难**: 加密后代码无法正常调试
- **不可逆**: 加密过程不可逆，必须保留原始代码
- **性能敏感**: 不建议在高性能要求的场景使用

## 🧪 测试验证

运行测试文件验证功能：

```bash
php test_encoder.php
```

测试包括：
- ✅ 基础加密功能测试
- ✅ 高级混淆算法测试  
- ✅ Loader解密器测试
- ✅ 完整包生成测试
- ✅ 性能对比测试
- ✅ 混淆度分析测试

## 📖 API文档

### RanyunJiaMiEncoder 类

#### 主要方法

```php
// 高级加密（完全模仿示例格式）
public function encode($sourceCode)

// 简化加密（基础混淆）
public function encodeSimple($sourceCode)

// 创建Loader解密器
public function createLoader()

// 生成完整包（包含Loader和加密代码）
public function generatePackage($sourceCode)
```

#### 内部方法

```php
// 生成复杂变量名
private function generateVarName()

// 生成数学表达式
private function generateMathExpr($target)

// 创建全局数组
private function createGlobalArrays()

// 创建变量操作链
private function createVariableChain($varName, $arrayName, $baseIndex)
```

### RanyunJiaMiLoader 类

#### 主要方法

```php
// 执行加密代码
public static function execute($code)

// 加载完整包
public static function loadPackage($packageFile)

// 十六进制解码
public static function hexDecode($hex)

// 验证代码完整性
private static function verifyCode($code)
```

## 🔍 示例对比

### 示例1：简单输出

**原始代码:**
```php
echo "Hello World";
```

**加密后:**
```php
unset($AU7xHZ4);$AU7xHZ4=array();
$VQ6nV01=call_user_func_array("is_array",$AU7xHZ4);
if($VQ6nV01){goto UM4xUZ8;}goto UM4xUZ9;
UM4xUZ8:$VQ6nV001=&$GLOBALS[CF__C_AA];
echo pack($GLOBALS[CF__C_AA][-4098+E_WARNING],$GLOBALS[CF__C_AA][8192*E_ERROR]);
```

### 示例2：变量赋值

**原始代码:**
```php
$username = $_POST['username'];
```

**加密后:**
```php
unset($BN7xXT0);$BN7xXT0=array();$BN7xXT0[]=&$GLOBALS;
$IT1xRX5=call_user_func_array("is_array",$BN7xXT0);
if($IT1xRX5){goto VQ6nV01;}goto VQ6nV02;
VQ6nV01:$User_Model=&$GLOBALS[DCBFBNDN_];
$XA4xWF4=call_user_func('addslashes',call_user_func('trim',$GLOBALS[DAFA_DEC][(3264-E_STRICT-128)]));
```

## 📞 技术支持

如果您在使用过程中遇到问题，请检查：

1. **PHP版本兼容性**: 确保使用PHP 5.4+
2. **函数支持**: 确认所需函数可用
3. **内存限制**: 增加PHP内存限制
4. **执行时间**: 增加脚本执行时间限制

## 📄 许可证

本项目仅供学习和研究使用。商业使用请联系获得授权。

## 🤝 贡献

欢迎提交Issue和Pull Request来改进项目。

---

**© 2024 Ranyun_JiaMi - 专业PHP代码保护解决方案**

🛡️ 保护您的知识产权 | 🚀 防止代码逆向工程 | 💼 商业级代码混淆