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
	 * ���������ݿ��������
	 * $tabName ����
	 */
function Model($tabName=null){
    //���û�д���������ֱ�Ӵ���DB���󣬵����ܶԱ���в���

        $conf = \core\lib\Conf::get('db','db_type');

        $class="core\\db\\driver\\Db_Adapter_".ucwords($conf);
        $db= new $class();
        //���ɱ�ṹ
        if(!is_null($tabName)){
            $tabName = \core\lib\Conf::get('db','prefix').$tabName;
            $db->setTable($tabName);
        }
        return $db;
}


//��ȡ����url
function get_fullurl()
{
    global $ym_is_https;
    $protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' && $ym_is_https == 1 ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $req_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
    return $protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $req_url;

}

/*��תҳ��*/
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

/*����*/
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


//����cookie
function set_cookie($name, $value='',$expire=0, $path="/", $domain=null)
{
    $domain = $domain==null ? cookiedomain : $domain;
    setcookie($name, $value, $expire, $path, $domain);
}


/**@name ������
 *@param pid ָ����id
 * */
function get_catTree($pid = 0)
{
    global $ym_cats;

    return getTree($ym_cats, $pid, 'id', 'pid');
}

//��������
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


//���� mid top bot
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

//��ȡ��������
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

//��ȡ�û�id
function get_userid()
{
    return (!isset($_SESSION['uid']) ? 0 : intval($_SESSION['uid']));
}


/**��ʽ���۸�
 * @param   float   $price  ��Ʒ�۸�
 * @param 	int		$format_type �۸��ʽ
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
        case 1: // ���������룬����һλС��
            $price = substr(number_format($price, 2, '.', ''), 0, -1);
            break;
        case 2: // ���������룬������С��
            $price = intval($price);
            break;
        case 3: // �������룬������С��
            $price = round($price);
            break;
        case 4: // �������룬����һλС��
            $price = number_format($price, 1, '.', '');
            break;
        case 5: // �������룬������λС��
            $price = number_format($price, 2, '.', '');
            break;
    }

    return $price;
}


/** ƴ�ӳ�in��ʽ: IN('a','b','c')
 * @access   public
 * @param    mix      $list      �б�������ַ���
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


/*��ȡ��ά�������ĳЩ����
 $key=����
 $val=��ֵ
 $arr=����
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

//��ȡ��������½��ʽ
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


/*��ȡ����  $arr[0]Ϊ��*/
function get_parents($id = 0, $list, $pid = 'pid'){
    if ($list[$id][$pid]!=0){
        $arr = get_parents($list[$id][$pid], $list);
    }
    $arr[] =$list[$id];
    return $arr;
}



/**@name ����url
 *@param
 * */
function build_url($k, $v, $list = array()) {
    $url = parse_url($_SERVER['REQUEST_URI']);

    parse_str($url['query'], $param);

    $v_arr = explode('_', $v);
    if($k=='at' && array_key_exists($k, $param))
    {
        $at_arr= array_filter(explode('@', $param[$k]));  // at=71_1000��@72_��ɫ
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
 * @name ƴ���ַ���������ȥ���ظ���: 'a','b','c'
 * @access   public
 * @param    mix      $list      �б�������ַ���
 * @param    bool	 $res_arr �Ƿ񷵻�����
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


//���÷�ҳ
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

/**�Ƿ��ֻ�����
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
 *��һ���ִ��к���ȫ�ǵ������ַ�����ĸ���ո��'%+-()'�ַ�ת��Ϊ��Ӧ����ַ�
 * @access    public
 * @param     string $str     ��ת���ִ�
 * @return    string  $str    ������ִ�
 */
function make_semiangle($str) {
    $arr = array('��' => '0', '��' => '1', '��' => '2', '��' => '3', '��' => '4','��' => '5', '��' => '6', '��' => '7', '��' => '8', '��' => '9', '��' => 'A', '��' => 'B', '��' => 'C', '��' => 'D', '��' => 'E','��' => 'F', '��' => 'G', '��' => 'H', '��' => 'I', '��' => 'J', '��' => 'K', '��' => 'L', '��' => 'M', '��' => 'N', '��' => 'O','��' => 'P', '��' => 'Q', '��' => 'R', '��' => 'S', '��' => 'T','��' => 'U', '��' => 'V', '��' => 'W', '��' => 'X', '��' => 'Y','��' => 'Z', '��' => 'a', '��' => 'b', '��' => 'c', '��' => 'd','��' => 'e', '��' => 'f', '��' => 'g', '��' => 'h', '��' => 'i','��' => 'j', '��' => 'k', '��' => 'l', '��' => 'm', '��' => 'n','��' => 'o', '��' => 'p', '��' => 'q', '��' => 'r', '��' => 's', '��' => 't', '��' => 'u', '��' => 'v', '��' => 'w', '��' => 'x', '��' => 'y', '��' => 'z','��' => '(', '��' => ')', '��' => '[', '��' => ']', '��' => '[','��' => ']', '��' => '[', '��' => ']', '��' => '[', '��' => ']','��' => '[', '��' => ']', '��' => '{', '��' => '}', '��' => '<','��' => '>','��' => '%', '��' => '+', '��' => '-', '��' => '-', '��' => '-','��' => ':', '��' => '.', '��' => ',', '��' => '.', '��' => '.',     '��' => ',', '��' => '?', '��' => '!', '��' => '-', '��' => '|', '��' => '"', '��' => '`', '��' => '`', '��' => '|', '��' => '"','��' => ' ');
    return strtr($str, $arr);
}

//��ȡ�ַ�����Ӣ�Ļ�ϳ���
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

/**@name �Ƿ�ֻ������
 *@param str �ַ���
 * */
function is_num($str)
{
    return preg_match("/^[0-9]+$/", $str);
}
//�����������
function check_censor($field, $word='keepword')
{
    $words =get_cache('censor', $path = CACHE_DATA, $word);
    $exp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($words = trim($words)), '/')).')$/i'; //print $words.' '.$exp;
    if($words && @preg_match($exp, $field)) {
        return false;
    }
    return true;
}

