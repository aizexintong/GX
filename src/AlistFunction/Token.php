<?php
require_once __GX_2FA_DIR__;
/**
 * @return array|void|null
 */
function getApiToken()
{
    $totp = null;

    if (!empty($_SESSION["__GX_Alist2FACode_DIR__"])) {
        $ga = new PHPGangsta_GoogleAuthenticator();
        $totp = $ga->getCode($_SESSION["__GX_Alist2FACode_DIR__"]);
    }

    $otpCode = ($totp !== null) ? $totp : null;

    $curl = curl_init();

    $requestData = [
        "username" => $_SESSION["__GX_AlistUsername_DIR__"],
        "password" => $_SESSION["__GX_AlistPassword_DIR__"],
        "otp_code" => $otpCode
    ];

    $url = $_SESSION["__GX_AlistUrl_DIR__"] . "/api/auth/login";

    $headers = [
        "Content-Type: application/json"
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => null,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($requestData),
        CURLOPT_HTTPHEADER => $headers
    ]);

    $response = curl_exec($curl);
    if ($response === false) {
        die('Curl error: ' . curl_error($curl));
    }

    $decodedResponse = json_decode($response, true);

    curl_close($curl);

    if (isset($decodedResponse['data']['token'])) {
        return $decodedResponse['data']['token'];
    }

    return null;
}