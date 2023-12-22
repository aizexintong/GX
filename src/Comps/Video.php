<?php

function Data($path): array
{
    require_once __GX_ALIST_DIR__ . "/Dirs.php";
    $datas = FetchDirectoryContents($path);
    $name_data = [];
    foreach ($datas as $data) {
        if ($data["type"] == "2") {
            $name_data[] = [
                $data["name"],
                $data["sign"]
            ];
        }
    }
    return $name_data;
}

function Iframe($path, $name_data): string
{
    require_once __GX_FUNCTION_DIR__;
    if (isset($_GET['_sign'])) {
        if ($_GET['_sign'] == "#") {
            $video_url = $_SESSION["__GX_AlistSharePrefix_DIR__"] . $path . "/" . $_GET['_name'];
        } else {
            $video_url = $_SESSION["__GX_AlistSharePrefix_DIR__"] . $path . "/" . $_GET['_name'] . "?sign=" . $_GET['_sign'];
        }
    } else {
        $video_url = $_SESSION["__GX_AlistSharePrefix_DIR__"] . $path . "/" . $name_data[0][0] . "?sign=" . $name_data[0][1];
    }
    return CryptographicDatas($video_url, $_SESSION["__GX_Key_DIR__"]);
}

function Catalogue($name_data): void
{
    $i = 0;
    foreach ($name_data as $data) {
        echo "<a href='?parameter=" . $_GET['parameter'] . "&_name=" . $data[0] . "&_sign=" . ($data[1] ?? "#") . "&name=" . $_GET['name'] . "'>第" . sprintf('%02d', ++$i) . "集</a>";
    }
}

function Introduce($path): void
{
    require_once __GX_FUNCTION_DIR__;

    $path = IntroducePreinstall($path);

    if (file_exists($path)) {
        // 原始文本
        $text = file_get_contents($path);

        // 将每一行用 <p> 标签包裹
        $text_with_p_tags = "<p>" . str_replace("\n", "</p><p>", $text) . "</p>";

        echo $text_with_p_tags;
    } else {
        echo "<p>暂无</p>";
    }
}

function Main(): void
{
    require_once __GX_FUNCTION_DIR__;
    $path = DeclassifiedDatas($_GET['parameter'], $_SESSION["__GX_Key_DIR__"]);
    $name_data = Data($path);
    echo '
    <body>
    <div class="container">
        <div class="video-section">
            <iframe src="/src/Artplayer/index.php?link=' . Iframe($path, $name_data) . '"></iframe>
                    <p></p>
                    <div class="episode-info">';
    Catalogue($name_data);
    echo '          </div>
                </div>
                <h2>' . $_GET["name"] . ' 视频简介:</h2>';
    Introduce($path);
    echo '  <h2>相关推荐</h2>
            <div class="related-videos">
            <p>未开放，功能正在添加中</p>
<!--            <div class="related-video">
                    <img src="/data/images/img.png" alt="案例" title="案例">
                    <p>案例</p>
                </div>-->             
            </div>
        </div>
        </body>
        ';
}


Main();