<?php
/**
 * @param $path //文件夹或者文件位置
 * @return array
 */
function FetchDirectoryOrFileMessage($path):array
{
    $curl = curl_init();

    // 使用关联数组为 CURL-OPT_HTTP HEADER 提供更好的可读性
    $headers = [
        "Content-Type: application/json",
        "Authorization: " . $_SESSION['token'],
    ];

    $options = [
        CURLOPT_URL => $_SESSION["__GX_AlistUrl_DIR__"] . "/api/fs/get",
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

    if ($response === false) {
        return [-1];
    }

    try {
        $data = json_decode($response, true)['data'];

        if ($data === null) {
            return [-1];
        }

        // 检查数据是否为空，如果有一个字段为空，返回-1
        if (empty($data['name'])) {
            return [-1];
        }

        return [
            'name' => $data['name'],
            'type' => $data['type'],
            'raw_url' => $data['raw_url']
        ];

    } catch (Exception $e) {
        // 异常处理，你可以根据需要进行进一步处理
        die($e->getMessage());
    }
}