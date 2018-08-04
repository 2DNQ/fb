<?php
set_time_limit(0);
error_reporting(0);
define('HASHTAG_NAMESPACE', '#pubgm');
$token = 'EAAAAUaZA8jlABAK5ZB4BIM4IuSJCRURQRbLZBjkXSAq6ZCoXqU5bnJxIZBSE7FQnq4CxkELj86URr63ZCjfByS0psoJZCcuFdcBuDNjDXDJElbCdI9Hb1EBC5Oz5TMUNKOUMD0uuxufSj8WAWvlZAKOJtAl1smFlYIQZD'; 
$idgroup = '366003400476723'; /* Id Group */
$post = json_decode(request('https://graph.facebook.com/v2.9/' .$idgroup. '/feed?fields=id,message,created_time,from&limit=100&access_token=' . $token), true); /* Get Data Post*/
$timelocpost = date('Y-m-d');
$logpost     = file_get_contents("log.txt");
for ($i = 0; $i < 100; $i++) {
    $idpost      = $post['data'][$i]['id'];
    $messagepost = $post['data'][$i]['message'];
    $time        = $post['data'][$i]['created_time'];
    /* Check time Post */
    if (strpos($time, $timelocpost) !== false) {
        /* Check hashtag */
        if (strpos(strtolower($messagepost), HASHTAG_NAMESPACE) === FALSE) {
            /* Check trùng  */
            if (strpos($logpost, $idpost) === FALSE) {
                /* Send Comment  */
                $comment = 'Bổ sung #hashtag vào bài viết nhé, ' . $post['data'][$i]['from']['name'] . '!' . "\n\n" . 'https://goo.gl/RsJbAj';
                request('https://graph.facebook.com/' . urlencode($idpost) . '/comments?method=post&message=' . urlencode($comment) . '&access_token=' . $token);
                $luulog = fopen("log.txt", "a");
                fwrite($luulog, $idpost . "\n");
                fclose($luulog);
            } else {
                echo 'Đã nhắc hashtag';
            }
        }
        
    }
}
exec("php test.php"); /* Chạy lại file  */
function request($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return FALSE;
    }
    
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HEADER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_ENCODING => '',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
        CURLOPT_AUTOREFERER => TRUE,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 0
    );
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response  = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    unset($options);
    return $http_code === 200 ? $response : FALSE;
}
?>