/**ͨ��curl��ʽ��ȡԶ��ͼƬ������
 *@param $url ������ͼƬ��ַ
 *@param $filename �ļ���
 */
function get_curl_file($url = "", $filename = "" , $time=6) {
    if(!is_dir($filename)) {
        mdir(str_replace(basename($filename), '', $filename));
    }
    //ȥ��URL����������ܵ�����
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

/**@name ��ȡ����ַ�������Сд��ĸ+���֣�ת�䲻���ִ�Сд
 *@param $len ����
 *@param $ignoreCase ���Դ�Сд
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

//��ȡ�û��豸����
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

//�����ַ���
function encryptStr($val, $salt='')
{
    return md5(md5(trim($val)).$salt);
}

// $string�� ���� �� ����
// $operation��DECODE��ʾ����,������ʾ����
// $key�� �ܳ�
// $expiry��������Ч��
function endecrypt($string, $operation = 'DECODE', $key = 'yunec.cn', $expiry = 0) {
    // ��̬�ܳ׳��ȣ���ͬ�����Ļ����ɲ�ͬ���ľ���������̬�ܳ�
    global $ym_auth_key;
    $ckey_length = 4;

    // �ܳ�
    $key = md5($key ? $key : $ym_auth_key);

    // �ܳ�a�����ӽ���
    $keya = md5(substr($key, 0, 16));
    // �ܳ�b��������������������֤
    $keyb = md5(substr($key, 16, 16));
    // �ܳ�c���ڱ仯���ɵ�����
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    // ����������ܳ�
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // ���ģ�ǰ10λ��������ʱ���������ʱ��֤������Ч�ԣ�10��26λ��������$keyb(�ܳ�b)������ʱ��ͨ������ܳ���֤����������
    // ����ǽ���Ļ�����ӵ�$ckey_lengthλ��ʼ����Ϊ����ǰ$ckey_lengthλ���� ��̬�ܳף��Ա�֤������ȷ
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // �����ܳײ�
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // �ù̶����㷨�������ܳײ�����������ԣ�����ܸ��ӣ�ʵ���϶Բ������������ĵ�ǿ��
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // ���ļӽ��ܲ���
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // ���ܳײ��ó��ܳ׽��������ת���ַ�
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 ��֤������Ч��
        // substr($result, 0, 10) - time() > 0 ��֤������Ч��
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) ��֤����������
        // ��֤������Ч�ԣ��뿴δ�������ĵĸ�ʽ
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // �Ѷ�̬�ܳױ������������Ҳ��Ϊʲôͬ�������ģ�������ͬ���ĺ��ܽ��ܵ�ԭ��
        // ��Ϊ���ܺ�����Ŀ�����һЩ�����ַ������ƹ��̿��ܻᶪʧ��������base64����
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

/**
 * �ӹ�php�쳣����
 *
 */
function api_response($err = 502,$message='�����쳣'){
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

/*�����·��ת���ɾ���·��*/
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

//�ж��ַ����Ƿ��Ǿ��Ե�ַ
function is_httpOrhttps($val)
{
    return preg_match('/(http:\/\/)|(https:\/\/)/i', $val);
}

/**�Ƿ�΢�������*/
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