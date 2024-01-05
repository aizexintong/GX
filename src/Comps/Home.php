<?php

/**
 * 根据当前日期的某个属性对日期列表进行重新排序
 *
 * @param array $dateList 待排序的日期列表
 * @return array 排序后的日期列表
 * @noinspection PhpSwitchCanBeReplacedWithMatchExpressionInspection
 */
function timeFirstSort(array $dateList): array
{
    $currentDate = new DateTime('now');
    $sortOption = $_SESSION['__GX_TimeFirstSort_DIR__'] ?? "未知";

    // 获取当前日期排序
    switch ($sortOption) {
        case "季度":
            $now = ceil($currentDate->format('n') / 3);
            break;
        case "月份":
            $now = $currentDate->format('n');
            break;
        case "星期":
            $now = $currentDate->format('N');
            break;
        default:
            $now = null;
            break;
    }

    return reorderDateList($dateList, $now);
}


/**
 * 根据给定的索引值对日期列表进行重新排序
 *
 * @param array $dateList 待排序的日期列表
 * @param int|null $index 索引值
 * @return array 排序后的日期列表
 */
function reorderDateList(array $dateList, ?int $index): array
{
    if ($index !== null) {
        $slice1 = array_slice($dateList, $index - 1);
        $slice2 = array_slice($dateList, 0, $index - 1);
        return array_merge($slice1, $slice2);
    } else {
        return $dateList;
    }
}

/**
 * MenuModule函数用于生成菜单模块
 * @param int|null $n - 一个可选的参数，默认为null
 *     $n: 用于确定菜单模块的显示方式
 */
function MenuModule(?int $n = -1): void
{
    switch ($n) {
        case -1:
            // 当$n为null时，生成一个内容列表
            echo '<div class="content_list">' . "\n";
            foreach ($_SESSION["__GX_BigList_DIR__"] as $TitleList) {
                // 为每个内容生成对应的链接
                $url = '?BigList=' . urlencode("/" . $TitleList);
                echo '<a class="content_list_a" href="' . $url . '">' . "\n";
                echo '<p style="padding: 1vh;">' . $TitleList . '</p>' . "\n";
                echo '</a>' . "\n";
            }
            echo '</div>' . "\n";
            break;
        default:
            // 当$n不为null时，提示需要自定义样式
            echo "可以自己添加设置特定样式";
            break;
    }
}

function MainMenu($DateList): void
{
    echo "
    <div style='margin: 4%; display: flex; font-size: 200%; justify-content: center; font-weight: bold;'>$DateList 分类</div>
    ";
}

/**
 * 定义一个名为 MainModuleStyle 的函数
 * @return string - 返回一个包含样式信息的字符串
 */
function MainModuleStyle(): string
{
    // 初始化输出字符串
    $output = 'class="content_list_content" ';

    // 根据参数 $n 的值进行不同的样式设置
    switch (intval($_SESSION["__GX_MainModuleDisplay_DIR__"])) {
        case -1:
            // 设置符合文字的布局样式
            $output .= 'style="text-decoration: none; color: inherit;"';
            break;
        case 0 or 1 or 2:
            // 设置符合显示图片的布局样式
            $output .= 'style="align-content: center; justify-content: space-between; text-decoration: none; color: inherit;"';
            break;
        default:
            echo "可以自己添加设置特定样式，与 MainModule 函数对应";
            break;
    }

    // 返回最终样式信息的字符串
    return $output;
}

