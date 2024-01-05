<?php
/**
 * @param $path //任何存在的路径
 * @return array //返回出此路径下所有需要发文件名称和文件性质
 */
function FetchDirectoryContents($path): array
{
    $curl = curl_init();

    // 使用关联数组为 CURL-OPT_HTTP HEADER 提供更好的可读性
    $headers = [
        "Content-Type: application/json",
        "Authorization: " . $_SESSION['token'],
    ];

    $options = [
        CURLOPT_URL => $_SESSION["__GX_AlistUrl_DIR__"] . "/api/fs/list",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode(["path" => $path]),
        CURLOPT_HTTPHEADER => $headers,
    ];

    curl_setopt_array($curl, $options);

    $response = curl_exec($curl);

    // 使用严格比较（===）检查 cURL 请求是否成功
    if ($response === false) {
        echo "与alist连接失败！";
    }
    try {

        // 解码响应，不将其关联为数组，以处理对象和数组响应
        $data = json_decode($response, true)['data'];

        $dirs_0 = [];

        // 检查 $data 是否存在且不为 null
        if (isset($data["content"]) && is_array($data["content"])) {
            foreach ($data["content"] as $item) {
                $dirs_0[] = [
                    'name' => $item["name"],
                    'type' => $item["type"],
                    'sign' => $item["sign"]
                ];
            }
        } else {
            return ["no"];
        }

        // 自定义排序顺序列表
        usort($dirs_0, function ($a, $b) {
            // 自定义文字类排序，不添加接口了，以后添加在 admin 页面
            $customOrder = ["春季", "夏季", "秋季", "冬季", "一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"];

            $nameA = $a['name'];
            $nameB = $b['name'];

            // 检查名称是否在自定义顺序数组中
            $indexA = array_search($nameA, $customOrder);
            $indexB = array_search($nameB, $customOrder);

            // 如果名称不在自定义顺序数组中，假设名称是数字，然后按升序处理
            if ($indexA === false) {
                $indexA = count($customOrder) + intval($nameA);
            }

            if ($indexB === false) {
                $indexB = count($customOrder) + intval($nameB);
            }

            // 返回比较结果，以便排序
            return $indexA - $indexB;
        });

        return $dirs_0;

    } catch (Exception $e) {
        // 异常处理，你可以根据需要进行进一步处理
        die($e->getMessage());
    }
}

// 递归获取最深路径的函数
/**
 * @param $path
 * @param $currentDepth //当前深度，没有特殊需求请请填写整数1
 * @param $maxDepth //最深深度，请填写整数
 * @return array
 */
function GetDeepestPaths($path, $currentDepth, $maxDepth): array
{
    $result = FetchDirectoryContents($path);

    if ($result[0] == "no") {
        return ["no"];
    } else {
        $deepestPath = [];

        // 只关注文件夹，不处理文件
        foreach ($result as $item) {
            if ($item['type'] == 1 && $currentDepth <= $maxDepth) {
                if ($currentDepth == $maxDepth) {
                    $deepestPath[] = $item['name'];
                } else {
                    // 递归调用获取子文件夹的最深路径
                    $subPaths = GetDeepestPaths($path . $item['name'] . "/", $currentDepth + 1, $maxDepth);
                    // 处理递归结果
                    if (!empty($subPaths)) {
                        if ($subPaths[0] != "no") {
                            foreach ($subPaths as $subPath) {
                                $deepestPath[] = $item['name'] . "/" . $subPath;
                            }
                        } else {
                            // 处理没有更深层次的情况
                            $deepestPath[] = $item['name'];
                        }
                    } else {
                        // 处理空数组的情况
                        $deepestPath[] = $item['name'];
                    }
                }
            }
        }
    }

    return $deepestPath;
}