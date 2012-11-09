<?php
//namespace gen_lib;
//include define.
define("__GEN_LIB_PHP__","1");


//Path helper functions.
function ConfirmLastPathBreaker($path)
{
 $str_len=strlen($path);
 if($str_len>0){
  $last_char=substr($path,$str_len-1);
  if($last_char!="\\" && $last_char!="/")
   $path.="/";
 }
 return $path;
}


//System functions.
function System_GetAccurateTime()
{
 $mtime = microtime();
 $mtime = explode(" ",$mtime);
 $mtime = $mtime[1] + $mtime[0];
 
 return $mtime;
}

//Parameter functions.
function IsGETnPOSTParamAvailable($ParamName)
{
 return (isset($_GET[$ParamName]) || isset($_POST[$ParamName]));
}

function ReadGETnPOSTParam($ParamName,$def_val)
{
 if(isset($_GET[$ParamName]))
  return $_GET[$ParamName];
 if(isset($_POST[$ParamName]))
  return $_POST[$ParamName];

 return $def_val;
}
    
    
    //======File Function=======
    function file_noCRLF($fn)
    {
        $lines=file($fn);
        for($i=0;$i<sizeof($lines);$i++){
            $lines[$i]=trim($lines[$i],"\r\n");
        }
        return $lines;
    }
    
    function File_CompareDate($file1,$file2)
    {
        if(!file_exists($file1) || !file_exists($file2)){
            return 0;
        }
        $date1=filectime($file1);
        $date2=filectime($file2);
        
        return ($date1-$date2);
    }
    
    function deleteDir($dir)
    {
        if(substr($dir, strlen($dir)-1, 1)!='/')
            $dir .= '/';
        
        if($handle=opendir($dir)){
            while($obj=readdir($handle)){
                if($obj!='.' && $obj!='..'){
                    if(is_dir($dir.$obj)){
                        if(!deleteDir($dir.$obj))
                            return false;
                    }elseif(is_file($dir.$obj)){
                        if(!unlink($dir.$obj))
                            return false;
                    }
                }
            }
            closedir($handle);
            if(!@rmdir($dir))
                return false;
            return true;
        }
        return false;
    }
    
    function fopen_maxsize($fn,$mode,$max_size)
    {
        if(file_exists($fn)){
            if(filesize($fn)>=$max_size){
                $new_fn=$fn.date('Y_m_j_h_i_s');
                rename($fn,$new_fn);
            }
        }
        return fopen($fn,$mode);
    }
    
    
