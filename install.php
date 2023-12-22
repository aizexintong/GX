<?php
require "config.ini.php";
function createConfigFile($filePath, $date): void
{
    // 构建文件内容
    $configContent = "[Database]
type = " . $date['type'] . "
dbname = " . $date['dbname'] . "
host = " . $date['host'] . "
port = " . $date['port'] . "
username = " . $date['username'] . "
password = " . $date['password'] . "
";

    // 检查文件是否存在，然后决定是否覆盖文件内容
    file_put_contents($filePath, $configContent);
}

/**
 *
 */
function config($SQL): void
{
    // 检查表是否存在，如果不存在，则创建表格
    try {
        $SQL->query("SELECT * FROM config");
    } catch (Exception $e) {
        if (stripos($e, 'no such table') !== false) {
            $SQL->exec("CREATE TABLE config (name  TEXT PRIMARY KEY,value TEXT)");

            // 检查并插入缺失的配置值
            $valuesToCheck = [
                'AlistUrl', 'AlistUsername', 'AlistPassword', 'Alist2FACode', 'AlistFrequently',
                'AlistSharePrefix', 'Key', 'OriginalBigClass', 'OriginalLittleClass', 'UltimatelyLen',
                'UnwantedElements', 'TimeFirstSort', 'MainModuleDisplay', 'IntroducePrestore', 'Introduce',
                'PicturePrestore', 'Picture'
            ];

            foreach ($valuesToCheck as $value) {
                $SQL->perform(
                    "INSERT INTO config (name, value) VALUES (:name, :value)",
                    ['name' => $value, 'value' => ''] // 这里设置value为默认空字符串
                );
            }
        }
    }
}

