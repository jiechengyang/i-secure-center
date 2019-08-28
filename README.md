# iSecureCenter

基于yii2开发的海康威视开发平台api接口组件，组件封装了接口的验证，使用者需要调用send()方法到指定url上并能获取到数据

===============================

安装
---------------
1. 使用composer
     composer的安装以及国内镜像设置请点击[此处](http://www.phpcomposer.com/)
     
     ```bash
     $ cd /path/to/iSecureCenter
     $ composer require "jiechengyang/iSecureCenter"
     $ composer install -vvv
     ```
2. 手动导入
    下载iSecureCenter包后放置在任意目录
    然后在yii2 index.php中
    ```php
       require "/path/to/iSecureCenter/autoload.php";
    ```
 

配置
-------------
iSecureCenter是作为一个组件提供服务的，所以得配置yii2 cdn组件。打开common/config/main.php在components块内增加
见[config/main.php](config/main.php)

示例
-------------
见[examples/demo.php](examples/demo.php)


说明
-------------
yii2 cdn集成了国内常用四个cdn厂商的sdk，以yii2组件的方式提供服务，屏蔽了厂商不同的api名称、调用方式。但是yii2 cdn只实现了几个常用的api：上传文件 分片上传 检测文件是否存在 删除文件。其他相应的操作需要自行实现。