//===Multi-Language support===
    define("GENLIB_LANGFILE_EXT",".lang");
    function mlang_readfile($fn)
    {
        $lines=file_noCRLF($fn);
        //Skip the first language name row.
        array_shift($lines);
        
        return $lines;
    }
    
    function mlang_retrieve_strings($sub_path,$langid,$prefix = "")
    {
        $lang_fn=$sub_path.$prefix.$langid.GENLIB_LANGFILE_EXT;
        if(file_exists($lang_fn)){
            $lang_strings = mlang_readfile($lang_fn);
        }else{
            if(file_exists($sub_path.$prefix.DEFLANG_NAME.GENLIB_LANGFILE_EXT)){
                $lang_strings = mlang_readfile($sub_path.DEFLANG_NAME.GENLIB_LANGFILE_EXT);
            }
        }
        return $lang_strings;
    }
    
    function mlang_isfile($fn)
    {
        return (strlen(strstr($fn,GENLIB_LANGFILE_EXT))==strlen(GENLIB_LANGFILE_EXT));
    }
    
    function mlang_getinfofromfile($fn)
    {
        $lines=file_noCRLF($fn);
        return array(substr($fn,0,strlen($fn)-strlen(GENLIB_LANGFILE_EXT)),$lines[0]);
    }
    
    function mlang_GetIDArray($path)
    {
        $lang_arr = array();
        $files = scandir($path);
        for($i=0; $i<sizeof($files); $i++){
            if( mlang_isfile($files[$i]) ){
                array_push($lang_arr, mlang_getinfofromfile($files[$i]));
            }
        }
        return $lang_arr;
    }
    
    function mlang_ShowSelectionCmb($param_lang,$path="./",$disp_text = "")
    {
        $LANG_ID_ARRAY = mlang_GetIDArray($path);
        $html_code="<form name='lang_sel' method='get'>".$text."<select name='lang' style='font-size:12px' onChange='javascript:submit();'>";
        for($i=0;$i<sizeof($LANG_ID_ARRAY);$i++){
            if(strcmp($param_lang,$LANG_ID_ARRAY[$i][0])==0){
                $html_code=$html_code."<option value='".$param_lang."' selected>".$LANG_ID_ARRAY[$i][1]."</option>\r\n";
            }else{
                $html_code=$html_code."<option value='".$LANG_ID_ARRAY[$i][0]."'>".$LANG_ID_ARRAY[$i][1]."</option>\r\n";
            }
        }
        $html_code.="</select>";
        if(false){
            $html_code.="<input value='GO' type='button' style='width:25px; font-size:9px' onClick='javascript:submit();'></input>";
        }
        $html_code.="</form>";
        return $html_code;
    }
    
    define("DEFLANG_NAME","en");
    define("PIDX_CLANG_EMAIL",0);
    define("PIDX_CLANG_TWITTER",1);
    define("PIDX_CLANG_WEIBO",2);
    define("PIDX_CLANG_CONTACT",3);
    define("PIDX_CLANG_SOCIAL",4);
    define("PIDX_CLANG_EMAILREPLACE",5);
    define("PIDX_CLANG_ABOUT",6);
	define("PIDX_CLANG_TENCENT",7);
    define("PIDX_CLANG_MAX",7);
    
    class CLangMgr
    {
        
        private static $langs_array=array(
                                          "en"=>array(
                                                      "Email",
                                                      "Twitter",
                                                      "Weibo",
                                                      "Contact",
                                                      "Social",
                                                      "Please replace 'at' with @",
                                                      "About",
													  "Tencent",
                                                      ),
                                          "cn"=>array(
                                                      "电子邮件",
                                                      "Twitter",
                                                      "微博",
                                                      "联系",
                                                      "Social",
                                                      "请将'at'替换为@",
                                                      "关于",
													  "腾讯",
                                                      ),
                                          "jp"=>array(
                                                      "メール",
                                                      "ツイッター",
                                                      "Weibo",
                                                      "連絡",
                                                      "ソーシャル",
                                                      "'at'を@に替えてください",
                                                      "アバウト",
													  "テンセント",
                                                      )
                           );
        
        public function __construct($lang=DEFLANG_NAME,$subpath = "")
        {
            $this->_strLangName=$lang;
            $this->_langfile=mlang_retrieve_strings($subpath,$lang);
        }
        
        public function __destruct()
        {
        }
        
        public function getContent($lang_idx)
        {
            $content="";
            if($lang_idx<=PIDX_CLANG_MAX){
                $lang_arr=CLangMgr::$langs_array[$this->_strLangName];
                if(isset($lang_arr))
                    $content=$lang_arr[$lang_idx];
            }else{
                $content=$_this->langfile[$lang_idx-PIDX_CLANG_MAX];
            }
            return $content;
        }
        
        public function getFileContent($lang_idx)
        {
            $content=$this->_langfile[$lang_idx];
            
            return $content;
        }
        
        private $_strLangName;
        private $_langfile;
    }



//Common functions
function gen_CreareClassByname($class_name)
{
 $class = "Class".$class_name;
 $object = new $class();
 
 return $object;
}



//========Image Functions============
//Return image object.
function Image_CreateFromFile($path, $user_functions = false)
{
    $info = @getimagesize($path);
    if(!$info){
        return false;
    }
    $functions = array(
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_WBMP => 'imagecreatefromwbmp',
        IMAGETYPE_XBM => 'imagecreatefromwxbm',
        );
   
    if($user_functions){
        $functions[IMAGETYPE_BMP] = 'imagecreatefrombmp';
    }
    if(!$functions[$info[2]]){
        return false;
    }
    if(!function_exists($functions[$info[2]])){
        return false;
    }
    return $functions[$info[2]]($path);
}

/*function Image_SaveToFile($filename, $user_functions = false)
{
 $filename;
}*/

function Image_LargerThanSize($originalImage,$cWidth,$cHeight)
{
 list($width, $height) = getimagesize($originalImage);
 
 if($width>$cWidth && $height>$cHeight)
  return true;
 else
  return false;
}

function Image_Resize($originalImage,$toWidth,$toHeight)
{
    // Get the original geometry and calculate scales
    list($width, $height) = getimagesize($originalImage);
    $xscale=$width/$toWidth;
    $yscale=$height/$toHeight;
    
    // Recalculate new size with default ratio
    if ($yscale>$xscale){
        $new_width = round($width * (1/$yscale));
        $new_height = round($height * (1/$yscale));
    }
    else {
        $new_width = round($width * (1/$xscale));
        $new_height = round($height * (1/$xscale));
    }

    // Resize the original image
    $imageResized = imagecreatetruecolor($new_width, $new_height);
    $imageTmp     = Image_CreateFromFile($originalImage);
    imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
    return $imageResized;
}



//==========
//Thumb function module.
define("THUMB_FOLDER_NAME","thumb");
function thumb_makethumbimgname($org_img_name,$thumb_width,$thumb_height)
{
 return $org_img_name."_t".$thumb_width."x".$thumb_height;
}

