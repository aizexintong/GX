<?php
require __GX_SRC_DIR__ . '/AlistFunction/Dirs.php';

/**
 * @param $OriginalBigClass // 大分类确认的深度
 * @return array
 */
function categorizeBigList($OriginalBigClass): array
{
    $Lists = [];
    $BigClass = $OriginalBigClass == 0 ? 1 : $OriginalBigClass;
    $BigLists = GetDeepestPaths("/", 1, $BigClass);
    foreach ($BigLists as $BigList) {
        $Lists[] = $BigList;
    }
    return $Lists;
}

/**
 * @param $Path // 大分类的路径
 * @param $OriginalLittleClass // 小分类确认的深度
 * @return array
 */
function categorizeLittleList($Path, $OriginalLittleClass): array
{
    $Lists = [];
    $LittleClass = $OriginalLittleClass == 0 ? 1 : $OriginalLittleClass;
    $LittleLists = GetDeepestPaths("/" . $Path, 1, $LittleClass);
    foreach ($LittleLists as $LittleList) {
        $Lists[] = $LittleList;
    }
    return $Lists;
}


/**
 * @param $Path // 大分类加小分类所构成的路径
 * @param $UltimatelyLen // 最后距离内容文件夹深度
 * @return array
 */
function contentsList($Path, $UltimatelyLen): array
{
    $details = [];
    $lists = GetDeepestPaths("/" . $Path, 1, $UltimatelyLen);
    if ($lists[0] == "no") return [-1];
    foreach ($lists as $list) {
        $details[] = $list;
    }
    return $details;
}