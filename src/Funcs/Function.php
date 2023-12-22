<?php
// 加密函数
/**
 * @param $data // 要解密的文本
 * @param $key //一开始定义的密文
 * @return string
 */
function CryptographicDatas($data, $key): string
{
    try {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 256, $iv);

        // 使用Base64编码，并替换特殊字符
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($iv . $encrypted));
    } catch (Exception $e) {
        error_log('Encryption error: ' . $e->getMessage());
        return '';
    }
}


// 解密函数
/**
 * @param $data //要解密的文本
 * @param $key //一开始定义的密文
 * @return string
 */
function DeclassifiedDatas($data, $key): string
{
    // 还原Base64编码，替换回特殊字符
    $data = base64_decode(str_replace(['-', '_'], ['+', '/'], $data));

    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);

    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 256, $iv);
}


// 自定义解析规则,暂时无用
/**
 * @param $text //符合条件的转换成文本的数组列表
 * @return array //返回出数组列表
 */
function restoreObject($text): array
{
    $datas = [];

    // 移除不必要的文本
    $text = preg_replace('/\n+/i', '', $text);
    $text = str_replace('array \(  ', '', $text);
    $text = str_replace('  \),  ', "\n", $text);

    // 将处理后的字符串按换行符拆分成数组
    $lines = explode("\n", $text);

    foreach ($lines as $line) {
        preg_match("/(?<='name' => ')(.*?)(?=')/", $line, $name);
        preg_match("/(?<='type' => )(\d+)/", $line, $type);
        preg_match("/(?<='sign' => ')(.*?)(?=')/", $line, $sign);

        $datas[] = [
            'name' => $name[1] ?? '',
            'type' => $type[1] ?? '',
            'sign' => $sign[1] ?? '',
        ];
    }

    return $datas;
}

/**
 * @param $folderPath // 给一个路径检查是否存在，不存在则递归建立
 * @param $path // 云端储存路径地址
 * @return void
 */
function createFolder($folderPath, $path = null): void
{
    $file = pathinfo($folderPath, PATHINFO_DIRNAME);
    if (!file_exists($file)) {
        // 创建目录，第三个参数表示递归创建子目录
        mkdir($file, 0777, true);
    }
    if (($_SESSION["__GX_IntroducePrestore_DIR__"] == "True" || $_SESSION["__GX_PicturePrestore_DIR__"] == "True") && $path != null) {
        require_once __GX_ALIST_DIR__ . "/Get.php";
        $img = FetchDirectoryOrFileMessage($path);
        if ($img[0] != -1) {
            $fileContents = file_get_contents($img["raw_url"]);
            file_put_contents($folderPath, $fileContents);
        }
    }
}