function ConfigInstall(): void
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // 验证和处理表单数据
        $postData = [
            "type" => $_POST["type"] ?? "",
            "dbname" => $_POST["dbname"] ?? "",
            "host" => $_POST["host"] ?? "",
            "port" => $_POST["port"] ?? "",
            "username" => $_POST["username"] ?? "",
            "password" => $_POST["password"] ?? ""
        ];

        createConfigFile(__GX_CONFIG_DIR__, $postData);

        // 处理完成后重定向到当前页面
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    echo "
<!DOCTYPE html>
<html lang='zh'>
<head>
    <title>数据库链接配置</title>
    <link href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\" rel=\"stylesheet\">
    <style>
        .mysql-field,
        .sqlite-field {display: none;}
    </style>
    <script>
        function Toggle() {
            let type = document.getElementById(\"type\").value;
            let mysql = document.querySelectorAll(\".mysql-field\");
            let sqlite = document.querySelectorAll(\".sqlite-field\");
            if (type === \"Mysql\") {
                mysql.forEach(function (field) {
                    field.classList.remove(\"d-none\");
                });
                mysql.forEach(function (field) {
                    field.classList.add(\"d-block\");
                });
                sqlite.forEach(function (field) {
                    field.value = \"GX\";
                    field.classList.remove(\"d-block\");
                    field.classList.add(\"d-none\");
                });
            } else if (type === \"Sqlite3\") {
                mysql.forEach(function (field) {
                    field.classList.remove(\"d-block\");
                    field.classList.add(\"d-none\");
                });
                sqlite.forEach(function (field) {
                    field.value = \"GX.DB\";
                    field.classList.remove(\"d-none\");
                    field.classList.add(\"d-block\");
                });
            }
        }

        function refreshPage() {
            location.reload();
        }

        window.onload = function () {
            Toggle();
        };
    </script>
</head>
<body>
<div class=\"container mt-5\">
    <form action=\"$_SERVER[PHP_SELF]\" method=\"post\">
        <h1 style=\"text-align: center;\">数据库连接信息写入</h1>
        <div class=\"form-group\">
            <label for=\"type\">数据库类型：</label>
            <select name=\"type\" id=\"type\" onchange=\"Toggle()\" class=\"form-control\">
                <option value=\"Sqlite3\">Sqlite3</option>
                <option value=\"Mysql\">Mysql</option>
            </select>
            <small class=\"form-text text-muted\">选择数据库类型，现在只支持 Sqlite3 或者 Mysql</small>
        </div>
        <div class=\"form-group\">
            <label for=\"dbname\" class=\"sqlite-field\">数据库名称：</label>
            <input type=\"text\" name=\"dbname\" id=\"dbname\" class=\"form-control sqlite-field d-none\"/>
            <small class=\"sqlite-field form-text text-muted\">多用于本地数据库，本地数据库的名字，记得需要带上文件后缀</small>
        </div>
        <div class=\"form-group\">
            <label for=\"host\" class=\"mysql-field\">主机名：</label>
            <input type=\"text\" name=\"host\" id=\"host\" value=\"localhost\" class=\"form-control mysql-field d-none\"/>
            <small class=\"mysql-field form-text text-muted\">多用于数据库链接地址 默认是 本地回环 的 localhost</small>
        </div>
        <div class=\"form-group\">
            <label for=\"port\" class=\"mysql-field\">端口号：</label>
            <input type=\"text\" name=\"port\" id=\"port\" class=\"form-control mysql-field d-none\" value=\"3306\"/>
            <small class=\"mysql-field form-text text-muted\">多用于数据库链接地址端口 默认是 Mysql 的 3306</small>
        </div>
        <div class=\"form-group\">
            <label for=\"username\" class=\"mysql-field\">用户名：</label>
            <input type=\"text\" name=\"username\" id=\"username\" class=\"form-control mysql-field d-none\" placeholder=\"登录账户\"/>
            <small class=\"mysql-field form-text text-muted\">多用于数据库链接 登录账户</small>
        </div>
        <div class=\"form-group\">
            <label for=\"password\" class=\"mysql-field\">密码：</label>
            <input type=\"password\" name=\"password\" id=\"password\" class=\"form-control mysql-field d-none\" placeholder=\"登录密码\"/>
            <small class=\"mysql-field form-text text-muted\">多用于数据库链接 登录密码</small>
        </div>
        <input type=\"submit\" value=\"提交\" class=\"btn btn-danger\" onclick=\"refreshPage()\"/>
    </form>
</div>
</body>
</html>";
}

function SqlInstall($SQL, $configItems): void
{


    // 处理表单提交
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        foreach ($_POST as $name => $value) {
            // 更新配置项
            $updateQuery = $SQL->prepare("UPDATE config SET value = :value WHERE name = :name");
            $updateQuery->execute([':value' => $value, ':name' => $name]);
        }
        // 处理完成后重定向到当前页面
        header("Location: ./");
        exit();
    }

    // 显示配置项表单
    echo "
<!DOCTYPE html>
<html lang='zh'>
    <head>
        <title>数据库信息写入</title>
        <link href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\" rel=\"stylesheet\">
        <style>
            .mysql-field,
            .sqlite-field {display: none;}
        </style>
        <script>
            function refreshPage() {
            location.reload();
            }
        </script>
    </head>
    <body>
        <div class=\"container mt-5\" style='margin-bottom: 10%'>
            <h1 style=\"text-align: center;\">数据库必要信息写入</h1>
            <form action=\"$_SERVER[PHP_SELF]\" method=\"post\">
                <div class=\"form-group\">
                    <label for=" . $configItems[0]["name"] . ">Alist地址链接：</label>
                    <input type=\"text\" name=" . $configItems[0]["name"] . " id=" . $configItems[0]["name"] . " class=\"form-control\" placeholder=" . $configItems[0]["name"] . ">
                    <small class=\"form-text text-muted\">例如：https://localhost:5244 或者 https://localhost</small>
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[1]["name"] . ">Alist用户账户：</label>
                    <input type=\"text\" name=" . $configItems[1]["name"] . " id=" . $configItems[1]["name"] . " class=\"form-control\" placeholder=" . $configItems[1]["name"] . ">     
                    <small class=\"form-text text-muted\">这个一般都是自己设置的</small>    
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[2]["name"] . ">Alist用户密码：</label>
                    <input type=\"text\" name=" . $configItems[2]["name"] . " id=" . $configItems[2]["name"] . " class=\"form-control\" placeholder=" . $configItems[2]["name"] . ">
                    <small class=\"form-text text-muted\">这个一般都是自己设置的</small>            
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[3]["name"] . ">Alist的2FA验证：</label>
                    <div style=\"display: flex; flex-direction: row; align-items: center;\">
                        <div style='width: 100%; margin-right: 10px;'>
                            <input type=\"text\" name=" . $configItems[3]["name"] . " id=" . $configItems[3]["name"] . " class=\"form-control\" placeholder=" . $configItems[3]["name"] . ">
                            <small class=\"form-text text-muted\">这个不开启则不填，填完后点击按钮获取验证码</small>      
                        </div>
                        <div style='width: 15%'>
                            <button type=\"button\" id=\"2FA\" class=\"btn btn-primary\">点击获取验证码</button>
                            <small class=\"form-text text-muted\">2FA验证码：显示功能还没完成</small>      
                        </div>
                    </div>      
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[4]["name"] . ">Alist的Token过期时间：</label>
                    <input type=\"text\" name=" . $configItems[4]["name"] . " id=" . $configItems[4]["name"] . " class=\"form-control\" placeholder=" . $configItems[4]["name"] . ">    
                    <small class=\"form-text text-muted\">这个可以在 Alist 内设置默认是 8 小时：</small>           
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[5]["name"] . ">Alist分享链接前缀：</label>
                    <input type=\"text\" name=" . $configItems[5]["name"] . " id=" . $configItems[5]["name"] . " class=\"form-control\" placeholder=" . $configItems[5]["name"] . ">    
                    <small class=\"form-text text-muted\">这个自己填，不想写自动提取了，分享一个链接赋值 /d 前的，如是账户指定了目录那就是 /d/指定目录 例如：https://127.0.0.1:5244/d/1 我的账户指定目录为1</small>            
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[6]["name"] . ">加密密文：</label>
                    <input type=\"text\" name=" . $configItems[6]["name"] . " id=" . $configItems[6]["name"] . " class=\"form-control\" placeholder=" . $configItems[6]["name"] . ">         
                    <small class=\"form-text text-muted\">顾名思义用来加密的，越难越好，自己设置吧建议直接脸滚键盘：</small>     
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[7]["name"] . ">大分类探寻深度：</label>
                    <input type=\"text\" name=" . $configItems[7]["name"] . " id=" . $configItems[7]["name"] . " class=\"form-control\" placeholder=" . $configItems[7]["name"] . "> 
                    <small class=\"form-text text-muted\">我是按照目录来分类的，一般是账户根目录下的目录变成大分类（这个时候深度为1），相关介绍进入下面链接：</small>             
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[8]["name"] . ">二级目录探寻深度：</label>
                    <input type=\"text\" name=" . $configItems[8]["name"] . " id=" . $configItems[8]["name"] . " class=\"form-control\" placeholder=" . $configItems[8]["name"] . ">       
                    <small class=\"form-text text-muted\">是根据大分类的进一步分化，一般这个分化就是我们显示的主页，相关介绍进入下面链接：</small>            
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[9]["name"] . ">最后探寻深度：</label>
                    <input type=\"text\" name=" . $configItems[9]["name"] . " id=" . $configItems[9]["name"] . " class=\"form-control\" placeholder=" . $configItems[9]["name"] . ">      
                    <small class=\"form-text text-muted\">二级目录往下寻找资源的深度，相关介绍进入下面链接：</small>         
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[10]["name"] . ">要屏蔽的文件夹：</label>
                    <input type=\"text\" name=" . $configItems[10]["name"] . " id=" . $configItems[10]["name"] . " class=\"form-control\" placeholder=" . $configItems[10]["name"] . ">     
                    <small class=\"form-text text-muted\">有些文件夹并不想被显示出来，就可以使用这个进行屏蔽，可以屏蔽多个记得使用英文逗号 例如：测试文件夹,备份,BT,Attachments,@ 等</small>        
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[11]["name"] . ">二级分类第一时间排序：</label>
                    <input type=\"text\" name=" . $configItems[11]["name"] . " id=" . $configItems[11]["name"] . " class=\"form-control\" placeholder=" . $configItems[11]["name"] . ">     
                    <small class=\"form-text text-muted\">一般为了方便我们会选择吧当前最新放在最上方</small>       
                    <small class=\"form-text text-muted\">现在只支持： 月份（输入月份） 季度（输入季度） 星期（输入星期）</small>       
                    <small class=\"form-text text-muted\">如果没有你想要的可以自定义，乱输入或者为空则不进行第一时间排序，相关介绍进入下面链接：</small>       
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[12]["name"] . ">主体显示模块选择：</label>
                    <input type=\"text\" name=" . $configItems[12]["name"] . " id=" . $configItems[12]["name"] . " class=\"form-control\" placeholder=" . $configItems[12]["name"] . ">  
                    <small class=\"form-text text-muted\">现在只支持：</small>           
                    <small class=\"form-text text-muted\">只有文字显示(输入-1)</small>           
                    <small class=\"form-text text-muted\">以log代替图片显示（输入1）</small>           
                    <small class=\"form-text text-muted\">获取图片链接显示（输入2）</small>           
                    <small class=\"form-text text-muted\">使用预存储图片要配合下面的预存储（输入3）</small>           
                    <small class=\"form-text text-muted\">如果没有你想要的可以自定义，乱输入或者为空则不进行第一时间排序，相关介绍进入下面链接：</small>           
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[13]["name"] . ">是否开启简介预存储：</label>     
                    <select name=" . $configItems[13]["name"] . " id=" . $configItems[13]["name"] . "  class=\"form-control\">
                        <option value=\"False\">False</option>
                        <option value=\"True\">True</option>
                    </select>
                    <small class=\"form-text text-muted\">使用或者不使用，建议不使用，如果数据很大可以使用，配合显示预存储简介更加</small>    
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[14]["name"] . ">简介统一名称：</label>
                    <input type=\"text\" name=" . $configItems[14]["name"] . " id=" . $configItems[14]["name"] . " class=\"form-control\" placeholder=" . $configItems[14]["name"] . ">   
                    <small class=\"form-text text-muted\">自己设置记住必须是txt后缀，例如：introduce.txt</small>           
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[15]["name"] . ">是否开启简图片存储：</label>
                    <select name=" . $configItems[15]["name"] . " id=" . $configItems[15]["name"] . "  class=\"form-control\">
                        <option value=\"False\">False</option>
                        <option value=\"True\">True</option>
                    </select>
                    <small class=\"form-text text-muted\">使用或者不使用，建议不使用，如果数据很大可以使用，配合显示预存储图片更加</small>      
                </div>
                <div class=\"form-group\">
                    <label for=" . $configItems[16]["name"] . ">图片统一名称：</label>
                    <input type=\"text\" name=" . $configItems[16]["name"] . " id=" . $configItems[16]["name"] . " class=\"form-control\" placeholder=" . $configItems[16]["name"] . ">  
                    <small class=\"form-text text-muted\">自己设置，例如：picture.jpg</small>            
                </div>
                    <input style=\"margin-top: 2%\" type=\"submit\" value=\"提交\" class=\"btn btn-danger\"/>
            </form>
        </div>
    </body>
</html>
";
}

// 如果数据库信息不完整，显示 HTML 表单
if (!file_exists(__GX_CONFIG_DIR__)) {
    ConfigInstall();
} else {
    require_once __GX_DATABASE_DIR__;
    if (!isset($SQL)) {
        $SQL = SQL();
    }

    config($SQL);

    // 查询所有配置项
    $configQuery = $SQL->query("SELECT * FROM config");
    $configItems = $configQuery->fetchAll(PDO::FETCH_ASSOC);

    if (!isset($configItems[2]["value"]) || $configItems[2]["value"] == "") {
        SqlInstall($SQL, $configItems);
    } else {
        // 处理完成后重定向到当前页面
        header("Location: ./");
        exit();
    }
}
