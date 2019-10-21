# 技术备忘

### 自定义命令行

在`App/Config`目录创建类 并实现接口 `EasySwoole\EasySwoole\Command\CommandInterface`
然后在 `/easyswoole`文件 `$customConsole 数组变量`项下面添加此类
