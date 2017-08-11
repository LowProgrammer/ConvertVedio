<?php 
//include DIRNAME(DIRNAME(__FILE__))."/src/FFmpeg.php";
//引用ffmpeg类库
include DIRNAME(DIRNAME(__FILE__)).'/src/FFmpeg.php';
$db_servername="114.215.157.228:3306";//数据库地址
$db_username="root";//数据库账户
$db_password="457589";//数据库密码
//'C:\Program Files (x86)\php\WWW\zhiwangWeb\uploads\txt'
$source_vedio_path='C:/feifei/php/WWW/zhiwangWeb/uploads/txt';//视频存放地址
$db_save_path="C:/feifei/php/WWW/zhiwangWeb/./uploads/txt";
$source_ffmpeg="C:\ffmpeg\bin\ffmpeg";//ffmpeg安装地址
$vedio_type=["avi","wmv","mpeg","rm","asf","mov","rmvb","f4v","mpeg","bhd","vob","m1v"];//要转码的视频格式
//实例化
$FFmpeg = new FFmpeg($source_ffmpe);

$con=mysql_connect($db_servername,$db_username,$db_password);
if($con){
	echo 'success';
	echo '<br />';
}
else{
	echo 'fail';
}
mysql_select_db('cnki',$con);
$select_word="SELECT * FROM zw_source where source_vedio_convert=0";
$result=mysql_query($select_word);
//var_dump($result);

while($row=mysql_fetch_array($result)){
	$in_or_not=in_array($row['source_subfix'], $vedio_type);
	if($in_or_not){
		//视频存放地址
		$source_url_array=explode("/", $row['source_url']);
		//var_dump($source_url_array);
		$source_path=array_slice($source_url_array, count($source_url_array)-2);
		$vedio_url=$source_vedio_path.'/'.implode('/', $source_path);
		//echo $vedio_url.'<br>';
		//要转化的地址
		$convert_vedio_url=explode('.', $vedio_url);
		$convert_url=$convert_vedio_url[0].'.flv';
		//echo $convert_url.'<br>';
		
		$FFmpeg->input($vedio_url)->forceFormat('flv')->output($convert_url)->ready();
		$vedio_file_arr=explode('/', $convert_url);
		$vedio_file_path=array_slice($vedio_file_arr, count($vedio_file_arr)-2);

		$db_vedio_path=$db_save_path.'/'.implode('/', $vedio_file_path);
		//echo $db_vedio_path;
		 $updata_word="UPDATE zw_source SET source_url=".$convert_url.'AND source_vedio_convert=1 where source_id='.$row['source_id'];
		 mysql_query($updata_word);
		
		echo "success";
	}
	
	
}



mysql_close($con);
 
?> 