function thumb_makethumbimagepath($org_img_file,$width,$height)
{
 $larger=Image_LargerThanSize($org_img_file,$width,$height);
 if($larger){
  //Parse path for the extension.
  $path_info=pathinfo($org_img_file);
  $thumb_img_file=$path_info["dirname"];
  $last_char=substr($thumb_img_file,strlen($thumb_img_file)-1);
  if($last_char!="\\" && $last_char!="/"){
   $thumb_img_file=$thumb_img_file."/";
  }
  $thumb_img_file=$thumb_img_file.THUMB_FOLDER_NAME."/";
  if(!is_dir($thumb_img_file)){
   echo "<!-- create folder: \"".$thumb_img_file."\" !-->";
   mkdir($thumb_img_file,0777,TRUE);
  }
  $thumb_img_file=thumb_makethumbimgname($thumb_img_file.$path_info["basename"],$width,$height).".png";
 }else
  $thumb_img_file=$org_img_file;
  
 return $thumb_img_file;
}

//Retrieve thumb image path through original image path.
function thumb_getthumbimage($org_img_file,$width,$height,$force_create = false)
{
 $thumb_img_file=thumb_makethumbimagepath($org_img_file,$width,$height);
 if(strcmp($thumb_img_file,$org_img_file)!=0){
  $recreate_timg=$force_create;
  if(!file_exists($thumb_img_file)){
   $recreate_timg=true;
  }else if(File_CompareDate($thumb_img_file,$org_img_file)<0){
   $recreate_timg=true;
  }
  if($recreate_timg==true){
   $thumb_img=Image_Resize($org_img_file,$width,$height);
   imagepng($thumb_img,$thumb_img_file,-1);
   //chmod($thumb_img_file,755);
  }
 }
 return $thumb_img_file;
}

function Image_MakeGalleryHtml($pathToImages)
{
  //$imagesRelPath=ConfirmLastPathBreaker($imagesRelPath);
  $imagesRelPath="../";
  $pathToImages=ConfirmLastPathBreaker($pathToImages);
  $pathToThumbs=$pathToImages.THUMB_FOLDER_NAME;
  $pathToThumbs=ConfirmLastPathBreaker($pathToThumbs);
  
  $output="<html>";
  $output.="<head><title>Thumbnails Gallery View</title></head>";
  $output.="<body>";
  $output.="<p><b>Thumbnails Gallery of images in ".$pathToImages." folder.</b></p>";
  $output.="<table cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">";
  $output.="<tr>";

  // open the directory
  $dir=opendir($pathToImages);
  $counter=0;
  // loop through the directory
  while(false!==($fname=readdir($dir)))
  {
    $thumb_fname=thumb_makethumbimgname($fname,128,128).".png";
    // strip the . and .. entries out
	$fq_path=$pathToThumbs.$thumb_fname;
    if($fname!='.' && $fname!='..' && Image_CreateFromFile($fq_path)!=false)
    {
      $output.="<td valign=\"middle\" align=\"center\"><a href=\"".$imagesRelPath.$fname."\">";
      $output.="<img src=\"{$thumb_fname}\" border=\"0\" />";
	  $output.="<br><font size='-1'><i>".$fname."</i></font>";
      $output.="</a></td>";

      $counter+=1;
      if($counter % 4 == 0 )
	  {
	   $output.="</tr><tr>\r\n";
	  }
    }
  }
  // close the directory
  closedir($dir);

  $output.="</tr>";
  $output.="</table><br><br><br>";
  $output.="<div align='left'><font size='-1' color='gray'>Created on: ".date("F j, Y, G:i:s ")."</font></div>";
  $output.="</body>";
  $output.="</html>";
  
  return $output;
}

function Image_CreateGalleryHtml($pathToImages,$html_file = "index.html")
{
  $output=Image_MakeGalleryHtml($pathToImages);
  $pathToImages=ConfirmLastPathBreaker($pathToImages);
  $pathToThumbs=$pathToImages.THUMB_FOLDER_NAME;
  $pathToThumbs=ConfirmLastPathBreaker($pathToThumbs);
  
  // open the file
  $fhandle=fopen($pathToThumbs.$html_file,"w+");
  if($fhandle!=FALSE){
   // write the contents of the $output variable to the file
   $wrote=fwrite($fhandle,$output);
   // close the file
   fclose($fhandle);
  }
  return $wrote;
}


//SQL functions
function SQL_MysqlAvailable()
{
	return (function_exists("mysql_query") && function_exists("mysql_pconnect") && function_exists("mysql_select_db") && function_exists("mysql_close"));
}

function SQL_MakeColumnNames()
{
 $strCols="";
 for($i=0;$i<func_num_args();$i++){
  $strCols.=func_get_arg($i).' ';
 }
 return $strCols;
}

