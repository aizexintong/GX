<?php
function isMobile(): bool
{
    return (bool)preg_match('/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i', $_SERVER['HTTP_USER_AGENT']);
}

function PublicHead($path): void
{
    /** @noinspection HtmlRequiredTitleElement */
    echo '<head>';
    echo '<meta charset="UTF-8">';
    if ($path == "" || $path == "index.php" || $path == "index") {
        echo '<title> 故乡的净土</title>';
        if (isMobile()) {
            // 如果是移动设备
            echo '<link rel="stylesheet" type="text/css" href="/data/css/PE/home_pe.css"/>' . "\n";
        } else {
            // 如果是桌面设备
            echo '<link rel="stylesheet" type="text/css" href="/data/css/PC/home_pc.css"/>' . "\n";
        }
    } elseif ($path == "video") {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>' . $_GET["name"] . '</title>';
        if (isMobile()) {
            // 如果是移动设备
            echo '<link rel="stylesheet" type="text/css" href="/data/css/PE/video_pe.css"/>' . "\n";
        } else {
            // 如果是桌面设备
            echo '<link rel="stylesheet" type="text/css" href="/data/css/PC/video_pc.css"/>' . "\n";
        }
    } else {
        echo '<title></title >';
    }
    echo '<script src="/data/js/share.js"></script>' . "\n";
    echo '</head>';
}

