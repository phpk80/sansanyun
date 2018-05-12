<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/2/20
 * Time: 10:05
 */
function p($var){
    if(is_bool($var)){
        var_dump($var);
    }else if(is_null($var)){
        var_dump(NULL);
    }else{
         echo "<pre  style='position:relative;z-index:1000;padding:10px;border-radius:5px;
               background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;
               opacity:0.9;'>".print_r($var,true)."</pre>";
    }
}
    /*
	 * 创建的数据库操作对象
	 * $tabName 表名
	 */
function Model($tabName=null){
    //如果没有传表名，则直接创建DB对象，但不能对表进行操作

        $conf = \core\lib\Conf::get('db','db_type');

        $class="core\\db\\driver\\Db_Adapter_".ucwords($conf);
        $db= new $class();
        //生成表结构
        if(!is_null($tabName)){
            $tabName = \core\lib\Conf::get('db','prefix').$tabName;
            $db->setTable($tabName);
        }
        return $db;
}


//获取完整url
function get_fullurl()
{
    global $ym_is_https;
    $protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' && $ym_is_https == 1 ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $req_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    return $protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $req_url;

}

/*跳转页面*/
function redirect($url, $time=0)
{
    if($time !=0)
    {
        echo '<meta http-equiv="Refresh" content="'.$time.'; url='.$url.'" />';
    }
    else {
        header("Location:".$url);
    }
    exit(0);
}

function ucode($txt, $key="8899666457303223445567")
{

    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $ch = $txt[0];

    $nh = strpos($chars,$ch);
    $mdKey = md5($key.$ch);
    $mdKey = substr($mdKey,$nh%8, $nh%8+7);
    $txt = substr($txt,1);

    $tmp = '';
    $i=0;$j=0; $k = 0;
    for ($i=0; $i<strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
        while ($j<0) $j+=61;
        $tmp .= $chars[$j];
    }

    return base64_decode($tmp);
}

/*过虑*/
function addslashes_val($val)
{
    if (empty($val) || get_magic_quotes_gpc())
    {
        return $val;
    }
    else
    {
        return is_array($val) ? array_map('addslashes_val', $val) : addslashes($val);
    }
}


//设置cookie
function set_cookie($name, $value='',$expire=0, $path="/", $domain=null)
{
    $domain = $domain==null ? cookiedomain : $domain;
    setcookie($name, $value, $expire, $path, $domain);
}


/**@name 分类树
 *@param pid 指定根id
 * */
function get_catTree($pid = 0)
{
    global $ym_cats;

    return getTree($ym_cats, $pid, 'id', 'pid');
}

//树形数组
function getTree($rows, $root = 0, $id = 'id', $pid = 'pid', $child = 'child') {
    $tree = array();
    if (is_array($rows)) {
        $array = array();
        foreach ($rows as $key => $item) {
            $array[$item[$id]] = &$rows[$key];
        }

        foreach ($rows as $key => $item) {
            $parentId = $item[$pid];
            if ($root == $parentId) {
                $tree[] = &$rows[$key];
            } else {
                if (isset($array[$parentId])) {
                    $parent = &$array[$parentId];
                    $parent[$child][] = &$rows[$key];
                }
            }
        }
    }

    return $tree;
}


//导航 mid top bot
function get_nav($type ='mid')
{
    $san_nav = get_cache("nav");
    if($type !='')
    {
        foreach ($san_nav as $k => $v) {
            if($type!=$v['type'])
            {
                unset($san_nav[$k]);
            }
        }
    }
    return $san_nav;
}

//读取缓存数据
function get_cache($val, $path = CACHE_DATA, $name='')
{
    $sansan_val= 'ym_'.$val;
    $file = $path.DIRECTORY_SEPARATOR.$val.'.php';

    if(file_exists($file))
    {
        require $file;

        $sansan_val= 'sansan_'.($name==''? $val :$name);
        return $$sansan_val;
    }
    else {
        return array();
    }
}

//获取用户id
function get_userid()
{
    return (!isset($_SESSION['uid']) ? 0 : intval($_SESSION['uid']));
}