function MainModule($list, $path): void
{

    // 根据给定的条件进行选择
    switch (intval($_SESSION["__GX_MainModuleDisplay_DIR__"])) {
        case -1:
            // 单纯文字显示
            echo '
                    <div class="parent-container">
                        <div class="scrollable-container-special">' . $list . '</div>
                    </div>
                ';
            break;
        case 0:
            // 使用预设图片显示
            echo '
                    <div class="content_content_item">
                        <img src="/data/images/img.png" alt = "' . $list . '" title = "' . $list . '" width ="100%">
                    </div>
                    <div class="parent-container">
                        <div class="scrollable-container">' . $list . '</div>
                    </div>
                ';
            break;
        case 1:
            // 使用储存图片显示
            require_once __GX_ALIST_DIR__ . "/Get.php";
            $paths = $path . "/" . $_SESSION["__GX_Picture_DIR__"];
            $img = FetchDirectoryOrFileMessage($paths);
            echo '
                    <div class="content_content_item">
                        <img src="' . $img['raw_url'] . '" alt = "' . $list . '" title = "' . $list . '" width ="100%">
                    </div>
                    <div class="parent-container">
                        <div class="scrollable-container">' . $list . '</div>
                    </div>
                ';
            break;
        case 2:
            // 使用先进行预储存到服务器然后从服务器调用资源,记录，后期添加
            $img = PicturePreinstall($path);
            echo '
                    <div class="content_content_item">
                        <img src="' . $img . '" alt = "' . $list . '" title = "' . $list . '" width ="100%">
                    </div>
                    <div class="parent-container">
                        <div class="scrollable-container">' . $list . '</div>
                    </div>
                ';
            break;
        default:
            echo "可以自己添加设置特定样式，与 MainModuleStyle 函数对应";
            break;
    }
}

function Main($lists, $path): void
{
    // 检查 $lists ,为-1则是没有信息存在
    if ($lists[0] !== -1) {
        // 遍历 $lists 数组中的每个元素，并将每个元素赋值给 $list 变量
        foreach ($lists as $list) {
            // 构建路径，使用 CryptographicDatas 函数对路径进行加密
            require_once __GX_FUNCTION_DIR__;

            $PathEncryption = CryptographicDatas($path . '/' . $list, $_SESSION["__GX_Key_DIR__"]);

            // 获取样式，使用 MainModuleStyle 函数
            $style = MainModuleStyle();

            // 输出一个链接，链接指向 /video/?parameter= 加上加密后的路径 $path，附带样式 $style
            echo '<a href="/video/?parameter=' . $PathEncryption . '&name=' . $list . '"' . $style . '>';

            // 调用 MainModule 函数，并传递参数 $list, $bigListValue, $DateList
            MainModule($list, $path . '/' . $list);

            // 输出链接闭合标签
            echo '</a>';
        }
    }
}

/**
 * 对主页信息进行预处理
 */
function PreprocessingHomeInformation(): void
{
    // 从GET请求中获取BigList参数，若不存在则使用默认值
    $bigListValue = $_GET['BigList'] ?? "/" . $_SESSION["__GX_BigList_DIR__"][0];

    // 列表获取定义文件
    require_once __GX_ALIST_DIR__ . "/List.php";

    // 获取指定大分类下的二次分类列表
    $DateLists = categorizeLittleList($bigListValue, intval($_SESSION["__GX_OriginalBigClass_DIR__"]));

    // 对日期列表按时间先后排序，并遍历处理
    foreach (TimeFirstSort($DateLists) as $index => $DateList) {

        // 获取二次分类下的列表
        $path = $bigListValue . "/" . $DateList;
        $lists = contentsList($path, intval($_SESSION["__GX_UltimatelyLen_DIR__"]));

        echo '
    <div class="content content_1" ' . ($index === 0 ? 'id="Begin_column"' : '') . '>
        <div class="content_div_1 content_div_1_merge" onclick="toggleContentDiv2(this)">
            <h2 class="content_title">' . $DateList . '</h2>
            <h6 class="content_text">点击打开' . $DateList . '列表</h6>
            <div class="content_content_title" style="display: none;">';

        // 调用MainMenu函数
        MainMenu($DateList);

        echo '
            </div>
        </div>
        <div class="content_div_2" style="display: none;">
            <div class="content_content_text" style="display: none;">
                <div class="content_content">';

        // 调用Main函数
        Main($lists, $path);

        echo '
                </div>
            </div>
        </div>
    </div>';
    }
}

echo "<body>";

require "HomeHeader.php";

MenuModule();

PreprocessingHomeInformation();

echo "<script>expandFirstElement('{$_SESSION['__GX_expandFirstElement_DIR__']}')</script></body>";