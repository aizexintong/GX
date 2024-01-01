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

        // 循环检查并删除在 $DirtyData 中元素包含不需要的元素内容的情况
        foreach ($DirtyData as $index => $data) {
            foreach ($unwantedElements as $unwanted) {
                // 如果当前元素包含了不需要的内容，则删除该元素
                if (strpos($data, $unwanted) !== false) {
                    unset($DirtyData[$index]);
                    break; // 跳出内部循环，继续下一个元素
                }
            }
        }

        // 重新索引数组键值，可以使用 array_values()
        $cleanedArray = array_values($DirtyData);

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