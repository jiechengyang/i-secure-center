# iSecureCenter

基于yii2开发的海康威视开发平台api接口组件，目前组件封装了资源接口和视频接口，后续打算把全部接口封装。

===============================

[![Latest Stable Version](https://poser.pugx.org/jiechengyang/i-secure-center/v/stable)](https://packagist.org/packages/jiechengyang/i-secure-center)
[![Total Downloads](https://poser.pugx.org/jiechengyang/i-secure-center/downloads)](https://packagist.org/packages/jiechengyang/i-secure-center)
[![Latest Unstable Version](https://poser.pugx.org/jiechengyang/i-secure-center/v/unstable)](https://packagist.org/packages/jiechengyang/i-secure-center)
[![License](https://poser.pugx.org/jiechengyang/i-secure-center/license)](https://packagist.org/packages/jiechengyang/i-secure-center)
[![composer.lock](https://poser.pugx.org/jiechengyang/i-secure-center/composerlock)](https://packagist.org/packages/jiechengyang/i-secure-center)

安装
---------------
1. 使用composer
     composer的安装以及国内镜像设置请点击[此处](http://www.phpcomposer.com/)
     
     ```bash
     $ cd /path/to/i-secure-center
     $ composer require "jiechengyang/i-secure-center"
     $ composer install -vvv
     ```
2. 手动导入
    下载i-secure-center包后放置在任意目录
    然后在yii2 index.php中
    ```php
       require "/path/to/i-secure-center/autoload.php";
    ```
关于组件
-------
####设计的想法：
  * 组件是继承了yii\base\Component,因为想把事件和行为也结合起来使用。写组件的时候，如果不想用事件、行为这些影响性能的特性可以直接继承yii\base\BaseObject写。
  * 组件提供了before_send和after_send两个事件
    * before_send可以用来修改我们的一些配置参数（比如：安防平台的地址，默认的地址在系统加载就固定了，那如果我们要换成另一个地址就可以在bofore_send里面处理了），可以加入判断，比如用户权限不过直接return false，就无需请求安防平台了
    * after_send里面有返回的数据，我们可以做一些参数校验之类的
    * 总而言之，使用事件的好处便是提前把接口请求前后的事情都注入到事件。还有这个组件提供的事件主要还是为了学了yii2的组件的思想，用不用无妨，用的话，可以根据自己的业务好好的完善，我只是提供了一个写yii2组件的demo
  * 组件配置的ak配置是为了实现多合作方请求的，使用综合安防的朋友都知道，合作方是有多个的，每个合作方的权限也不一致，请求接口返回的数据也会和合作方的权限有关系，因此在这样的考虑之下，便把它设计成了一个多合作方的操作。`需要强调的是：必须配置一个默认的合作方adminPartner，建议是配置对内合作方`
####接口实现的想法：
 * 这次接口实现有幸在github上看到一位朋友的设计，让我耳目一新，于是我便借着这个思路开发了，以下我将说明这个接口设计。
 
   *第一个想法：外部调用组件的send的方法，传递两个参数：A、安防接口地址B、对应A需要的参数。在send里面直接把验签的参数和提交的参数配置，使用http-client处理。
   *第二个想法：第一想法的诟病就是安防接口地址太长，既然是个组件，用起来不方面，于是乎便想到了枚举，用一个文件专门作一个数组映射，用相近的单词作为key，接口地址作为value。其他和第一个想法一样。
   *第三个想法：这便是看了那位朋友的设计之后，让我有了相同的想法。我先按照安防接口的大分类定义几个目录，然后把小分类写成文件放在大目录里，这里的文件就是一个接口类，这个接口类都会继承BaseApi的抽象类。外部调用的时候，先实例化某个接口类作为第一个参数，第二个参数则是类里方法名，这个方法名就代表具体的接口地址。
   *第四个想法：组件的里面加入接口服务层service，由service层具体请求数据，这样组件的send方法便有了第三个参数合作方，这个参数便是实现我们的多合作方请求接口。
   
**`总结`**
-
通过第三、四个想法的结合，我发现了它的一些好处：
1. 我们调用人家的接口总有些必须携带的参数，在接口类的每个方法都预先的做了预警操作，必要的参数外部调用未携带时，send会直接返回错误提示，而像分页参数、时间参数方法里也设置了默认值和时间格式转换，外部调用简单调用就可以了。而这样的想法和上面那位朋友也是不谋而合，因为我没细看他的代码，在我接口调用出现问题时，发现他的做法也是如此。
2. 合理的使用yii2-http-client组件，我封装了http请求组件，因为我在init里面，写了before_send和after_send的两个事件。service调用只需把请求的地址，参数，验参的参数提供过去，具体怎么接口认证，那就是before_send的事了，最后返回的参数需要做一些变化，也是after_send做的了，service它只需发送了，把想要的数据给它就行了。
哈哈，yii2的思想真的很nice。
3. baseApi代码和注释如下:
```php
abstract class BaseApi
{
    protected $error;// 用于存放接口必填参数未填的错误

    protected $_uriMap;// 接口的方法与接口地址的映射，每个接口类都预先设置好的

    protected $requestUri;// 接口地址

    public $requestBody;// 接口请求参数

    /**
     * @return mixed
     */
    public function getUriMap()
    {
        return $this->_uriMap;
    }

    /**
     * @return mixed
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * @return mixed
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * 获取action对应的地址
     * @return mixed
     */
    public function getUri($action)
    {
        if (empty($action) || empty($this->_uriMap[$action])) {
            return null;
        }

        $this->requestUri = $this->_uriMap[$action];

        return $this->requestUri;
    }

    /**
     * 接口存在该方法便调用
     * @return bool
     */
    public function runAction($action, $message = [])
    {
        if (!is_callable([$this, $action])) {
            return false;
        }

        call_user_func([$this, $action], $message);

        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getErrorFormat($glue = PHP_EOL)
    {
        return implode($glue, $this->getError());
    }

    public function setError($error)
    {
        $this->error = $error;
    }
}
```
配置
-------------
i-secure-center是作为一个组件提供服务的，所以得配置yii2 iSecureCenter组件。打开common/config/main.php在components块内增加如下配置：
配置见[config/main.php](config/main.php)

参数说明
-------------
示例
-------------
见[examples/demo.php](examples/demo.php)


说明
-------------
要在github发布release才可以composer require，不然你只能用dev-master版本

yii2-iSecureCenter 集成了[https://open.hikvision.com]
常用接口，采用组件的形式主要为了实现参数可配置，事件化处理业务。

**_目前组件还在完善中...._**