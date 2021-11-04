<?php
namespace Yyh;

class Tools{
    /**
     * 方便打印调试信息
     */
    public static function dre($var)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Keep-Alive,User-Agent,If-Modified-Since,Cache-Control,Content-Type,Access-Control-Allow-Headers, Authorization, X-Requested-With, token");
        dd($var);
    }

    /**
     * 把返回的数据集转换成Tree
     *
     * @param array  $list  要转换的数据集
     * @param string $pk    parent   ID
     * @param string $pid   parent   父级ID
     * @param string $child parent   子级名称
     * @param string $root  parent   父级ID默认值
     *
     * @return array
     */
    public static function  generateTree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
        $tree     = array();
        $packData = array();
        foreach($list as $data) {
            $packData[$data[$pk]] = $data;
        }
        foreach($packData as $key => $val) {
            if($val[$pid] == $root) {
                //代表跟节点, 重点一
                $tree[] = &$packData[$key];
            } else {
                //找到其父类,重点二
                $packData[$val[$pid]][$child][] = &$packData[$key];
            }
        }
        return $tree;
    }

    /**
     * 获取客户端浏览器
     */
    public static function getBrowser($server) {
        $http_user_agent = $server['HTTP_USER_AGENT'] ?? '';
        if(strpos($http_user_agent, 'Maxthon')) {
            $browser = 'Maxthon';
        } else if(strpos($http_user_agent, 'MSIE 12.0')) {
            $browser = 'IE12.0';
        } else if(strpos($http_user_agent, 'MSIE 11.0')) {
            $browser = 'IE11.0';
        } else if(strpos($http_user_agent, 'MSIE 10.0')) {
            $browser = 'IE10.0';
        } else if(strpos($http_user_agent, 'MSIE 9.0')) {
            $browser = 'IE9.0';
        } else if(strpos($http_user_agent, 'MSIE 8.0')) {
            $browser = 'IE8.0';
        } else if(strpos($http_user_agent, 'MSIE 7.0')) {
            $browser = 'IE7.0';
        } else if(strpos($http_user_agent, 'MSIE 6.0')) {
            $browser = 'IE6.0';
        } else if(strpos($http_user_agent, 'NetCaptor')) {
            $browser = 'NetCaptor';
        } else if(strpos($http_user_agent, 'Netscape')) {
            $browser = 'Netscape';
        } else if(strpos($http_user_agent, 'Lynx')) {
            $browser = 'Lynx';
        } else if(strpos($http_user_agent, 'Opera')) {
            $browser = 'Opera';
        } else if(strpos($http_user_agent, 'Chrome')) {
            $browser = 'Google';
        } else if(strpos($http_user_agent, 'Firefox')) {
            $browser = 'Firefox';
        } else if(strpos($http_user_agent, 'Safari')) {
            $browser = 'Safari';
        } else if(strpos($http_user_agent, 'iphone') || strpos($http_user_agent, 'ipod')) {
            $browser = 'iphone';
        } else if(strpos($http_user_agent, 'ipad')) {
            $browser = 'iphone';
        } else if(strpos($http_user_agent, 'android')) {
            $browser = 'android';
        } else {
            $browser = 'other';
        }
        return $browser;
    }

    /**
     * 获取浏览器版本号
     * 
     */
    public static function getBrowserVer($server) {
        $agent = $server['HTTP_USER_AGENT'] ?? '';
        if(preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } else if(preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } else if(preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } else if(preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } else if((strpos($agent, 'Chrome') == false) && preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs)) {
            return $regs[1];
        } else {
            return 'unknow';
        }
    }

    /**
     * 获取用户操作系统
     */
    public static function getClientOS($server, $ua = '') {
        $ua or $ua = $server['HTTP_USER_AGENT'] ?? '';
    
        //window系统
        if(stripos($ua, 'window')) {
            $os = 'Windows';
            if(preg_match('/nt 6.0/i', $ua)) {
                $os .= ' Vista';
            } else if(preg_match('/nt 6.1/i', $ua)) {
                $os .= ' 7';
            } else if(preg_match('/nt 6.2/i', $ua)) {
                $os .= ' 8';
            } else if(preg_match('/nt 10.0/i', $ua)) {
                $os .= ' 10';
            } else if(preg_match('/nt 5.1/i', $ua)) {
                $os .= ' XP';
            } else {
                //其他window操作系统
            }
        } else if(stripos($ua, 'android')) {
            preg_match('/android\s([\d\.]+)/i', $ua, $match);
            $os = 'Android ';
            $os .= $match[1]??'';
        } else if(preg_match('/iPhone|iPad|iPod/i', $ua)) {
            preg_match('/OS\s([0-9_\.]+)/i', $ua, $match);
            $os = 'IOS ';
            $os .= str_replace('_', '.', $match[1]??'');
        } else if(stripos($ua, 'mac os')) {
            preg_match('/Mac OS X\s([0-9_\.]+)/i', $ua, $match);
            $os = 'Mac OS X ';
            $os .= str_replace('_', '.', $match[1]??'');
        } else if(stripos($ua, 'unix')) {
            $os = 'Unix';
        } else if(stripos($ua, 'linux')) {
            $os = 'Linux';
        } else {
            $os = 'Other';
        }
        return $os;
    }

    /**
     * 判断客户端是否手机
     */
    public static function isMobile($server) {
        $useragent               = isset($server['HTTP_USER_AGENT']) ? $server['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';
        function CheckSubstrs($substrs, $text) {
            foreach($substrs as $substr) {
                if(false !== strpos($text, $substr)) {
                    return true;
                }
            }
            return false;
        }

        $mobile_os_list    = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
        $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');

        $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) ||
            CheckSubstrs($mobile_token_list, $useragent);

        if($found_mobile) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 下划线风格转驼峰命名法
     */
    public static function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

    /**
     * 判断为空
     */
    public static function isEmpty($data,$key){
        if(!isset($data[$key])){
            return true;
        }
      
        if(is_array($data[$key])){
            if(empty($data[$key])){
                return true;
            }
        }else{
            if(trim($data[$key]) === ''){
                return true;
            }
        }
        return false;
    }
    /**
     * 生成uuid
     */
    public static function createUuid($prefix = "") {
        //可以指定前缀
        $str  = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }

    /**
     * 用户名、邮箱、手机账号中间字符串以*隐藏
     */
    public static function hideStr($str) {
        if(!$str){
            return '';
        }
        if (strpos($str, '@')) {
            $email_array = explode("@", $str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $rs = $prevfix . $str;
        } else {
            $pattern = '/^1\d{10}$/';
            if (preg_match($pattern, $str)) {
                $rs = substr_replace($str,'****',3,4);
            } else {
                $rs = substr($str, 0, 3) . "***" . substr($str, -1);
            }
        }
        return $rs;
    }
    /**
     * 对银行卡号进行掩码处理
     * @param  string $bankCardNo 银行卡号
     * @return string             掩码后的银行卡号
     */
    public static function formatBankCardNo($bankCardNo){
        //截取银行卡号前4位
        $prefix = substr($bankCardNo,0,4);
        //截取银行卡号后4位
        $suffix = substr($bankCardNo,-4,4);
        $maskBankCardNo = $prefix." **** **** ".$suffix;
        return $maskBankCardNo;
    }

    /**
     * 对姓名进行掩码处理
     * @param  string $name 姓名
     * @return string   掩码后的姓名
     */
    public static function substrCut($name){
        $strLen     = mb_strlen($name, 'utf-8');
        $firstStr   = mb_substr($name, 0, 1, 'utf-8');
        $lastStr    = mb_substr($name, -1, 1, 'utf-8');
        if ($strLen === 1){
            return $name;
        }
        return $strLen == 2 ? $firstStr . str_repeat('*', mb_strlen($name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strLen - 2) . $lastStr;
    }

    /**
     * 获取毫秒时间戳
     */
    public static function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}