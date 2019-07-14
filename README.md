RD_connect 教程
=====
一个简单的PHP封装库，只需要一个文件就可以快速连接数据库。

# 如何使用？
很简单，下载后 放置在你需要的目录就可以。

然后在你需要使用的PHP文件中载入，就像这样：
```PHP
include "$ADDRESS/RD_connect.php"; 
```
即可引用。

即使你修改这个文件名也没有影响，只需要在引用的时候也改成一样即可。

# 使用方法

## 1.申请一个RD类的对象
```PHP
$TEST = new RD(参数1;参数2;参数3;参数4);
```
其中：

* 参数1：你的主机名

* 参数2：你的登陆用户名

* 参数3：你的登陆密码

* 参数4：你的数据库名

如果一切正确，你已经直接连接到了数据库，且成功设置了UTF-8语言格式。

## 2.获取数据信息




