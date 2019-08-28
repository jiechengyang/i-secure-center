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
yii2-iSecureCenter 集成了[https://open.hikvision.com]的常用接口，采用组件的形式主要为了实现参数可配置，事件化处理业务。

**_目前组件还在完善中...._**