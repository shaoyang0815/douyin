<?php
$_GET['url'] = 'https://v.douyin.com/Tj7Uk5/';
if (!empty($_GET['url'])) {
    $url = $_GET['url'];
    $str = curl($url, 1);
	$itemId = getSubstr($str, "itemId: \"", "\",");
	$dytk = getSubstr($str, "dytk: \"", "\" });");
	if(!empty($itemId) && !empty($dytk)) {
		$data = json_decode(curl("https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=".$itemId."&dytk=".$dytk, 0),true);
		echo $data['item_list'][0]['desc'];//标题
		echo '<pre>';
		echo $data['item_list'][0]['statistics']['digg_count'];//赞
		echo '<pre>';
		echo $data['item_list'][0]['statistics']['comment_count'];//评论
		echo '<pre>';
		$video_id = $data['item_list'][0]['video']['play_addr']['uri'];
        $str = curl("https://aweme.snssdk.com/aweme/v1/play/?video_id=".$video_id."&line=0", 0);
        preg_match('#<a href="(.*?)">#', $str, $data2);
        if (count($data2) >= 1) {
            $arr3 = explode("//", $data2[1]);
            if (!empty($arr3)) {
				echo "https://".$arr3[1];//去水印链接
				//header("Location: "."https://".$arr3[1]);
            }else{
				echo '解析失败1......';
			}
        }else{
			echo '解析失败2......';
		}
    }else{
		echo '解析失败3......';
	}
 
} else {
    echo "请输入链接......";
}
 
function curl($url, $foll = 0) {
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $foll);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
	
}

function getSubstr($str, $leftStr, $rightStr)
{
	$left = strpos($str, $leftStr);
	$right = strpos($str, $rightStr, $left);
	if ($left < 0 or $right < $left) return '';
	return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
}
?>