<?php
class EasytobookHTTP extends Easytobook {

    protected static function post($body){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, parent::api_url());
        curl_setopt($ch, CURLOPT_USERAGENT, "EasytobookPHPClient v1.0");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "request=".urlencode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

?>