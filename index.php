<?php
$startTime = microtime(true);
session_start();

// 引入h5开始字段
echo "<!DOCTYPE html><html lang='zh-CN'>";

require_once "config.ini.php";

// 检查数据库配置文件是否存在
if (file_exists(__GX_CONFIG_DIR__)) {

    if (!isset($SQL)) {
        require_once __GX_DATABASE_DIR__;
        $SQL = SQL();
    }

    require_once __GX_PREPROCESSING_DIR__;

    if (!isset($_SESSION["__GX_AlistUrl_DIR__"])) {
        AlistDataPreloading($SQL);
    }

    if (isset($_SESSION["__GX_OriginalBigClass_DIR__"])) {
        AlistToken();
        MainClassification();

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // 获取 URL 的路径部分
        if (isset($path)) {
            $impasse = basename($path);

            require_once __GX_PUBLIC_HEAD_DIR__;

            if ($impasse == "") {
                // 文件存在，进入主页
                PublicHead($impasse);
                require_once __GX_HOME_DIR__;
            }

            if ($impasse == "video") {
                // 携带参数加上预定路径，进入视频详情与播放页面
                PublicHead($impasse);
                require_once __GX_VIDEO_DIR__;
            }
        }
    }

} else {
    // 文件不存在，重定向到安装页面
    header("Location: install.php");
    exit; // 确保重定向后立即退出脚本
}

// 输出结束HTML标签
echo "</html>";

// 获取真实的访问者IP地址，考虑代理服务器的情况
$ip = !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];

$endTime = microtime(true);
$executionTimeInMilliseconds = round(($endTime - $startTime) * 1000, 2); // 将秒转换为毫秒并四舍五入到4位小数

// 输出页面底部信息
echo "<div style='margin: 10vh;padding: 10vh;text-align: center;'>\n";
echo "        <h3>本次运行时间：$executionTimeInMilliseconds 毫秒</h3>\n";
echo "<br><br>\n";
echo "        <h4>您的访问IP：$ip</h4>\n";
echo "</div>\n";
