<?php
/**
 * 定义 根 目录
 */
define("__GX_ROOT_DIR__", dirname(__FILE__));

/**
 * 定义 引用文件 目录
 */
const __GX_INC_DIR__ = __GX_ROOT_DIR__ . "/inc";

// composer包管理的调用指向
const __GX_AUTOLOAD_DIR__ = __GX_INC_DIR__ . "/vendor/autoload.php";

// 定义 F2A解码库 文件地址（开源地址：https://github.com/PHPGangsta/GoogleAuthenticator）
const __GX_2FA_DIR__ = __GX_INC_DIR__ . "/PHPGangsta/GoogleAuthenticator.php";


/**
 * 定义 核心文件 目录
 */
const __GX_SRC_DIR__ = __GX_ROOT_DIR__ . "/src";

/**
 * 定义 Alist使用函数 目录
 */
const __GX_ALIST_DIR__ = __GX_SRC_DIR__ . "/AlistFunction";

/**
 * 定义 前端模块 目录
 */
const __GX_COMP_S_DIR__ = __GX_ROOT_DIR__ . "/src/Comps";

// 定义 主页 文件地址
const __GX_HOME_DIR__ = __GX_COMP_S_DIR__ . "/Home.php";

// 定义 视频播放 文件地址
const __GX_VIDEO_DIR__ = __GX_COMP_S_DIR__ . "/Video.php";

// 定义 公共头部 文件地址
const __GX_PUBLIC_HEAD_DIR__ = __GX_COMP_S_DIR__ . "/PublicHead.php";

/**
 *  定义 函数存储 目录
*/
const __GX_FUNC_S_DIR__ = __GX_SRC_DIR__ ."/Funcs";

// 定义 数据库操作 文件地址（由作者给出 "可进行自由扩展"）
const __GX_DATABASE_DIR__ = __GX_FUNC_S_DIR__ . "/Database.php";

// 定义 函数库 文件地址（由作者给出 "可进行自由扩展"）
const __GX_FUNCTION_DIR__ = __GX_FUNC_S_DIR__ . "/Function.php";

// 定义 预处理操作 文件地址（由作者给出 "可进行自由扩展"）
const __GX_PREPROCESSING_DIR__ = __GX_FUNC_S_DIR__ . "/Preprocessing.php";


/**
 * 定义 数据文件 目录
 */
const __GX_DATA_DIR__ = __GX_ROOT_DIR__ . "/data";

// 定义 临时文件 地址
const __GX_TMP_DIR__ = __GX_DATA_DIR__ . "/tmp";

// 定义 数据库信息 文件地址
const __GX_CONFIG_DIR__ = __GX_DATA_DIR__ . "/config.ini";