/**格式化价格
 * @param   float   $price  商品价格
 * @param 	int		$format_type 价格格式
 * @return  string
 */
function format_price($price, $format_type=-1)
{
    global $ym_priceformat;
    $price = floatval($price);
    $format_type= $format_type == -1 ? $ym_priceformat : $format_type;

    switch ($format_type)
    {
        case 0:
            $price = number_format($price, 2, '.', '');
            break;
        case 1: // 不四舍五入，保留一位小数
            $price = substr(number_format($price, 2, '.', ''), 0, -1);
            break;
        case 2: // 不四舍五入，不保留小数
            $price = intval($price);
            break;
        case 3: // 四舍五入，不保留小数
            $price = round($price);
            break;
        case 4: // 四舍五入，保留一位小数
            $price = number_format($price, 1, '.', '');
            break;
        case 5: // 四舍五入，保留两位小数
            $price = number_format($price, 2, '.', '');
            break;
    }

    return $price;
}


/** 拼接成in格式: IN('a','b','c')
 * @access   public
 * @param    mix      $list      列表数组或字符串
 * @return   string
 */
function create_in($list= '')
{
    if (empty($list)) {
        return " IN ('') ";
    } else {
        $str = joinString($list);
        return trim($str) == '' ? " IN ('') " : " IN (" . $str . ") ";
    }
}


/*获取多维数组里的某些数组
 $key=键名
 $val=键值
 $arr=数组
 * */
function array_query($key, $val, $arr) {
    $res =array();
    if(is_array($arr))
    {
        foreach ($arr as $k => $v) {
            if (trim($v[$key])===trim($val) ) {
                $res[] = $v;
            }
        }
    }
    return $res;
}

//获取第三方登陆方式
function get_oauth($status=1, $code='')
{
    $db =Model('oauth');
    $where = array('status'=>$status);
    if($code !='')
    {
        $where['code'] = $code;
    }
    $row = $db->where($where)->select();

    foreach ($row as $k => $v) {
        if($v['is_qrcode']==1)
        {
            $oauthfile = plugin."oauth/".trim($v['code']).'/'.trim($v['code']).Ext;
            if(file_exists($oauthfile))
            {
                @include($oauthfile);
                $row[$k]['src']= get_oauthcode();
            }
        }
    }
    return $row;
}


function get_crumbs_nav($type='', $id= 0)
{
    switch ($type) {
        case 'goods':
            global $ym_cats;
            $list = get_parents($id, $ym_cats);
            $tmp_nav='';
            foreach ($list as $k => $v) {
                $tmp_nav .= ' &gt; <a href="'.$v['url'].'">'.$v['name'].'</a>';
            }
            return $tmp_nav;
            break;
        case 'news':
            global $ym_idsort;
            $list = get_parents($id, $ym_idsort);
            $tmp_nav='';
            foreach ($list as $k => $v) {
                $tmp_nav .= '&gt; <a href="'.$v['url'].'">'.$v['name'].'</a>';
            }
            return ltrim($tmp_nav,'&gt; ');
            break;
        default:
            return '';
            break;
    }
}


/*获取祖先  $arr[0]为根*/
function get_parents($id = 0, $list, $pid = 'pid'){
    if ($list[$id][$pid]!=0){
        $arr = get_parents($list[$id][$pid], $list);
    }
    $arr[] =$list[$id];
    return $arr;
}



/**@name 构造url
 *@param
 * */
function build_url($k, $v, $list = array()) {
    $url = parse_url($_SERVER['REQUEST_URI']);

    parse_str($url['query'], $param);

    $v_arr = explode('_', $v);
    if($k=='at' && array_key_exists($k, $param))
    {
        $at_arr= array_filter(explode('@', $param[$k]));  // at=71_1000克@72_白色
        $i = count($at_arr);
        foreach ($at_arr as $key => $val) {
            $val_arr = explode('_', $val);
            if($val_arr[0] == $v_arr[0])
            {
                $i = $key;
                break;
            }
        }
        if($v_arr[1]=='')
        {
            unset($at_arr[$i]);
        }
        else {
            $at_arr[$i] = $v;
        }
        $param[$k] = stripslashes(implode($at_arr, '@'));
    }
    elseif($k=='at' && $v_arr[1]=='') {
        unset($param[$k]);
    }
    else {
        $param[$k] = stripslashes($v);
    }
    $param['page']=1;
    $param = array_filter($param);
    return $url['path'].(count($param)==0?'': '?'.http_build_query($param));
}

