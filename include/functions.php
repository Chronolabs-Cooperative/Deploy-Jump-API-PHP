<?php
/**
 * Email Account Propogation REST Services API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         emails-api
 * @since           1.1.11
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         1.1.11
 * @description		A REST API for the creation and management of emails/forwarders and domain name parks for email
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/Emails-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 * 
 */



if (!function_exists("getAuthKey")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getAuthKey($username, $password, $format = 'json')
    {
        $return = array();
        $sql = "SELECT `uid`, `email`, `last_login` FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `uname` LIKE '$username' AND (`pass` LIKE '$password' OR `pass` LIKE MD5('$password'))";
        list($uid, $email, $last_login) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($uid != $last_login && $uid <> 0)
        {
            $time = time();
            if ($last_login < $time - 3600) {
                $GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('users') . "` SET `last_login` = '$time', `hits` = `hits` + 1, `actkey` = '" . substr(md5(mt_rand(-time(), time())), 32 - ($len = mt_rand(3,6)), $len) . "' WHERE `uid` = '$uid'");
                $last_login = $time;
            }
            $sql = "SELECT md5(concat(`uid`, `uname`, `email`, `last_login`, `actkey`)) FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `uid` = '$uid'";
            list($authkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            $_SESSION['authkey'] = $authkey;
            $_SESSION['credentials']['username'] = $username;
            $_SESSION['credentials']['password'] = $password;
            $_SESSION['credentials']['uid'] = $uid;
            $_SESSION['credentials']['email'] = $email;
            setcookie('authkey', $_SESSION['authkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 201, 'authkey' => $_SESSION['authkey'], 'errors' => array());
        } else {
            $_SESSION['authkey'] = md5(NULL);
            setcookie('authkey', $_SESSION['authkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'authkey' => $_SESSION['authkey'] = md5(NULL), 'errors' => array('101' => 'Username and/Or Password Mismatch'));
        }
        return $return;
    }
}

if (!function_exists("getDomainID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getDomainID($domainkey = '')
    {
        $sql = "SELECT `id` FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE '$domainkey' LIKE md5(concat(`id`, '".API_URL."', 'domain'))";
        list($id) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($id <> 0)
        {
            return $id;
        } else {
            $_SESSION['domainkey'] = md5(NULL.'domain');
            setcookie('domainkey', $_SESSION['domainkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('102' => 'Domain Key is not valid!'));
        }
        return $return;
    }
}

if (!function_exists("getRecordID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getRecordID($recordkey = '')
    {
        $sql = "SELECT `id` FROM `records` WHERE '$recordkey' LIKE md5(concat(`id`, '".API_URL."', 'record'))";
        list($id) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($id <> 0)
        {
            return $id;
        } else {
            $_SESSION['recordkey'] = md5(NULL.'record');
            setcookie('recordkey', $_SESSION['recordkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('104' => 'Record Key is not valid!'));
        }
        return $return;
    }
}


if (!function_exists("getSupermasterID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getSupermasterID($masterkey = '')
    {
        $sql = "SELECT `id` FROM `supermasters` WHERE '$masterkey' LIKE md5(concat(`id`, '".API_URL."', 'supermaster'))";
        list($id) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($id <> 0)
        {
            return $id;
        } else {
            $_SESSION['masterkey'] = md5(NULL.'supermaster');
            setcookie('masterkey', $_SESSION['masterkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('105' => 'Supermaster Key is not valid!'));
        }
        return $return;
    }
}

if (!function_exists("getUserID")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getUserID($userkey = '')
    {
        $sql = "SELECT `uid` FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE '$userkey' LIKE md5(concat(`uid`, '".API_URL."', 'user'))";
        list($uid) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($uid <> 0)
        {
            return $uid;
        } else {
            $_SESSION['userkey'] = md5(NULL.'user');
            setcookie('userkey', $_SESSION['userkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('105' => 'User Key is not valid!'));
        }
        return $return;
    }
}

if (!function_exists("getHostnames")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getHostnames($userkey = '')
    {
        $sql = "SELECT concat(`sub-domain`, '.', `hostname`) as `hostname` FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE `apache2-configured` <> 0";
        $result = $GLOBALS['APIDB']->queryF($sql);
        $hostnames = array();
        while($row = $GLOBALS['APIDB']->fetchArray($result))
            $hostnames[$row['hostname']] = $row['hostname'];
        sort($hostnames, SORT_ASC);
        return $hostnames;
    }
}

if (!function_exists("checkAuthKey")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function checkAuthKey($authkey = '')
    {
        $sql = "SELECT `uid`, `uname` FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE '$authkey' LIKE md5(concat(`uid`, `uname`, `email`, `last_login`, `actkey`))";
        list($uid, $uname) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($uid <> 0 && !empty($uname))
        {
            $GLOBALS['account'] = $uname;
            $GLOBALS['uid'] = $uid;
            $time = time();
            $GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('users') . "` SET `last_online` = '$time', `hits` = `hits` + 1 WHERE `uid` = '$uid'");
            $return = array();
        } else {
            $_SESSION['authkey'] = md5(NULL);
            setcookie('authkey', $_SESSION['authkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
            $return = array('code' => 501, 'errors' => array('102' => 'AuthKey is not valid!'));
        }
        return $return;
    }
}

if (!function_exists("addDomains")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function addDomains($authkey, $domain = '', $adminemail = '', $format = 'json')
    {
        $nameservices = explode("\n", getURIData(API_ZONES_API_URL . DS . 'include' . DS . 'data' . DS . 'name-servers.diz'));
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $nstarget = $targets = array();
            foreach(dns_get_record($domain, DNS_NS) as $ns)
                $nstarget[] = $ns['target'];
                    
            foreach($nameservices as $key => $nameservice)
                if (empty($nameservice))
                    unset($nameservices[$key]);
                elseif (in_array($nameservice, $nstarget)) {
                    $parts = explode('.', $nameservice);
                    $targets['ns'][$parts[0] . '.' . $domain] = $nameservice;
                    unset($nameservices[$key]);
                }
            
            foreach(dns_get_record(getBaseDomain(API_URL), DNS_SOA) as $ns) {
                $parts = explode(" ", $record['target']);
                unset($parts[0]);
                unset($parts[1]);
                $targets['soa'][$domain] = sprintf(getURIData(API_ZONES_API_URL . DS . 'include' . DS . 'data' . DS . 'soa-record.diz'), implode(" ", $parts));
            }
            
            if (count($nameservices)>0) 
                return array('code' => 501, 'errors' => array('403' => "Name Service on Domain: $domain must include services: " . implode(', ', $nameservices)));
            
            if (!checkEmail($adminemail))
                return array('code' => 501, 'errors' => array('401' => "Admin Email isn't a valid address: $adminemail"));
            
            $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE (`domain` LIKE '$domain')";
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count==0)
            {
                
                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('domains') . "` (`domain`, `admin-email`, `api-uid`, `created`) VALUES ('$domain', '$adminemail', '" . $GLOBALS['uid'] . "', UNIX_TIMESTAMP())";
                if ($GLOBALS['APIDB']->queryF($sql))
                {
                    $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'domain')) FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `id` = '".$GLOBALS['APIDB']->getInsertId()."'";
                    list($domainkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                    $_SESSION['domainkey'] = $domainkey;
                    setcookie('domainkey', $_SESSION['masterkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
                    
                    define('API_DOMAIN', $domain);
                    define('API_AUTHKEY', '%apiurl/v1/authkey.api');
                    define('API_DOMAINADD', '%apiurl/v1/%authkey/domains.api');
                    define('API_DOMAINKEYS', '%apiurl/v1/%authkey/domains/json.api');
                    define('API_DNSRECORDS', '%apiurl/v1/%authkey/%domainkey/zones/json.api');
                    define('API_ADDRECORD', '%apiurl/v1/%authkey/zones.api');
                    define('API_MYIPADDY', 'https://vcf5.sourceforge.io/myip/index.php?version=1&mode=allmyip&format=json');
                    define('API_NSTYPE', 'NS');
                    define('API_SOATYPE', 'SOA');
                    define('API_MXTYPE', 'MX');
                    
                    $ips = json_decode(getURIData(API_MYIPADDY), true);
                    $authkey = json_decode(getURIData(str_replace('%apiurl', API_ZONES_API_URL, API_AUTHKEY), 25, 25, array('username' => $_SESSION['credentials']['username'], 'password' => $_SESSION['credentials']['password'], 'format' => 'json')), true);
                    $domains = json_decode(getURIData(str_replace('%apiurl', API_ZONES_API_URL, str_replace('%authkey', $authkey['authkey'], API_DOMAINKEYS))), true);
            
                    $targets['mx'][$domain] = $ips['ipv4'][0]['netbios'];
                    
                    if (isset($domains['domains']) && is_array($domains['domains']))
                        foreach($domains['domains'] as $domain) {
                            if ($domain['name'] == API_DOMAIN || $domain['master'] == API_DOMAIN) {
                                if (!defined("API_DOMAINKEY"))
                                    define("API_DOMAINKEY", $domain['domainkey']);
                            }
                        }
                    
                    if (!defined("API_DOMAINKEY")) {
                        $json = json_decode(getURIData(str_replace('%apiurl', API_ZONES_API_URL, str_replace('%authkey', $authkey['authkey'], API_DOMAINADD)), 180, 180, array('format'=>'json', 'name' => $domain, 'type' => 'NATIVE', 'mode' => 'newdomain')), true);
                        if (isset($json['domainkey']) && !empty($json['domainkey']))
                            define("API_DOMAINKEY", $json['domainkey']);
                    }
                    if (defined("API_DOMAINKEY")) {
                        $records = json_decode(getURIData(str_replace('%apiurl', API_ZONES_API_URL, str_replace('%authkey', $authkey['authkey'], str_replace('%domainkey', API_DOMAINKEY, API_DNSRECORDS)))), true);
                        if (isset($records['zones']) && is_array($records['zones']))
                            foreach($records['zones'] as $record) {
                                if (in_array($record['name'], array_keys($targets['ns'])) && $record['type'] == API_NSTYPE) {
                                    unset($targets['ns'][$record['name']]);
                                } elseif ($record['type'] == API_SOATYPE && in_array($record['target'], $targets['soa'])) {
                                    unset($targets['soa']);
                                } elseif ($record['type'] == API_MXTYPE && in_array($record['target'], $targets['mx'])) {
                                    unset($targets['mx']);
                                }
                            }
                        
                        foreach($targets['ns'] as $name => $target)
                            @getURIData(str_replace('%apiurl', API_ZONES_API_URL, str_replace('%authkey', $authkey['authkey'], API_ADDRECORD)), 25, 25, array('domain' => API_DOMAINKEY, 'type' => API_NSTYPE, 'name' => $name, 'content'=>$target, 'ttl' => 6000, 'prio' => 5, 'format' => 'json'));
                        foreach($targets['soa'] as $name => $target)
                            @getURIData(str_replace('%apiurl', API_ZONES_API_URL, str_replace('%authkey', $authkey['authkey'], API_ADDRECORD)), 25, 25, array('domain' => API_DOMAINKEY, 'type' => API_SOATYPE, 'name' => $name, 'content'=>$target, 'ttl' => 6000, 'prio' => 5, 'format' => 'json'));
                        foreach($targets['mx'] as $name => $target)
                            @getURIData(str_replace('%apiurl', API_ZONES_API_URL, str_replace('%authkey', $authkey['authkey'], API_ADDRECORD)), 25, 25, array('domain' => API_DOMAINKEY, 'type' => API_MXTYPE, 'name' => $name, 'content'=>$target, 'ttl' => 6000, 'prio' => 1000, 'format' => 'json'));
                    }
                    
                    $return = array('code' => 201, 'domainkey' => $_SESSION['domainkey'], 'errors' => array());
                    
                } else {
                    $return = array('code' => 501, 'domainkey' => md5(NULL. 'domainkey'), 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                }
            } else {
                $return = array('code' => 501, 'domainkey' => md5(NULL. 'domainkey'), 'errors' => array('103' => 'Record Already Exists!!!'));
            }
        }
        return $return;
    }
}


if (!function_exists("createUser")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function createUser($key, $vars)
    {
        if (!checkEmail($vars['email']))
            return array('code' => 501, 'errors' => array('109' => 'e-Mail format isn\'t valid!!!'));
                
        if (!empty($vars['password']) && !empty($vars['vpassword']) && $vars['password'] != $vars['vpassword'])
            return array('code' => 501, 'errors' => array('108' => 'Password & verify password do not match!!!'));
                    
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE (`uname` LIKE '" .$GLOBALS['APIDB']->escape($vars['username']). "')";
        list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
        if ($count==0)
        {
            APICache::write("create-user-$key", $vars, 3600 * 96 * mt_rand(4, 13));
            require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'apimailer.php';
                            
            $mail = new APIMailer(API_LICENSE_EMAIL, API_LICENSE_COMPANY);
            $body = file_get_contents(__DIR__  . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'verify_user_emailtemplate.html' );
            $body = str_replace('%apilogo', API_URL . '/assets/images/logo_350x350.png', $body);
            $body = str_replace('%apiurl', API_URL, $body);
            $body = str_replace('%zoneurl', API_ZONES_API_URL, $body);
            $body = str_replace('%verifyurl', API_URL . '/v1/' . $key . '/verify.api', $body);
            $body = str_replace('%companyname', API_LICENSE_COMPANY, $body);
            $body = str_replace('%uname', $var['username'], $body);
            $body = str_replace('%pass', $var['password'], $body);
            $body = str_replace('%email', $var['email'], $body);
            if ($mail->sendMail(array((!empty($var['name'])?$var['name']:$var['email']) => $var['email']), array(), array(), "Confirm: Zone + Short URL Deployment API Creditials", $body, array(), "", true)) {
                $return = array('code' => 201, 'verifykey' => $key, 'errors' => array());
            } else {
                $return = array('code' => 501, 'userkey' => md5(NULL. 'user'), 'errors' => array("smtp" => "Email Address Not Contactable: " . $vars['email']));
            }
        }
        return $return;
    }
}


if (!function_exists("verifyUser")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function verifyUser($key, $vars)
    {
        if ($vars = APICache::read("create-user-$key")) {
            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('users') . "` (`uname`, `email`, `pass`, `name`, `url`) VALUES ('" .$GLOBALS['APIDB']->escape($vars['username']). "', '" .$GLOBALS['APIDB']->escape($vars['email']). "', md5('" .$GLOBALS['APIDB']->escape($vars['password']). "', '" .$GLOBALS['APIDB']->escape($vars['name']). "', '" .$GLOBALS['APIDB']->escape($vars['url']). "'))";
            if ($GLOBALS['APIDB']->queryF($sql))
            {
                $sql = "SELECT md5(concat(`uid`, '" . API_URL . "', 'user')) FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `uid` = '".$GLOBALS['APIDB']->getInsertId()."'";
                list($userkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                $_SESSION['userkey'] = $userkey;
                setcookie('userkey', $_SESSION['userkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
                $return = array('code' => 201, 'userkey' => array(parse_url(API_URL, PHP_URL_HOST) => $_SESSION['userkey']), 'errors' => array());
                
                $authkey = json_decode(getURIData(API_ZONES_API_URL, 25, 25, array('username' => API_ZONES_USERNAME_URL, 'password' => API_ZONES_PASSWORD_URL, 'format' => 'json')), true);
                $json = json_decode(getURIData(sprintf(API_ZONES_API_URL . "/v1/%s/users/json.api", $authkey['authkey']), 180, 180, array_merge(array('uname' => $vars['username'], 'email' => $vars['email'], 'pass' => $vars['password'], 'vpass' => $vars['vpassword'], 'format' => 'json'), $vars)), true);
                if (!empty($json['userkey']))
                    $return['userkey'][parse_url(API_ZONES_API_URL, PHP_URL_HOST)] = $json['userkey'];
                APICache::delete("create-user-$key");
            }
        } else 
            return array('code' => 501, 'userkey' => array(), 'errors' => array(404 => "User Verififcation Key Not Found: $key"));
        return $return;
    }
}

if (!function_exists("addUser")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function addUser($authkey, $uname, $email = '', $pass = '', $vpass = '', $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        { 
            if (!checkEmail($email))
                return array('code' => 501, 'errors' => array('109' => 'e-Mail format isn\'t valid!!!'));
            
            if (!empty($pass) && !empty($vpass) && $pass != $vpass)
                return array('code' => 501, 'errors' => array('108' => 'Password & verify password do not match!!!'));
            
            $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE (`uname` LIKE '" .$GLOBALS['APIDB']->escape($uname). "') OR (`uname` LIKE '" .$GLOBALS['APIDB']->escape($uname). "' AND `email` LIKE '" .$GLOBALS['APIDB']->escape($email). "')";
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count==0)
            {
                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('users') . "` (`uname`, `email`, `pass`) VALUES ('" .$GLOBALS['APIDB']->escape($uname). "', '" .$GLOBALS['APIDB']->escape($email). "', md5('" .$GLOBALS['APIDB']->escape($pass). "'))";
                if ($GLOBALS['APIDB']->queryF($sql))
                {
                    $sql = "SELECT md5(concat(`uid`, '" . API_URL . "', 'user')) FROM `" . $GLOBALS['APIDB']->prefix('users') . "` WHERE `uid` = '".$GLOBALS['APIDB']->getInsertId()."'";
                    list($userkey) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                    $_SESSION['userkey'] = $userkey;
                    setcookie('userkey', $_SESSION['userkey'], 3600 + $time, '/', API_COOKIE_DOMAIN);
                    $return = array('code' => 201, 'userkey' => $_SESSION['userkey'], 'errors' => array());
                    
                    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'apimailer.php';
                    
                    $mail = new APIMailer(API_LICENSE_EMAIL, API_LICENSE_COMPANY);
                    $body = file_get_contents(__DIR__  . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'new_user_emailtemplate.html' );
                    $body = str_replace('%apilogo', API_URL . '/assets/images/logo_350x350.png', $body);
                    $body = str_replace('%apiurl', API_URL, $body);
                    $body = str_replace('%companyname', API_LICENSE_COMPANY, $body);
                    $body = str_replace('%account', $GLOBALS['account'], $body);
                    $body = str_replace('%uname', $uname, $body);
                    $body = str_replace('%pass', $pass, $body);
                    $body = str_replace('%email', $email, $body);
                    $mail->sendMail($email, array(), array(), "Zone API Creditials as established by: " . $GLOBALS['account'], $body, array(), "", true);
                    
                } else {
                    $return = array('code' => 501, 'userkey' => md5(NULL. 'user'), 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                }
            } else {
                $return = array('code' => 501, 'userkey' => md5(NULL. 'user'), 'errors' => array('107' => 'User Record Already Exists!!!'));
            }
        }
        return $return;
    }
}


if (!function_exists("editRecord")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function editRecord($table, $authkey, $id, $vars = array(), $fields = array(), $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            if (!empty($id) && is_array($id))
                return $id;
            
            foreach($vars as $key => $value)
                if (!in_array($key, $fields))
                    unset($vars[$key]);
            
            if (count($vars) == 0)
                return array('code' => 501, 'errors' => array('110' => 'No records fields specified for edit this supports: '.implode(', ', $fields).'!!!'));
            switch ($table)
            {
                case 'users':
                    if (isset($vars['email']) && !empty($vars['email']) && !checkEmail($vars['email']))
                        return array('code' => 501, 'errors' => array('109' => 'e-Mail format isn\'t valid!!!'));
                    if (!empty($vars['pass']) && !empty($vars['vpass']) && $vars['pass'] != $vars['vpass'])
                        return array('code' => 501, 'errors' => array('108' => 'Password & verify password do not match!!!'));
                    elseif (!empty($vars['pass']) && !empty($vars['vpass']) && $vars['pass'] == $vars['vpass']) {
                        $vars['pass'] = md5($vars['pass']);
                        unset($vars['vpass']);
                    } else {
                        unset($vars['pass']);
                        unset($vars['vpass']);
                    }
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `uid` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`uname` LIKE '" .$GLOBALS['APIDB']->escape($vars['uname']). "') OR (`email` LIKE '" .$GLOBALS['APIDB']->escape($vars['email']). "'))";
                    break;
                case 'records':
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `id` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`name` LIKE '" .$GLOBALS['APIDB']->escape($vars['name']). "' AND `content` LIKE '" .$GLOBALS['APIDB']->escape($vars['content']). "' AND `type` LIKE '" . $old['type'] . "'))";
                    break;
                case 'domains':
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `id` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`name` LIKE '" .$GLOBALS['APIDB']->escape($vars['name']). "' AND `type` LIKE '" . $vars['type'] . "') OR (`master` LIKE '" .$GLOBALS['APIDB']->escape($vars['master']). "' AND `type` LIKE '" . $vars['type'] . "'))";
                    break;
                case 'supermasters':
                    $old = $GLOBALS["APIDB"]->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `$table` WHERE `id` = '$id'"));
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE (`ip` LIKE '" .$GLOBALS['APIDB']->escape($vars['ip']). "' AND `nameserver` LIKE '" .$GLOBALS['APIDB']->escape($vars['nameserver']). "'))";
                    break;
            }
            list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
            if ($count==0)
            {
                $sql = "UPDATE `$table` SET ";
                $u=0;
                foreach($vars as $key => $value)
                {
                    $u++;
                    $sql .= "`$key` = '" . $GLOBALS['APIDB']->escape($value) . ($u < count($vars)?"', ":"' ");
                }
                switch ($table)
                {
                    case 'users':
                        $sql .= "WHERE `uid` = '$id'";
                        break;
                    default:
                        $sql .= "WHERE `id` = '$id'";
                        break;
                }
                if ($GLOBALS['APIDB']->queryF($sql))
                {
                    $return = array('code' => 201, 'affected' =>$GLOBALS['APIDB']->getAffectedRows(), 'errors' => array());
                } else {
                    $return = array('code' => 501, 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
                }
            } else {
                $return = array('code' => 501, 'errors' => array('107' => 'User Record Already Exists!!!'));
            }
        }
        return $return;
    }
}

if (!function_exists("deleteRecord")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function deleteRecord($table, $authkey, $id)
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            if (!empty($id) && is_array($id))
                return $id;
            
            $sql = "DELETE FROM `$table` ";
            switch ($table)
            {
                case 'users':
                    $sql .= "WHERE `uid` = '$id'";
                    break;
                default:
                    $sql .= "WHERE `id` = '$id'";
                    break;
            }
            if ($GLOBALS['APIDB']->queryF($sql))
            {
                $return = array('code' => 201, 'affected' =>$GLOBALS['APIDB']->getAffectedRows(), 'errors' => array());
            } else {
                $return = array('code' => 501, 'errors' => array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()));
            }
        } else {
            $return = array('code' => 501, 'errors' => array('107' => 'User Record Already Exists!!!'));
        }
        return $return;
    }
}

if (!function_exists("getDomains")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getDomains($authkey, $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $return['code'] = 201;
            $sql = "SELECT md5(concat(`id`, '" . API_URL . "', 'domain')) as `domainkey`, `admin-email`, `domain`, `ssl` FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` ORDER BY `domain` ASC, `ssl` ASC, `type` DESC";
            $result = $GLOBALS['APIDB']->queryF($sql);
            while($domain = $GLOBALS['APIDB']->fetchArray($result)) {
                $domain['admin-email'] = checkEmail($domain['admin-email'], true);
                $return['domains'][] = $domain;
            }
        }
        return $return;
    }
}

if (!function_exists("getUsers")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function getUsers($authkey, $format = 'json')
    {
        $return = checkAuthKey($authkey);
        if (empty($return))
        {
            $return['code'] = 201;
            $sql = "SELECT md5(concat(`uid`, '" . API_URL . "', 'user')) as `userkey`, `uname`, `email`, `hits`, `last_online`, `last_login` FROM `" . $GLOBALS['APIDB']->prefix('users') . "` ORDER BY `uname` ASC, `email` ASC, `hits` DESC";
            $result = $GLOBALS['APIDB']->queryF($sql);
            while($user = $GLOBALS['APIDB']->fetchArray($result)) {
                $user['email'] = checkEmail($user['email'], true);
                $return['users'][] = $user;
            }
        }
        return $return;
    }
}


if (!function_exists("checkEmail")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function checkEmail($email, $antispam = false)
    {
        if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            return false;
        }
        $email_array      = explode('@', $email);
        $local_array      = explode('.', $email_array[0]);
        $local_arrayCount = count($local_array);
        for ($i = 0; $i < $local_arrayCount; ++$i) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode('.', $email_array[1]);
            if (count($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < count($domain_array); ++$i) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        if ($antispam) {
            $email = str_replace('@', ' at ', $email);
            $email = str_replace('.', ' dot ', $email);
        }
        
        return $email;
    }
}

if (!function_exists("writeRawFile")) {
    /**
     *
     * @param string $file
     * @param string $data
     */
    function writeRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            mkdir(dirname($file), 0777, true);
            if (is_file($file))
                unlink($file);
                $data = str_replace("\n", $lineBreak, $data);
                $ff = fopen($file, 'w');
                fwrite($ff, $data, strlen($data));
                fclose($ff);
    }
}

if (!function_exists("getCompleteFilesListAsArray")) {
	function getCompleteFilesListAsArray($dirname, $result = array())
	{
		foreach(getCompleteDirListAsArray($dirname) as $path)
			foreach(getFileListAsArray($path) as $file)
				$result[$path.DIRECTORY_SEPARATOR.$file] = $path.DIRECTORY_SEPARATOR.$file;
				return $result;
	}

}


if (!function_exists("getCompleteDirListAsArray")) {
	function getCompleteDirListAsArray($dirname, $result = array())
	{
		$result[$dirname] = $dirname;
		foreach(getDirListAsArray($dirname) as $path)
		{
			$result[$dirname . DIRECTORY_SEPARATOR . $path] = $dirname . DIRECTORY_SEPARATOR . $path;
			$result = getCompleteDirListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
		}
		return $result;
	}

}

if (!function_exists("getCompleteHistoryListAsArray")) {
	function getCompleteHistoryListAsArray($dirname, $result = array())
	{
		foreach(getCompleteDirListAsArray($dirname) as $path)
		{
			foreach(getHistoryListAsArray($path) as $file=>$values)
				$result[$path][sha1_file($path . DIRECTORY_SEPARATOR . $values['file'])] = array_merge(array('fullpath'=>$path . DIRECTORY_SEPARATOR . $values['file']), $values);
		}
		return $result;
	}
}

if (!function_exists("getDirListAsArray")) {
	function getDirListAsArray($dirname)
	{
		$ignored = array(
				'cvs' ,
				'_darcs');
		$list = array();
		if (substr($dirname, - 1) != '/') {
			$dirname .= '/';
		}
		if ($handle = opendir($dirname)) {
			while ($file = readdir($handle)) {
				if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
					continue;
					if (is_dir($dirname . $file)) {
						$list[$file] = $file;
					}
			}
			closedir($handle);
			asort($list);
			reset($list);
		}

		return $list;
	}
}

if (!function_exists("getFileListAsArray")) {
	function getFileListAsArray($dirname, $prefix = '')
	{
		$filelist = array();
		if (substr($dirname, - 1) == '/') {
			$dirname = substr($dirname, 0, - 1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
					$file = $prefix . $file;
					$filelist[$file] = $file;
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}

		return $filelist;
	}
}

if (!function_exists("getHistoryListAsArray")) {
	function getHistoryListAsArray($dirname, $prefix = '')
	{
		$formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'history-formats.diz'));
		$filelist = array();

		if ($handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				foreach($formats as $format)
					if (substr(strtolower($file), strlen($file)-strlen(".".$format)) == strtolower(".".$format)) {
						$file = $prefix . $file;
						$filelist[$file] = array('file'=>$file, 'type'=>$format, 'sha1' => sha1_file($dirname . DIRECTORY_SEPARATOR . $file));
					}
			}
			closedir($handle);
		}
		return $filelist;
	}
}


if (!function_exists("cleanWhitespaces")) {
	/**
	 *
	 * @param array $array
	 */
	function cleanWhitespaces($array = array())
	{
		foreach($array as $key => $value)
		{
			if (is_array($value))
				$array[$key] = cleanWhitespaces($value);
				else {
					$array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
				}
		}
		return $array;
	}
}


if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIPAddy()
	 * 
	 * 	provides an associative array of whitelisted IP Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @return 		array
	 */
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/* function whitelistGetNetBIOSIP()
	 *
	 * 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		array
	 */
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		} 
		return $ret;
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIP()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @param		boolean		$asString	Whether to return an address or network long integer
	 * 
	 * @return 		mixed
	 */
	function whitelistGetIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}


if (!function_exists("getURIData")) {
    
    /* function yonkURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function getURIData($uri = '', $timeout = 25, $connectout = 25, $post = array(), $headers = array())
    {
        if (!function_exists("curl_init"))
        {
            die("Install PHP Curl Extension ie: $ sudo apt-get install php-curl -y");
        }
        $GLOBALS['php-curl'][md5($uri)] = array();
        if (!$btt = curl_init($uri)) {
            return false;
        }
        if (count($post)==0 || empty($post))
            curl_setopt($btt, CURLOPT_POST, false);
            else {
                $uploadfile = false;
                foreach($post as $field => $value)
                    if (substr($value , 0, 1) == '@' && !file_exists(substr($value , 1, strlen($value) - 1)))
                        unset($post[$field]);
                    else
                        $uploadfile = true;
                curl_setopt($btt, CURLOPT_POST, true);
                curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post));
                
                if (!empty($headers))
                    foreach($headers as $key => $value)
                        if ($uploadfile==true && substr($value, 0, strlen('Content-Type:')) == 'Content-Type:')
                            unset($headers[$key]);
                if ($uploadfile==true)
                    $headers[]  = 'Content-Type: multipart/form-data';
            }
            if (count($headers)==0 || empty($headers))
                curl_setopt($btt, CURLOPT_HEADER, false);
                else {
                    curl_setopt($btt, CURLOPT_HEADER, true);
                    curl_setopt($btt, CURLOPT_HTTPHEADER, $headers);
                }
                curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
                curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($btt, CURLOPT_VERBOSE, false);
                curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
                $data = curl_exec($btt);
                $GLOBALS['php-curl'][md5($uri)]['http']['posts'] = $post;
                $GLOBALS['php-curl'][md5($uri)]['http']['headers'] = $headers;
                $GLOBALS['php-curl'][md5($uri)]['http']['code'] = curl_getinfo($btt, CURLINFO_HTTP_CODE);
                $GLOBALS['php-curl'][md5($uri)]['header']['size'] = curl_getinfo($btt, CURLINFO_HEADER_SIZE);
                $GLOBALS['php-curl'][md5($uri)]['header']['value'] = curl_getinfo($btt, CURLINFO_HEADER_OUT);
                $GLOBALS['php-curl'][md5($uri)]['size']['download'] = curl_getinfo($btt, CURLINFO_SIZE_DOWNLOAD);
                $GLOBALS['php-curl'][md5($uri)]['size']['upload'] = curl_getinfo($btt, CURLINFO_SIZE_UPLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['length']['download'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['length']['upload'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_UPLOAD);
                $GLOBALS['php-curl'][md5($uri)]['content']['type'] = curl_getinfo($btt, CURLINFO_CONTENT_TYPE);
                curl_close($btt);
                return $data;
    }
}

if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 * 
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {
	
		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 * 
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {
	
			$domElement = is_null($domElement) ? $this : $domElement;
	
			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {
	
					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}
					 
					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}
					 
					$this->fromMixed($mixedElement, $node);
					 
				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}
			 
		}
		 
	}
}


function getHTMLForm($mode = '', $authkey = '')
{
    if (empty($authkey) && isset($_COOKIE['authkey']))
        $authkey = $_COOKIE['authkey'];
    elseif (empty($authkey) && isset($_SESSION['authkey']))
        $authkey = $_SESSION['authkey'];
    elseif (empty($authkey)) 
        $authkey = md5(NULL);
    
    $form = array();
    switch ($mode)
    {
        case "createuser":
            $form[] = "<form name='create-user' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/createuser.api">';
            $form[] = "\t<table class='auth-key' id='auth-key' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='username'>Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='username' id='username' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='email'>Email:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='email' id='email' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='name'>Organisation/Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='name' id='name' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='url'>URL:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='url' id='url' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='password'>Password:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='password' name='password' id='password' size='41' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='vpassword'>Verify Password:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='vpassword' name='vpassword' id='vpassword' size='41' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='createuser' name='mode'>";
            $form[] = "\t\t\t\t<input type='submit' value='Create User' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
        case "authkey":
            $form[] = "<form name='auth-key' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/authkey.api">';
            $form[] = "\t<table class='auth-key' id='auth-key' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='username'>Username:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='username' id='username' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='password'>Password:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='password' name='password' id='password' size='41' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='authkey' name='mode'>";
            $form[] = "\t\t\t\t<input type='submit' value='Get URL Auth-key' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
        case "newdomain":
            $form[] = "<form name='new-domain' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/' . $authkey . '/domains.api">';
            $form[] = "\t<table class='new-domain' id='auth-domain' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='domain'>Domain Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='domain' id='domain' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='adminemail'>Admin Email:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='adminemail' id='adminemail' size='41' value='" . (isset($_SESSION['credentials']['email'])?$_SESSION['credentials']['email']:API_LICENSE_EMAIL) ."' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='newdomain' name='newdomain'>";
            $form[] = "\t\t\t\t<input type='submit' value='Create New Domain' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
        case "newsupermaster":
            $form[] = "<form name='new-supermaster' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/' . $authkey . '/supermaster.api">';
            $form[] = "\t<table class='new-supermaster' id='auth-supermaster' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='ip'>IP:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='ip' id='ip' size='41' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='nameserver'>Name Server:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='nameserver' id='nameserver' size='41' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='newsupermaster' name='mode'>";
            $form[] = "\t\t\t\t<input type='submit' value='Create New Supermaster' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
            
        case "newrecord":
            $form[] = "<form name='new-record' method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v1/' . $authkey . '/zones.api">';
            $form[] = "\t<table class='new-record' id='auth-record' style='vertical-align: top !important; min-width: 98%;'>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='domain'>Domain:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='domain' id='format'/>";
            $result = $GLOBALS['APIDB']->queryF("SELECT md5(concat(`id`, '" . API_URL . "', 'domain')) as `key`, `name`, `master` FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` ORDER BY `name` ASC, `master` ASC");
            while($row = $GLOBALS['APIDB']->fetchArray($result))
                $form[] = "\t\t\t\t\t<option value='".$row['key']."'>".(isset($row['name'])?$row['name']:$row['master'])."</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='type'>Record Type:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='type' id='type'/>";
            foreach(array('A', 'AAAA', 'AFSDB', 'ALIAS', 'CAA', 'CERT', 'CDNSKEY', 'CDS', 'CNAME', 'DNSKEY', 'DNAME', 'DS', 'HINFO', 'KEY', 'LOC', 'MX', 'NAPTR', 'NS', 'NSEC', 'NSEC3', 'NSEC3PARAM', 'OPENPGPKEY', 'PTR', 'RP', 'RRSIG', 'SOA', 'SPF', 'SSHFP', 'SRV', 'TKEY', 'TSIG', 'TLSA', 'SMIMEA', 'TXT', 'URI') as $type)
                $form[] = "\t\t\t\t\t<option value='".$type."'>". $type." Record</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='name'>Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='name' id='name' size='41' maxlen='255'/>&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='content'>Record:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<textarea name='content' id='content' col='41' row='7'></textarea><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='ttl'>Time-to-live:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='textbox' name='ttl' id='ttl' value='6000' size='8' />&nbsp;&nbsp;";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<label for='prio'>Pirority:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<input type='content' name='prio' id='prio' value='0' size='8' /><br/>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td>";
            $form[] = "\t\t\t\t<label for='format'>Output Format:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td style='width: 320px;'>";
            $form[] = "\t\t\t\t<select name='format' id='format'/>";
            $form[] = "\t\t\t\t\t<option value='raw'>RAW PHP Output</option>";
            $form[] = "\t\t\t\t\t<option value='json' selected='selected'>JSON Output</option>";
            $form[] = "\t\t\t\t\t<option value='serial'>Serialisation Output</option>";
            $form[] = "\t\t\t\t\t<option value='xml'>XML Output</option>";
            $form[] = "\t\t\t\t</select>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t\t<td>&nbsp;</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
            $form[] = "\t\t\t\t<input type='hidden' value='newzone' name='mode'>";
            $form[] = "\t\t\t\t<input type='submit' value='Create New Zone' name='submit' style='padding:11px; font-size:122%;'>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
            $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
            $form[] = "\t\t\t</td>";
            $form[] = "\t\t</tr>";
            $form[] = "\t\t<tr>";
            $form[] = "\t</table>";
            $form[] = "</form>";
            break;
        }
    return implode("\n", $form);
           
}


if (!function_exists("getBaseDomain")) {
    /**
     * Gets the base domain of a tld with subdomains, that is the root domain header for the network rout
     *
     * @param string $url
     *
     * @return string
     */
    function getBaseDomain($uri = '')
    {
        
        if (!defined('API_STRATA_API_URL'))
            define('API_STRATA_API_URL', 'http://strata.snails.email');
            
        static $fallout, $strata, $classes;
        
        if (empty($classes))
        {
            
            $attempts = 0;
            $attempts++;
            $classes = array_keys(json_decode(getURIData(API_STRATA_API_URL ."/v1/strata/json.api", 150, 100), true));
            
        }
        if (empty($fallout))
        {
            $fallout = array_keys(json_decode(getURIData(API_STRATA_API_URL ."/v1/fallout/json.api", 150, 100), true));
        }
        
        // Get Full Hostname
        $uri = strtolower($uri);
        $hostname = parse_url($uri, PHP_URL_HOST);
        if (!filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 || FILTER_FLAG_IPV4) === false)
            return $hostname;
            
        // break up domain, reverse
        $elements = explode('.', $hostname);
        $elements = array_reverse($elements);
        
        // Returns Base Domain
        if (in_array($elements[0], $classes))
            return $elements[1] . '.' . $elements[0];
        elseif (in_array($elements[0], $fallout) && in_array($elements[1], $classes))
            return $elements[2] . '.' . $elements[1] . '.' . $elements[0];
        elseif (in_array($elements[0], $fallout))
            return  $elements[1] . '.' . $elements[0];
        else
            return  $elements[1] . '.' . $elements[0];
    }
}


