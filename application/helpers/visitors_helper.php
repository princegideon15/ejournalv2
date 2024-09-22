<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function ip_info() {

    $datetime = date('Y-m-d H:i:s');
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $name = 'NA';

    if (preg_match('/MSIE/i', $useragent) && !preg_match('/Opera/i', $useragent)) {
        $name = 'Internet Explorer';
    } elseif (preg_match('/Firefox/i', $useragent)) {
        $name = 'Mozilla Firefox';
    } elseif (preg_match('/Chrome/i', $useragent)) {
        $name = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $useragent)) {
        $name = 'Apple Safari';
    } elseif (preg_match('/Opera/i', $useragent)) {
        $name = 'Opera';
    } elseif (preg_match('/Netscape/i', $useragent)) {
        $name = 'Netscape';
    }

    $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');
                        

    $purpose = "location";
    $deep_detect = TRUE;
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }

    $loc = array();
    if($output != null)

    foreach($output as $row)
    {
        array_push($loc,$row);
    }
    
    $location = implode(',',$loc);

    $data = array('vis_location' => $location,
                    'vis_ip' => $ip,
                        'vis_user_agent' => $name,
                            'vis_datetime' => $datetime);

    save_visitor($data);
}

function save_visitor($data)  {
    $CI =& get_instance();  
    $CI->db->insert('tblvisitor_details',$data);
    
}

function getIP(){
    $octet = explode(".",$_SERVER['HTTP_CLIENT_IP']);
    $refer = explode(".",$_SERVER['REMOTE_ADDR']);
    if(!empty($refer[0]) && ($refer[0] != $octet[0]))
    {
        $octet = array_reverse($octet);
    }

    $priv1 = (!empty($octet[0]) && ($octet[0] != 10)) ? 1 : 0;
    $priv2 = ($octet[0] != 172) || ($octet[1] < 16) || ($octet[1] > 31) ? 1 : 0;
    $priv3 = ($octet[0] != 192) || ($octet[1] < 168) ? 1 : 0;
    if($priv1 && $priv2 && $priv3 !=0 )
    {
        return implode('.',$octet);
    }
    else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        return $_SERVER['REMOTE_ADDR'];
    }unset($octet,$refer);
}

function getRealIP() {
    $headers = array ('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_VIA', 'HTTP_X_COMING_FROM', 'HTTP_COMING_FROM', 'HTTP_CLIENT_IP' );

    foreach ( $headers as $header ) {
        if (isset ( $_SERVER [$header]  )) {
        
            if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
                $ip = substr ( $_SERVER [$header], 0, $pos );
            } else {
                $ip = $_SERVER [$header];
            }
            $ipnum = ip2long ( $ip );
            if ($ipnum !== - 1 && $ipnum !== false && (long2ip ( $ipnum ) === $ip)) {
                if (($ipnum - 184549375) && // Not in 10.0.0.0/8
                ($ipnum  - 1407188993) && // Not in 172.16.0.0/12
                ($ipnum  - 1062666241)) // Not in 192.168.0.0/16
                if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
                    $ip = substr ( $_SERVER [$header], 0, $pos );
                } else {
                    $ip = $_SERVER [$header];
                }
                return $ip;
            }
        }
        
    }
    return $_SERVER ['REMOTE_ADDR'];
}


?>