/**
 * @name 拼接字符串，并且去掉重复项: 'a','b','c'
 * @access   public
 * @param    mix      $list      列表数组或字符串
 * @param    bool	 $res_arr 是否返回数组
 * @return   string
 */
function joinString($list='', $delimiter =',', $res_arr=false)
{
    if (!is_array($list))
    {
        $list = explode($delimiter, $list);
    }
    $list = array_unique($list);
    $arr = array();
    foreach ($list AS $v)
    {
        if (is_array($v)) {
            foreach ($v as $key => $val) {
                $arr[]= "'".$val."'";
            }
        }
        elseif ($v !== '')
        {
            $arr[]= "'$v'";
        }
    }
    if($res_arr)
    {
        return $arr;
    }
    return count($arr)==0 ? " " : join($delimiter, $arr);
}


//设置分页
function getPages($count, $page=1, $pagenum=12, $style=1)
{
    if($count<=$pagenum) {
        return array('pages'=>'','first_page'=>'','last_page'=>'');
    }

    $url = parse_url($_SERVER["REQUEST_URI"]);
    parse_str($url['query'],$url_param)	;
    unset($url_param['page']);

    $params = array(
        'total_rows'=>$count,
        'method'    =>'html',
        'parameter' => $url['path'].(count($url_param)>0 ? ('?'.http_build_query($url_param).'&') : '?').'page={?}',
        'now_page'  =>$page,
        'list_rows' =>$pagenum,
    );
    $thispage = new \core\util\Page($params);
    return $thispage->show($style);
}
function get_url($code, $type='goods')
{
    global $ym_htmlext, $ym_idsort,$conext;
    switch ($type) {
        case 'goods':
            return '/goods/item/id/'.$code;
        case 'news':
            return '/n-'.$code.$conext['news'].".html";
        default:
            return '';
    }
}

function build_newsurl($row)
{
    global $ym_htmlext, $ym_idsort,$conext;
    //$dir=$ym_idsort[$row['c_toid']]['dir']==''?'':$ym_idsort[$row['c_toid']]['dir'].'/';
    //return '/'.$dir.$ym_idsort[$row['c_toid']]['file'].Condir.$row['c_code'].$conext['news'].$ym_htmlext;
    return '/n-'.$row['c_code'].$conext['news'].$ym_htmlext;
}

/**是否手机号码
 * @param   string   $str
 */
function is_mobile($str)
{
    return preg_match("/^(13|14|15|18|17)[0-9]{9}$/", $str);
}


function get_randNum($len=6)
{
    $chars = '0123456789';
    $str = '';
    for ( $i = 0; $i < $len; $i++ )
    {
        $str .= $chars[mt_rand(0, strlen($chars) - 1) ];
    }
    return $str;
}

function getip()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }

    return $realip;
}

/**
 *将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
 * @access    public
 * @param     string $str     待转换字串
 * @return    string  $str    处理后字串
 */
function make_semiangle($str) {
    $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4','５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9', 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E','Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O','Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T','Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y','Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd','ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i','ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n','ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y', 'ｚ' => 'z','（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[','】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']','‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<','》' => '>','％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-','：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',     '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|', '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"','　' => ' ');
    return strtr($str, $arr);
}

//获取字符串中英文混合长度
function get_strlen($str,$charset='utf-8'){
    if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
    $num = strlen($str);
    $cnNum = 0;
    for($i=0;$i<$num;$i++){
        if(ord(substr($str,$i+1,1))>127){
            $cnNum++;
            $i++;
        }
    }
    $enNum = $num-($cnNum*2);
    return $cnNum*2+$enNum;
}

/**@name 是否只有数字
 *@param str 字符串
 * */
