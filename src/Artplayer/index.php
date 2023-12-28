<?php
session_start();
require_once "../../config.ini.php";
require_once __GX_FUNCTION_DIR__;

$video_url = DeclassifiedDatas($_GET['link'], $_SESSION["__GX_Key_DIR__"]);

$video_type = preg_replace('/\?sign=.*$/', '', $video_url);

preg_match('/\w+$/i', $video_type, $matches);
$video_type = $matches[0];

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>视频播放器</title>
    <!-- 引入 Artplayer 依赖 -->
    <script src="/src/Artplayer/include/flv.min.js"></script>
    <script src="/src/Artplayer/include/hls.min.js"></script>
    <script src="/src/Artplayer/include/dash.all.min.js"></script>
    <!-- 引入 Artplayer -->
    <script src="/src/Artplayer/include/artplayer.js"></script>
    <script src="/src/Artplayer/include/artplayer-plugin-danmuku.js"></script>
    <style>
        .video-player, html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;

        }

        html,
        body {
            overflow: hidden;
        }
    </style>
</head>
<body>
<div class="video-player"></div>
<script>
    function Danmuku() {
        // 读取数据库进行加载弹幕
        const Danmuku = [];
        Danmuku.push({
            text: '预设，功能未完全开放',
            time: 1,
            color: '#fff',
            border: false,
            mode: 0,
        })
        return Danmuku
    }

    function PluginDanmuku(art) {
        // 监听手动输入的弹幕，保存到数据库
        // art.on('artplayerPluginDanmuku:emit', (danmu) => {
        //     console.info('新增弹幕', danmu);
        // });

        // // 监听加载到的弹幕数组
        // art.on('artplayerPluginDanmuku:loaded', (danmus) => {
        // 	console.info('加载弹幕', danmus.length);
        // });

        // // 监听加载到弹幕的错误
        // art.on('artplayerPluginDanmuku:error', (error) => {
        // 	console.info('加载错误', error);
        // });

        // // 监听弹幕配置变化
        // art.on('artplayerPluginDanmuku:config', (option) => {
        // 	console.info('配置变化', option);
        // });

        // // 监听弹幕停止
        // art.on('artplayerPluginDanmuku:stop', () => {
        // 	console.info('弹幕停止');
        // });

        // // 监听弹幕开始
        // art.on('artplayerPluginDanmuku:start', () => {
        // 	console.info('弹幕开始');
        // });

        // // 监听弹幕隐藏
        // art.on('artplayerPluginDanmuku:hide', () => {
        // 	console.info('弹幕隐藏');
        // });

        // // 监听弹幕显示
        // art.on('artplayerPluginDanmuku:show', () => {
        // 	console.info('弹幕显示');
        // });

        // // 监听弹幕销毁
        // art.on('artplayerPluginDanmuku:destroy', () => {
        // 	console.info('弹幕销毁');
        // });
    }

    const video_url = '<?php echo $video_url; ?>';//需要变量储存
    const video_type = '<?php echo $video_type; ?>'; // 使用 [0] 获取整个匹配的字符串
    Artplayer.TOUCH_MOVE_RATIO = 1;
    Artplayer.CONTEXTMENU = false;
    Artplayer.NOTICE_TIME = 5000;
    Artplayer.INFO_LOOP_TIME = 200;
    Artplayer.PLAYBACK_RATE = [0.1, 0.2, 0.4, 0.8, 1, 2, 4, 8, 16];
    Artplayer.FAST_FORWARD_VALUE = 4;
    Artplayer.SETTING_WIDTH = 360;
    Artplayer.SETTING_ITEM_WIDTH = 360;
    Artplayer.SETTING_ITEM_HEIGHT = 42;
    Artplayer.RESIZE_TIME = 1000;
    Artplayer.SCROLL_TIME = 1000;
    Artplayer.USE_RAF = true;
    Artplayer.FULLSCREEN_WEB_IN_BODY = true;
    const art = new Artplayer({
        container: '.video-player',
        url: video_url,
        type: video_type,
        title: "故乡的净土",
        poster: '/src/artplayer/images/poster.png',
        lang: navigator.language.toLowerCase(),
        lock: true,
        airplay: true,
        volume: 0.5,
        fastForward: true,
        autoPlayback: true,
        autoOrientation: true,
        isLive: false,
        muted: false,
        autoplay: false,
        autoSize: false,
        autoMini: true,
        screenshot: true,
        loop: true,
        flip: true,
        playbackRate: true,
        aspectRatio: true,
        setting: true,
        hotkey: true,
        pip: true,
        mutex: true,
        backdrop: true,
        fullscreen: true,
        fullscreenWeb: false,
        subtitleOffset: true,
        miniProgressBar: true,
        playsInline: true,
        theme: '#23ade5',
        icons: {
            loading: '<img src="/src/Artplayer/images/ploading.gif" alt="视频加载显示">',
            state: '<img width="150" height="150" src="/src/Artplayer/images/state.svg" alt="视频暂停显示">',
            indicator: '<img width="16" height="16" src="/src/Artplayer/images/indicator.svg" alt="进度条图标">',
        },
        moreVideoAttr: {
            "webkit-playsinline": true,
            playsInline: true,
        },
        settings: [{
            width: 200,
            html: '字幕',
            tooltip: '语言',
            icon: '<img width="22"  src="/src/Artplayer/images/subtitle.svg" alt="字幕图标">',
            selector: [{
                html: '展示',
                tooltip: '显示',
                switch: true,
                onSwitch: function (item) {
                    item.tooltip = item.switch ? '隐藏' : '显示';
                    art.subtitle.show = !item.switch;
                    return !item.switch;
                },
            },
                {
                    default: true,
                    html: '双语或中文',
                    url: '',
                },
                {
                    html: '日语',
                    url: '',
                },
                {
                    html: '英文',
                    url: '',
                },
            ],
            onSelect: function (item) {
                art.subtitle.switch(item.url, {
                    name: item.html,
                });
                return item.html;
            },
        },
            {
                html: '滑动快慢进比值',
                icon: '<img width="22"  src="/src/Artplayer/images/state.svg" alt="快慢进图标">',
                tooltip: '0-10',
                range: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                onRange: function (item) {
                    return item.range + 'x';
                },
            },
        ],
        contextmenu: [{
            html: '自定义菜单',
            click: function (contextmenu) {
                console.info('您单击了自定义菜单');
                contextmenu.show = false;
            },
        },],
        layers: [{
            html: "故乡的净土",
            click: function () {
                window.open('https://gxdjt.org');
            },
            style: {
                position: 'absolute',
                "top": '1.128vh',
                "right": '2vw',
                "opacity": '.9',
                "color": "#eaeade",
                "font-size": "3vh",
                "font-weight": "900",
            },
        }],
        controls: [],
        plugins: [
            artplayerPluginDanmuku({
                danmuku: Danmuku(),
                speed: 5, // 弹幕持续时间，单位秒，范围在[1 ~ 10]
                opacity: 1, // 弹幕透明度，范围在[0 ~ 1]
                fontSize: 25, // 字体大小，支持数字和百分比
                color: '#FFFFFF', // 默认字体颜色
                mode: 0, // 默认模式，0-滚动，1-静止
                margin: [10, '25%'], // 弹幕上下边距，支持数字和百分比
                antiOverlap: true, // 是否防重叠
                useWorker: true, // 是否使用 web worker
                synchronousPlayback: true, // 是否同步到播放速度
                filter: (danmu) => danmu.text.length < 50, // 弹幕过滤函数，返回 true 则可以发送
                lockTime: 1, // 输入框锁定时间，单位秒，范围在[1 ~ 60]
                maxLength: 100, // 输入框最大可输入的字数，范围在[0 ~ 500]
                minWidth: 200, // 输入框最小宽度，范围在[0 ~ 500]，填 0 则为无限制
                maxWidth: 400, // 输入框最大宽度，范围在[0 ~ Infinity]，填 0 则为 100% 宽度
                theme: 'dark', // 输入框自定义挂载时的主题色，默认为 dark，可以选填亮色 light
                heatmap: true, // 是否开启弹幕热度图, 默认为 false
                beforeEmit: (danmu) => !!danmu.text.trim(), // 发送弹幕前的自定义校验，返回 true 则可以发送
                // 其他配置项...
            })
        ],
        whitelist: [],
        customType: {

            flv: function playFlv(video, url, art) {
                if (flvjs.isSupported()) {
                    if (art.flv) art.flv.destroy();
                    const flv = flvjs.createPlayer({
                        type: 'flv',
                        url
                    });
                    flv.attachMediaElement(video);
                    flv.load();
                    art.flv = flv;
                    art.on('destroy', () => flv.destroy());
                } else {
                    art.notice.show = '格式不支持';
                }
            },

            m3u8: function playM3u8(video, url, art) {
                if (Hls.isSupported()) {
                    if (art.hls) art.hls.destroy(); // 注意这里使用 art.hls
                    const hls = new Hls();
                    hls.loadSource(url);
                    hls.attachMedia(video);
                    art.hls = hls;
                    art.on('destroy', () => hls.destroy());
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = url;
                } else {
                    art.notice.show = '格式不支持';
                }
            },

            mpd: function playMpd(video, url, art) {
                if (dashjs.supportsMediaSource()) {
                    if (art.dash) art.dash.destroy();
                    const dash = dashjs.MediaPlayer().create();
                    dash.initialize(video, url, art.option.autoplay);
                    art.dash = dash;
                    art.on('destroy', () => dash.destroy());
                } else {
                    art.notice.show = '格式不支持';
                }
            }
        }
    });
    PluginDanmuku(art);
</script>
</body>
</html>