function SQL_MakeInsert()
{
 $strInsertSql="(";
 $num_cols=func_num_args()/2;
 for($i=0;$i<$num_cols;$i++){
  $strInsertSql.=func_get_arg($i).', ';
 }
 $strInsertSql.=") VALUES (";
 for($i=0;$i<$num_cols;$i++){
  $strInsertSql.=mysql_real_escape_string(func_get_arg($num_cols+$i)).', ';
 }
 $strInsertSql.=")";

 return $strInsertSql;
}

function SQL_CheckTableExists($table_name)
{
 return "DESCRIBE ".$table_name.";";
}


//Email identify.
function EI_RegisterEmail($email,$subject,$msg_front,$identify_url,$msg_back,$from="")
{
 $body=$msg_front."<a href='".$identify_url."' target='_blank'>".$identify_url."</a>".$msg_back;
 if(mail($email,$subject,$body,$from)){
  return TRUE;
 }else{
  return FALSE;
 }
}
    
function Tweet($login_user,$pwd,$tweet_msg)
{
    $status = urlencode(stripslashes(urldecode($tweet_msg)));
    
    if ($status) {
        //http://www.twitter.com/statuses/timeline.xml
        $tweetUrl = 'http://www.twitter.com/statuses/update.xml';
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "$tweetUrl");
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "status=$status");
        curl_setopt($curl, CURLOPT_USERPWD, "$login_user:$pwd");
        
        $result = curl_exec($curl);
        $resultArray = curl_getinfo($curl);
        
        
        var_dump($result);
        echo $resultArray['http_code'];
        if ($resultArray['http_code'] == 200)
            return TRUE;
        else
            return FALSE;
        
        curl_close($curl);
    }
    return FALSE;
}
 
    //HTTP request sender class.
    class CHttpReq
    {
        public function __construct()
        {
            $this->m_curl = curl_init();
        }
        
        public function __destruct()
        {
            if($this->m_curl!=FALSE)
                curl_close($this->m_curl);
        }
        
        public function setOpt($key,$val)
        {
            return curl_setopt($this->m_curl,$key,$val);
        }
        
        public function sendPost($url,$param_array)
        {
            $this->setOpt(CURLOPT_POST, 1);
            
            return $this->send($url);
        }
        
        public function sendGet($url,$param_array)
        {
            $this->setOpt(CURLOPT_HTTPGET, 1);
            
            return $this->send($url);
        }
        
        public function getInfo()
        {
            return curl_getinfo($this->m_curl);
        }
        
        public function getCurl()
        {
            return $this->m_curl;
        }
        
        private function setOpts($params_array)
        {
            foreach ($params_array as $key => $val) {
                $this->setOpts($key,$val);
            }
        }
        
        private function send($url)
        {
            $this->setOpt(CURLOPT_URL, "$url");
            
            return curl_exec($this->m_curl);
        }
        
        private $m_curl;
    };

//Subscribe manager
class CSubscribeMgr_Text
{
 public function __construct($fn="")
 {
  if(strlen($fn)>0)
   $file_handle=fopen($fn,"rb");
  else
   $file_handle=FALSE;
 }
 
 public function __destruct()
 {
  if($file_handle!=FALSE)
   fclose($file_handle);
 }
 
 public function isValid()
 {
  return ($file_handle!=FALSE);
 }

 //TODO: Optimize methods:
 // register
 // is_registered
 // unregister
 public function register($email)
 {
  $bReg=FALSE;
  if(is_registered($email)==FALSE){
   fseek($file_handle,0,SEEK_END);
   fwrite($file_handle,$email."\r\n");
   
   $bReg=TRUE;
  }
  return $bReg;
 }
 
 public function is_registered($email)
 {
  $bReg=FALSE;
  
  reset();
  while(isEndIterate()){
   $_email=next();
   if($_email==$email){
    $bReg=TRUE;
    break;
   }
  }
  return $bReg;
 }
 
 public function reset()
 {
  rewind($file_handle);
 }
 
 public function isEndIterate()
 {
  return (feof($file_handle));
 }
 
 public function next()
 {
  if(!feof($file_handle)){
   $line_of_text = fgets($file_handle);
  }else{
   $line_of_text="";
  }
  return $line_of_text;
 }
 
 public function unregister($email)
 {
 }
 
 private $file_handle;
}

class CSubscribeMgr_DB
{
 public function __construct($str_db_conn="")
 {
 }
 
 public function __destruct()
 {
 }

 public function register($email)
 {
  return FALSE;
 }
 
 public function is_registered($email)
 {
  return FALSE;
 }
 
 public function reset()
 {
 }
 
 public function next()
 {
  return "";
 }
 
 public function isEndIterate()
 {
  return FALSE;
 }
 
 public function unregister($email)
 {
 }
 
 private $db;
}
?>