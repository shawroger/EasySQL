EasySQL 使用说明
=====
EasySQL是一个一个简单的PHP封装库，只需要一个文件就可以快速连接数据库数据表。

# 如何使用？
很简单，下载后 放置在你需要的目录就可以。

然后在你需要使用的PHP文件中载入，就像这样：
```PHP
include "$ADDRESS/EasySQL.php"; 
```
即可引用。

即使你修改这个文件名也没有影响，只需要在引用的时候也改成一样即可。

# 使用方法

## 1.申请一个RD类的对象
```PHP
$TEST = new EasySQL(参数1;参数2;参数3;参数4;参数5);
```
其中：

* 参数1：你的主机名

* 参数2：你的登陆用户名

* 参数3：你的登陆密码

* 参数4：你的数据库名

* 参数5：你的数据表名

如果一切正确，你已经直接连接到了数据库及其数据表，且成功设置了UTF-8语言格式。

## 2.获取数据信息

### (1) 获取数据表长度

调用 **size()** 方法，可以直接获得目标数据表的长度。

```PHP
$length = $TEST->size();
```

这里默认是查询已经连接的数据表。

如果你还想查询同一个数据库内的别的数据表的长度，你也可以直接加入参数：

```PHP
$length = $TEST->size($AnotherTableName);
```

而对于已经连接的数据表，可以这样直接获得数据长度：

```PHP
$length = $TEST->length;
```

### (2)获取指定条件的数据

假设我们想要获得目标 **name="Roger"** 的数据其他的信息(如age等)我们可以先指定一个mysql语句：

```SQL
$sql = "SELECT * FROM {$TEST->TABLE} WHERE name='Roger'";
```

然后可以调用 **get()** 方法：

```PHP
$TSET->get($sql);
```

然后就可以直接使用 **GET[]** 数组获得数据：

```PHP
echo $TSET->GET['age'];
```

### (3)增加、修改、删除指定数据

三种目的对应同一个方法 **run()**。

都是需要一条SQL语句，然后调用方法完成操作。

```SQL
$sql = "INSERT INTO {$TABLE} (name,age) VALUES 'Mark',18";  //增加数据
```

```SQL
$sql = "UPDATE {$TABLE} SET age=XXX WHERE name='Roger'";  //修改数据
```

```SQL
$sql = "DELETE FROM {$TABLE} WHERE name='Roger'";  //删除数据
```

然后均执行**run()** 方法即可：

```PHP
$TSET->run($sql);
```

### (4)直接查询数据表内的字段

对于一个未知的数据表，我们可以直接检索其所有的字段名和其数量。

使用 **col()** 方法我们就可以获得其中的数据。

**(TIPS: 已连接的数据表的方法在构造时已经自动调用，所以在使用时不必重复调用，但我们如若需要其他数据表的内容，可以使用col($AnotherTableName)来完成)**

通过该方法的参数，我们提供了一个参数和数组：

```PHP
echo $TSET->width;  //输出字段数 (从0开始)
```

```PHP
print_r($TSET->COL);  //输出字段名的数组 
```
### (5)简单获取数据，EasyGet的eget()方法

这是最近更新增加的方法 (2019年7月15日)。

调用自带的 **eget()** 方法，可以在需要直接查询单个条件数据的其他字段信息时比较方便，**但若条件满足的数据不唯一，则只能获取第一个数据，因此并不建议使用。**

使用方法：

```PHP
$TSET->eget($已知字段名,$已知数据);  
echo $TSET->GET['$其他字段名'];
```

使用该方法的好处是无需写出sql语句，在可以写出sql时候完全可以用 **get()** 方法替代。

### (6)遍历所有数据

所有的表中的数据在对象构造的时候都被储存在一个二维数组 **$this->CROSS** 内，使用的是 **cross()** 方法，构造时自动调用，无需再次调用。

```PHP
print_r($TEST->CROSS);  //直接输出所有数据内容
```

```PHP
echo $TEST->CROSS[m][n];  //输出表中坐标(m,n)的数据
```

# 案例

### 1.假设我们在数据库year2019、数据表table02，有学生的姓名和学校数据：

利用 **EasySQL.php** 我们可以很简短地写出代码：

```PHP
include "EasySQL.php"; //引入文件

$conn = new EasySQL('HOST','USER','PASSWORD','year2019','table02'); //连接数据库

for($t=0;$t<$conn->length;$t++) {

    for($p=0;$p<$conn->width;$p++){
    
        echo $conn->CROSS[$t][$p]."\r\n";
    }
}
```

# 感谢
