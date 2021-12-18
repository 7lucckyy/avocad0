<?php
    include_once 'JwtHandler.php';

    function GenerateResponseMessage($success,$status,$message,$extra = []){
        return json_encode(array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ],$extra));
    }
    function GenerateUUID()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    function GetAuthorizationHeader($headers)
    {
        return $headers['Authorization'] != null ? $headers['Authorization'] : false;
    }
    function DecodeAuthenticationToken($authToken)
    {
        $token = explode(" ",$authToken);
        $jwt = new JwtHandler();
        $token = $jwt->_jwt_decode_data($token[1]);
        if (isset($token['data']) == true && isset($token['data']->id) == true) {
            return $token['data'];
        }
        return false;
    }