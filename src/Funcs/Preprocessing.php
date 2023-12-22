<?php
/**
 * @param $SQL
 * @return void
 */
function AlistDataPreloading($SQL): void
{
    $DirtyData = $SQL->query("SELECT * FROM config");
    foreach ($DirtyData as $i) {
        $_SESSION["__GX_" . $i["name"] . "_DIR__"] = $i["value"];
    }
}

function AlistToken(): void
{
    // 导入必要文件
    require_once __GX_ALIST_DIR__ . "/Token.php";
    // 检查是否存在 $_SESSION['time']，如果不存在或者已经过期，则更新 token 和时间戳
    if (!isset($_SESSION['time']) || $_SESSION['time'] < time()) {
        $_SESSION['token'] = getApiToken();
        $_SESSION['time'] = time() + intval($_SESSION["__GX_AlistFrequently_DIR__"]) ?? 8 * 60 * 60;
    }
}

function MainClassification(): void
{
    if (!isset($_SESSION["__GX_BigList_DIR__"])) {
        require_once __GX_ALIST_DIR__ . "/List.php";

        $DirtyData = array_reverse(categorizeBigList(intval($_SESSION["__GX_OriginalBigClass_DIR__"])));

        // 定义要删除的不需要的元素
        $unwantedElements = explode(',', $_SESSION["__GX_UnwantedElements_DIR__"]);

        // 使用 array_diff() 函数获取两个数组的差集
        $cleanedArray = array_diff($DirtyData, $unwantedElements);

        // 重新索引数组键值，可以使用 array_values()
        $cleanedArray = array_values($cleanedArray);

        // 将常量标记为已定义
        $_SESSION["__GX_BigList_DIR__"] = $cleanedArray;
    }
}

function IntroducePreinstall($path): string
{
    if ($_SESSION["__GX_IntroducePrestore_DIR__"] == "True") {
        $Introduce = __GX_TMP_DIR__ . $path . "/" . $_SESSION["__GX_Introduce_DIR__"];
        if (!file_exists($Introduce)) {
            require_once __GX_FUNCTION_DIR__;
            createFolder($Introduce . "/", $path . "/" . $_SESSION["__GX_Introduce_DIR__"]);
        }
        return strstr($Introduce, '/data/tmp');
    } else {
        require_once __GX_ALIST_DIR__ . "/Get.php";
        $path = $path . "/" . $_SESSION["__GX_Introduce_DIR__"];
        $rra = FetchDirectoryOrFileMessage($path);
        if ($rra[0] == -1) {
            return "";
        } else {
            return $rra['raw_url'];
        }
    }
}

function PicturePreinstall($path): string
{
    if ($_SESSION["__GX_PicturePrestore_DIR__"] == "True") {
        $Picture = __GX_TMP_DIR__ . $path . "/" . $_SESSION["__GX_Picture_DIR__"];
        if (!file_exists($Picture)) {
            require_once __GX_FUNCTION_DIR__;
            createFolder($Picture, $path . "/" . $_SESSION["__GX_Picture_DIR__"]);
        }
        return strstr($Picture, '/data/tmp');
    } else {
        require_once __GX_ALIST_DIR__ . "/Get.php";
        $path = $path . "/" . $_SESSION["__GX_Picture_DIR__"];
        $rra = FetchDirectoryOrFileMessage($path);
        if ($rra[0] == -1) {
            return "";
        } else {
            return $rra['raw_url'];
        }
    }
}