function is_num($str)
{
    return preg_match("/^[0-9]+$/", $str);
}
//检测审查的文字
function check_censor($field, $word='keepword')
{
    $words =get_cache('censor', $path = CACHE_DATA, $word);
    $exp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($words = trim($words)), '/')).')$/i'; //print $words.' '.$exp;
    if($words && @preg_match($exp, $field)) {
        return false;
    }
    return true;
}

/**通过curl方式获取远程图片到本地
 *@param $url 完整的图片地址
 *@param $filename 文件名
 */
function get_curl_file($url = "", $filename = "" , $time=6) {
    if(!is_dir($filename)) {
        mdir(str_replace(basename($filename), '', $filename));
    }
    //去除URL连接上面可能的引号
    $url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $url );
    $ch = curl_init();
    $fp = fopen($filename,'wb');
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_FILE,$fp);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch,CURLOPT_TIMEOUT, $time);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return '';
}

/**@name 获取随机字符串，大小写字母+数字，转变不区分大小写
 *@param $len 长度
 *@param $ignoreCase 忽略大小写
 * */
function get_salt($len=6, $ignoreCase=true)
{
    //return substr(uniqid(rand()), -$len);
    $discode="123546789wertyupkjhgfdaszxcvbnm".($ignoreCase?'': 'QABCDEFGHJKLMNPRSTUVWXYZ');
    $code_len = strlen($discode);
    $code = "";
    for($j=0; $j<$len; $j++){
        $code .= $discode[rand(0, $code_len-1)];
    }
    return $code;
}

//获取用户设备类型
function get_client()
{
    global $sansan_client;
    if(isset($_SESSION['client']))
    {
        return $_SESSION['client'];
    }
    session_start();
    if($sansan_client && ($sansan_client ==client_pc || $sansan_client ==client_m || $sansan_client ==client_app))
    {
        $_SESSION['client'] = $sansan_client;
        return $sansan_client;
    }
    include(CORE."/util/mobile_detect.php");
    $detect = new Mobile_Detect();
    if($detect->isMobile())
    {
        $_SESSION['client'] = client_m;
        return client_m;
    }else {
        $_SESSION['client'] = client_pc;
        return client_pc;
    }
}

function jcode($txt, $key="8899666457303223445567")
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $nh = rand(0,61);
    $ch = $chars[$nh];
    $mdKey = md5($key.$ch);
    $mdKey = substr($mdKey,$nh%8, $nh%8+7);
    $txt = base64_encode($txt);
    $tmp = '';
    $i=0;$j=0;$k = 0;
    for ($i=0; $i<strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%61;
        $tmp .= $chars[$j];
    }
    return $ch.$tmp;
}

//加密字符串
function encryptStr($val, $salt='')
{
    return md5(md5(trim($val)).$salt);
}

// $string： 明文 或 密文
// $operation：DECODE表示解密,其它表示加密
// $key： 密匙
// $expiry：密文有效期
function endecrypt($string, $operation = 'DECODE', $key = 'yunec.cn', $expiry = 0) {
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    global $ym_auth_key;
    $ckey_length = 4;

    // 密匙
    $key = md5($key ? $key : $ym_auth_key);

    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

/**
 * 接管php异常处理
 *
 */
function api_response($err = 502,$message='网络异常'){
   echo json_encode(array(
       'err'=>$err,
       'message' => $message
   )) ;

}
function handler(Exception $e){
        //dump($e);
        api_response($e->getMessage(),$e->getCode());
}
function errhandler($errno,$errstr,$errfile,$errline){

    throw new \Exception($errstr,$errno);
}

/*将相对路径转换成绝对路径*/
function url_to_abs($url)
{
    $ym_url = $_SERVER['HTTP_HOST'];
    if($url == '')
    {
        return '';
    }
    if(is_httpOrhttps($url))
    {
        return $url;
    }

    return $ym_url.ltrim($url,"/");
}

//判断字符串是否是绝对地址
function is_httpOrhttps($val)
{
    return preg_match('/(http:\/\/)|(https:\/\/)/i', $val);
}

/**是否微信浏览器*/
function is_weixin()
{
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'micromessenger') === false)
    {
        return false;
    }
    return true;
}
//
function H(){

}