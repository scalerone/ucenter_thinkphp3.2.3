
Thinkphp 整合UCenter
====
整体构思:

服务器环境为apache, 根据apache的vhost在本地虚拟出两个域名: `tpuc.la` 和 `tpuc2.la`, 

tpuc.la登录, tpuc2.la可以得到对应的登录信息就说明成功了 

本文只是一个最简单的demo, 如果需要更多的功能, 请到官网查看api接口



同步登录流程:

在tpuc.la登录退出之后, 会回调UCenter`应用列表`里面的应用的`syslogin`, `syslogout`函数, 进行写cookie或者清楚cookie, 或者其他的 操作(可自定义)

# UCenter

##安装UCenter

下载请到ucenter的官网下载:

http://www.comsenz.com/downloads/install/ucenter

安装到你的电脑上面

## 安装应用

clone 例子

在apache的www目录下新建tpuc和tpuc2两个目录, 分别把代码克隆到里面

## 配置域名环境

### 配置vhost

打开apache的`vhost.conf`, 在结尾处加上

```
<VirtualHost _default_:80>
DocumentRoot "F:\www\tpuc"
ServerName www.tpuc.la
ServerAlias tpuc.la
SetEnv APPLICATION_ENV "tpuc"
  <Directory "F:\www\tpuc">
    Options +Indexes +FollowSymLinks +ExecCGI
    AllowOverride All
    Order allow,deny
    Allow from all
    Require all granted
  </Directory>
</VirtualHost>

<VirtualHost _default_:80>
DocumentRoot "F:\www\tpuc2"
ServerName www.tpuc2.la
ServerAlias tpuc2.la
SetEnv APPLICATION_ENV "tpuc2"
  <Directory "F:\www\tpuc2">
    Options +Indexes +FollowSymLinks +ExecCGI
    AllowOverride All
    Order allow,deny
    Allow from all
    Require all granted
  </Directory>
</VirtualHost>
```

重启apache

### 配置hosts文件

打开系统的`hosts`文件, 在文件末尾处, 加入

```
127.0.0.1 www.tpuc.la
127.0.0.1 tpuc.la
127.0.0.1 www.tpuc2.la
127.0.0.1 tpuc2.la
```

## 配置UCenter

### 增加测试用户

在用户管理里面先增加2,3个用户, 待之后测试

### 增加测试应用
选中`应用管理` -> `添加新应用`

需要配置

test1: 

```
应用类型 -> 其它
应用名称 -> 我取为test1, 名称自取
用用的主URL -> http://tpuc.la/index.php/Ucenter/Api
通信密钥 -> 随便输入字母数字
是否开启同步登录 -> 是
是否接受通知 -> 是
```

点击提交, 然后把最下面的 `应用的 UCenter 配置信息`全部复制到tpuc的Ucenter模块 > Conf文件夹 > config.php, 覆盖对应的配置文件

 ![20160927180730](pic\20160927180730.jpg)

覆盖这里的

 ![20160927180932](pic\20160927180932.jpg)

test2: 

```
应用类型 -> 其它
应用名称 -> 我取为test2, 名称自取
用用的主URL -> http://tpuc2.la/index.php/Ucenter/Api
通信密钥 -> 随便输入字母数字
是否开启同步登录 -> 是
是否接受通知 -> 是
```

覆盖同上



保存之后,  返回到应用管理, 确保都通信成功

 ![20160927172619](pic\20160927172619.jpg)


# 修改

如果你需要修改来自己使用, 其实只需要修改下面框住的3个文件就可以了, 其他的文件都可以不动

 ![sp161024_145018](pic\sp161024_145018.jpg)

## config.php 

是从ucenter上面复制下来的配置

## ApiService

里面的内容大致如下

```
class ApiService{
    // todo login_handler
    public function login($get, $post){
        $uid      = intval($get['uid']);
        $username = $get['username'];
        cookie('auth', _uc_authcode($uid . "\t" . $username, 'ENCODE'), array('prefix' => 'ucenter_'));
    }

    // todo logout_handler
    public function logout(){
        cookie('auth', null, array('prefix' => 'ucenter_'));
    }
}
```

其实就是登录和注册之后的操作, 我选择的是写入cookie, 你可以根据自己的实际需求来操作



## UcenterService

就是对外提供的接口的

我大概实现了, 3个方法, 具体看代码

`login`, `logout`, `get_cookie_info`



# 测试

我主要写在了admin模块的index控制器里面

有3个主要的方法, `syslongin`, `syslogout`, `get_cookie_info`

在浏览器的地址栏输入, 把xxx补充为刚才新添加的用户的username和password

http://tpuc.la/index.php?m=Admin&c=Index&a=syslogin&username=xxx&password=xxx
 ![sp161024_150420](pic\sp161024_150420.jpg)
 会有`登录成功`的显示

 ![sp161024_150532](pic\sp161024_150532.jpg)

 ![sp161024_150551](pic\sp161024_150551.jpg)


# 案例

Ucenter带来的好处, 我就不说了, 拿现在我公司的案例来说吧, 

统一的登录和退出界面`account.xxx.com`

其他的应用app1.xxx.com需要登录和退出的时候直接重定向到account登录和退出, 

其他的应用就不需要做登录, 退出, 注册, 一个帐号全站通用