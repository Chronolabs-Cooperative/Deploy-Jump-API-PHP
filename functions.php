<?php
/**
 * Chronolabs REST Short Link URIs API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://au.syd.labs.coop
 * @license         Academic + GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         api
 * @since           2.2.1
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @version         2.2.1
 * @subpackage		shortening-url
 * @description		Short Link URIs API
 * @link			http://internetfounder.wordpress.com
 * @link			http://sourceoforge.net/projects/chronolabsapis/files/jump.labs.coop
 * @link			https://github.com/Chronolabs-Cooperative/Jump-API-PHP
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'xcp' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'xcp.class.php';

if (!function_exists('encode_sef'))
{
    
    /**
     * Xoops safe encoded url elements
     *
     * @param unknown $datab
     * @param string $char
     * @return string
     */
    function encode_sef($datab, $char ='-')
    {
        $replacement_chars = array();
        $rejected = array(" ",".",",","<",">","/","?","'","\"",";",":","{","}","[","]","|","\\","=",
            "+","_","(",")","*","&","^","%","$","#","@","!","`","~",NULL);
        $return_data = (str_replace($rejected,$char,$datab));
        while(substr($return_data, 0, 1) == $char)
            $return_data = substr($return_data, 1, strlen($return_data)-1);
        while(substr($return_data, strlen($return_data)-1, 1) == $char)
            $return_data = substr($return_data, 0, strlen($return_data)-1);
        while(strpos($return_data, $char . $char))
            $return_data = str_replace($char . $char, $char, $return_data);
        return(strtolower($return_data));
    }
}
if (!function_exists("checkDisplayHelp")) {

	/**
	 * checkDisplayHelp ~ checks if help will need to be displayed
	 * 
	 * @param string $action
	 * @return boolean
	 */
	function checkDisplayHelp($action = '')
	{
		global $errors;
		apiLoadLanguage('errors', _API_LANGUAGE_DEFAULT);
		$errors = array();
		if (!empty($action))
			checkFunctionRequirements(basename(__DIR__), $action);
		return (!empty($errors)?false:true);
	}
}


if (!function_exists("checkFunctionRequirements")) {

	/**
	 * checkFunctionRequirements ~ checks the requirements of an API Function for a form
	 * 
	 * @param string $base
	 * @param string $func
	 * @return boolean
	 */
	function checkFunctionRequirements($base = 'salty', $func = '')
	{
		global $errors;
		if (file_exists($file = API_PATH_IO_FUNCTIONS . DIRECTORY_SEPARATOR . "$base-$func.diz"))
		{
			foreach(file($file) as $fields)
			{
				$parts = explode("||", $fields);
				if (isset($parts[2]) && $parts[2] == 'required')
				{
					switch ($parts[1])
					{
						default:
							if (!isset($_REQUEST[$parts[0]]) && empty($_REQUEST[$parts[0]]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_SET, '$_' . sprintf('REQUEST["%s"]', $parts[0]));
							elseif (isset($parts[3]) && checkValidField($_REQUEST[$parts[0]], str_replace(array("\n","\r","\t", " "), "", $parts[3]), str_replace(array("\n","\r","\t", " "), "", (isset($parts[4])?$parts[4]:"")), str_replace(array("\n","\r","\t", " "), "", (isset($parts[5])?$parts[5]:"")), $parts[0]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_VALID, '$_' . sprintf('REQUEST["%s"]', $parts[0]), $errors[$parts[0]]);
							break;
						case "get":
							if (!isset($_GET[$parts[0]]) && empty($_GET[$parts[0]]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_SET, '$_' . sprintf('GET["%s"]', $parts[0]));		
							elseif (isset($parts[3]) && checkValidField($_GET[$parts[0]], str_replace(array("\n","\r","\t", " "), "", $parts[3]), str_replace(array("\n","\r","\t", " "), "", (isset($parts[4])?$parts[4]:"")), str_replace(array("\n","\r","\t", " "), "", (isset($parts[5])?$parts[5]:"")), $parts[0]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_VALID, '$_' . sprintf('GET["%s"]', $parts[0]), $errors[$parts[0]]);
							break;
						case "post":
							if (!isset($_POST[$parts[0]]) && empty($_POST[$parts[0]]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_SET, '$_' . sprintf('POST["%s"]', $parts[0]));		
							elseif (isset($parts[3]) && checkValidField($_POST[$parts[0]], str_replace(array("\n","\r","\t", " "), "", $parts[3]), str_replace(array("\n","\r","\t", " "), "", (isset($parts[4])?$parts[4]:"")), str_replace(array("\n","\r","\t", " "), "", (isset($parts[5])?$parts[5]:"")), $parts[0]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_VALID, '$_' . sprintf('POST["%s"]', $parts[0]), $errors[$parts[0]]);
							break;
							
					}
				} else {
					switch ($parts[1])
					{
						default:
							if (isset($_REQUEST[$parts[0]]) && isset($parts[3]) && checkValidField($_REQUEST[$parts[0]], str_replace(array("\n","\r","\t", " "), "", $parts[3]), str_replace(array("\n","\r","\t", " "), "", (isset($parts[4])?$parts[4]:"")), str_replace(array("\n","\r","\t", " "), "", (isset($parts[5])?$parts[5]:"")), $parts[0]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_VALID, '$_' . sprintf('REQUEST["%s"]', $parts[0]), $errors[$parts[0]]);
							break;
						case "get":
							if (isset($_GET[$parts[0]]) && isset($parts[3]) &&  checkValidField($_GET[$parts[0]], str_replace(array("\n","\r","\t", " "), "", $parts[3]), str_replace(array("\n","\r","\t", " "), "", (isset($parts[4])?$parts[4]:"")), str_replace(array("\n","\r","\t", " "), "", (isset($parts[5])?$parts[5]:"")), $parts[0]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_VALID, '$_' . sprintf('GET["%s"]', $parts[0]), $errors[$parts[0]]);
							break;
						case "post":
							if (isset($_POST[$parts[0]]) && isset($parts[3]) &&  checkValidField($_POST[$parts[0]], str_replace(array("\n","\r","\t", " "), "", $parts[3]), str_replace(array("\n","\r","\t", " "), "", (isset($parts[4])?$parts[4]:"")), str_replace(array("\n","\r","\t", " "), "", (isset($parts[5])?$parts[5]:"")), $parts[0]))
								$errors[$parts[0]] = sprintf(_API_ERROR_FIELD_NOT_VALID, '$_' . sprintf('POST["%s"]', $parts[0]), $errors[$parts[0]]);
							break;
								
					}
				}
				if (empty($errors[$parts[0]]) || is_array($errors[$parts[0]]))
					unset($errors[$parts[0]]);
			}
		}
		return (!empty($errors)?true:false);
	}
}



if (!function_exists("checkValidField")) {

	/**
	 * checkValidField ~ Validates a value of a field for API Form from scipted .diz files
	 * 
	 * @param string $value
	 * @param string $type
	 * @param string $typal
	 * @param string $sizes
	 * @param string $field
	 * @return boolean
	 */
	function checkValidField($value = '', $type = '', $typal = '', $sizes = '', $field = '')
	{
		global $errors;
		$errors[$field] = '';
		$pass = true;
		if (strpos($typal, "-"))
		{
			$parts = explode("-", $typal);
			$minimal = $parts[0];
			$maximum = $parts[1];
		}
		if (strpos($sizes, "-"))
		{
			$parts = explode("-", $sizes);
			$minimal = $parts[0];
			$maximum = $parts[1];
		}
		switch ($type)
		{
			case "enumerator":
				if (!in_array($value, explode("|", $typal)))
					$errors[$field] = sprintf(_API_ERROR_FIELD_NOT_ENUMATOR, $value, "'" . implode("', '", explode("|", $typal)) . "'");
				break;
			case "words":
				if (count($words = explode(" ", $value)))
				{
					if (strpos($typal, "-") != 0)
						if (count($words)>=$minimal || count($words)<=$maximum )	
							$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_WORDS_RANGE, $maximum, count($words), $minimal,  count($words));
					elseif(count($words)<=$typal)
						$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_WORDS_LESS, $typal, count($words));
				}
				break;
			case "string":
				if (!is_string($value))
					$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_STRING, $value);
				elseif (!empty($typal))
				{
					if (strpos($typal, "-") != 0)
						if (strlen($value)>=$minimal || strlen($value)<=$maximum )
							$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_LENGTH_RANGE, $maximum, strlen($value), $minimal,  strlen($value));
					elseif(strlen($value)<=$typal)
						$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_LENGTH_LESS, $typal, strlen($value));
				}
				break;
			case "number":
				if (!is_numeric($value))
					$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_NUMERIC, $value);
				elseif (!empty($typal))
				{
					if (strpos($typal, "-") != 0)
						if ((float)($value)>=(float)$minimal || (float)($value)<=(float)$maximum )
						$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_NUMERIC_RANGE, (float)$maximum, (float)($value), (float)$minimal,  (float)($value));
					elseif((float)($value)>=(float)$typal)
						$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_NUMERIC_GREATER, (float)$typal, (float)($value));
				}
				break;	
			case "email":
				if (!checkEmail($value))
					$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_EMAIL, $value);
				break;
			case "uri":
				if (substr($value,0,4)!="http")
					$errors[$field] =  sprintf(_API_ERROR_FIELD_NOT_, $value);
				break;
		}	
		if (empty($errors[$field]))
			unset($errors[$field]);
		return !isset($errors[$field])?false:true;
	}
}


if (!function_exists("apiLoadLanguage")) {

	/**
	 * apiLoadLanguage ~ loads a language files
	 * 
	 * @param unknown_type $definition
	 * @param unknown_type $language
	 * @return boolean
	 */
	function apiLoadLanguage($definition = 'help', $language = 'english')
	{
		if (!empty($language)) $language = _API_LANGUAGE_DEFAULT;
		if (file_exists($file = __DIR__ . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . "$definition.php"))
		{
			return include_once($file);
		}
		return false;
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



if (!function_exists("readRawFile")) {
	
	/**
	 * Return the contents of this File as a string.
	 *
	 * @param string $file
	 * @param string $bytes where to start
	 * @param string $mode
	 * @param boolean $force If true then the file will be re-opened even if its already opened, otherwise it won't
	 * @return mixed string on success, false on failure
	 * @access public
	 */
	function readRawFile($file = '', $bytes = false, $mode = 'rb', $force = false)
	{
		$success = false;
		if ($bytes === false) {
			$success = file_get_contents($file);
		} elseif ($fhandle = fopen($file, $mode)) {
			if (is_int($bytes)) {
				$success = fread($fhandle, $bytes);
			} else {
				$data = '';
				while (! feof($fhandle)) {
					$data .= fgets($fhandle, 4096);
				}
				$success = trim($data);
			}
			fclose($fhandle);
		}
		return $success;
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
		if (!is_dir(dirname($file)))
			mkdir(dirname($file), 0777, true);
		if (is_file($file))
			unlink($file);
		$ff = fopen($file, 'w');
		fwrite($ff, $data, strlen($data));
		return fclose($ff);
	}
}

if (!function_exists("mkdirSecure")) {
	/**
	 *
	 * @param unknown_type $path
	 * @param unknown_type $perm
	 * @param unknown_type $secure
	 */
	function mkdirSecure($path = '', $perm = 0777, $secure = true)
	{
		if (!is_dir($path))
		{
			mkdir($path, $perm, true);
			if ($secure == true)
			{
				writeRawFile($path . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
			}
			return true;
		}
		return false;
	}
}

if (!function_exists("writeCache")) {
	/**
	 * Write data for key into cache
	 *
	 * @param string $key Identifier for the data
	 * @param mixed $data Data to be cached
	 * @param mixed $duration How long to cache the data, in seconds
	 * @return boolean True if the data was succesfully cached, false on failure
	 * @access public
	 */
	function writeCache($key, $data = array(), $duration = 3600)
	{
		if (!isset($data)) {
			return false;
		}
	
		if (!empty($key))
			$key .= substr(md5($_SERVER["HTTP_HOST"]), 3, 7) . '--' . $key;
		else
			return false;
		
		if ($duration == null) {
			$duration = 3600;
		}
		$windows = false;
		$lineBreak = "\n";
	
		if (substr(PHP_OS, 0, 3) == "WIN") {
			$lineBreak = "\r\n";
			$windows = true;
		}
		$expires = time() + $duration;
		$contents = $expires . $lineBreak . "return " . var_export($data, true) . ";" . $lineBreak;
		return  writeRawFile(API_PATH_IO_CACHE . DIRECTORY_SEPARATOR . $key . '.php');
	}
}

if (!function_exists("readCache")) {
	/**
	 * Read a key from the cache
	 *
	 * @param string $key Identifier for the data
	 * @return mixed The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
	 * @access public
	 */
	function readCache($key)
	{
		if (!empty($key))
			$key .= substr(md5($_SERVER["HTTP_HOST"]), 3, 7) . '--' . $key;
		else
			return false;
	
		$cachetime = readRawFile(API_PATH_IO_CACHE . DIRECTORY_SEPARATOR . $key . '.php', 11);
		if ($cachetime !== false && intval($cachetime) < time()) {
			return false;
		}
		$data = readRawFile(API_PATH_IO_CACHE . DIRECTORY_SEPARATOR . $key . '.php', true);
		if (!empty($data))
			$data = eval($data);
		return $data;
	}
}

if (!function_exists("checkEmail")) {
	/**
	 * checkEmail()
	 *
	 * @param mixed $email
	 * @return bool|mixed
	 */
	function checkEmail($email)
	{
		if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
			return false;
		}
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
				return false;
			}
		}
		if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
					return false;
				}
			}
		}
		return $email;
	}
}

if (!function_exists("getBaseDomain")) {
	/**
	 * getBaseDomain
	 * 
	 * @param string $url
	 * @return string|unknown
	 */
	function getBaseDomain($url)
	{
		
		static $strata, $fallout, $stratas;
		
		if (empty($strata))
		{
			if (!$strata = readCache('internets_stratas'))
			{
				if (empty($stratas))
					$stratas = file(API_FILE_IO_STRATA);
				shuffle($stratas);
				$attempts = 0;
				while(empty($strata) || $attempts < (count($strata) * 1.65))
				{
					$attempts++;
					$strata = array_keys(unserialize(getURIData($stratas[mt_rand(0, count($stratas)-1)] ."/v1/strata/serial.api")));
				}
				if (!empty($strata))
					writeCache('internets_stratas', $strata, 3600*24*mt(3.75,11));
			}
		}
		if (empty($fallout))
		{
			if (!$fallout = readCache('internets_fallouts'))
			{
				if (empty($stratas))
					$stratas = file(API_FILE_IO_STRATA);
				shuffle($stratas);
				$attempts = 0;
				while(empty($fallout) || $attempts < (count($strata) * 1.65))
				{
					$attempts++;
					$fallout = array_keys(unserialize(getURIData($stratas[mt_rand(0, count($stratas)-1)] ."/v1/fallout/serial.api")));
				}
				if (!empty($fallout))
					writeCache('internets_fallouts', $fallout, 3600*24*mt(3.75,11));
			}
		}
		
		// Get Full Hostname
		$url = strtolower($url);
		$hostname = parse_url($url, PHP_URL_HOST);
		if (!filter_var($hostname, FILTER_VALIDATE_IP) === true) 
			return $hostname;
	
		// break up domain, reverse
		$elements = explode('.', $hostname);
		$elements = array_reverse($elements);
		
		// Returns Base Domain
		if (in_array($elements[0], $fallout) && in_array($elements[1], $strata))
			return $elements[2] . '.' . $elements[1] . '.' . $elements[0];
		elseif (in_array($elements[0], $fallout) || in_array($elements[0], $strata))
			return $elements[1] . '.' . $elements[0];
		
		// Nothing Found
		return $hostname;
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

if (!function_exists("getIP")) {
    
    /* function whitelistGetIP()
     *
     * 	get the True IPv4/IPv6 address of the client using the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @param		boolean		$asString	Whether to return an address or network long integer
     *
     * @return 		mixed
     */
    function getIP($asString = true){
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


if (!function_exists("getNetbios")) {
    
    /* function whitelistGetIP()
     *
     * 	get the True IPv4/IPv6 address of the client using the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @param		boolean		$asString	Whether to return an address or network long integer
     *
     * @return 		mixed
     */
    function getNetbios() {
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
                            return gethostbyaddr($the_IP);
    }
}


/**
 * validateMD5()
 * Validates an MD5 Checksum
 *
 * @param string $email
 * @return boolean
 */

if (!function_exists("validateMD5")) {
    function validateMD5($md5) {
        if(preg_match("/^[a-f0-9]{32}$/i", $md5)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * validateEmail()
 * Validates an Email Address
 *
 * @param string $email
 * @return boolean
 */
if (!function_exists("validateEmail")) {
    function validateEmail($email) {
        if(preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name|edu))$", $email)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * validateDomain()
 * Validates a Domain Name
 *
 * @param string $domain
 * @return boolean
 */
if (!function_exists("validateDomain")) {
    function validateDomain($domain) {
        if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) {
            return false;
        }
        return $domain;
    }
}

/**
 * validateIPv4()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
if (!function_exists("validateIPv4")) {
    function validateIPv4($ip) {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === FALSE) // returns IP is valid
        {
            return false;
        } else {
            return true;
        }
    }
}
/**
 * validateIPv6()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
if (!function_exists("validateIPv6")) {
    function validateIPv6($ip) {
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) // returns IP is valid
        {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists("mailparse_rfc822_parse_addresses")) {
    function mailparse_rfc822_parse_addresses($str = '')
    {
        $emails = array();
        if(preg_match_all('/\s*"?([^><,"]+)"?\s*((?:<[^><,]+>)?)\s*/', $str, $matches, PREG_SET_ORDER) > 0)
        {
            foreach($matches as $m)
            {
                if(! empty($m[2]))
                {
                    $emails[trim($m[2], '<>')] = $m[1];
                }
                else
                {
                    $emails[$m[1]] = '';
                }
            }
        }
        return $emails;
    }
}

/**
 * get A + AAAA record for $host
 *
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 *
 * @param   host    string      netbios networking name\
 * @param   try_a   boolean     try for A Record inclusive of AAAA Records
 *
 * return array
 */
if (!function_exists("getHostByName6")) {
    function getHostByName6($host, $try_a = false) {
        $dns6 = dns_get_record($host, DNS_AAAA);
        if ($try_a == true) {
            $dns4 = getHostByName($host);
            $dns = array_merge($dns4, $dns6);
        }
        else { $dns = $dns6; }
        if ($dns == false) { return false; }
        else { return $dns; }
    }
}


/**
 * get A + AAAA record IPv4/IPv6 Addresses for $host
 *
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 *
 * @param   host    string      netbios networking name
 * @param   try_a   boolean     try for A Record inclusive of AAAA Records
 *
 * return array
 */
if (!function_exists("getHostByNamel6")) {
    function getHostByNamel6($host, $try_a = false) {
        
        $dns6 = dns_get_record($host, DNS_AAAA);
        if ($try_a == true) {
            $dns4 = getHostByName($host);
            $dns = array_merge($dns4, $dns6);
        }
        else { $dns = $dns6; }
        $ip6 = array();
        $ip4 = array();
        foreach ($dns as $record) {
            if ($record["type"] == "A") {
                $ip4[] = $record["ip"];
            }
            if ($record["type"] == "AAAA") {
                $ip6[] = $record["ipv6"];
            }
        }
        if (count($ip6) < 1) {
            if ($try_a == true) {
                if (count($ip4) < 1) {
                    return false;
                }
                else {
                    return $ip4;
                }
            }
            else {
                return false;
            }
        }
        else {
            return $ip6;
        }
    }
}

/**
 * get A record for $host
 *
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 *
 * @param   host    string      netbios networking name
 *
 * return array
 */
if (!function_exists("getHostByName")) {
    function getHostByName($host) {
        $dns = dns_get_record($host, DNS_A);
        if ($dns == false) { return false; }
        else { return $dns; }
    }
}

/**
 * get A record IPv4 Addresses for $host
 *
 * if $try_a is true, if AAAA fails, it tries for A the first match found is returned otherwise returns false
 *
 * @param   host    string      netbios networking name
 *
 * return array
 */
if (!function_exists("getHostByNamel")) {
    function getHostByNamel($host) {
        $dns4 = getHostByName($host);
        $ip4 = array();
        foreach ($dns4 as $record) {
            if ($record["type"] == "A") {
                $ip4[] = $record["ip"];
            }
        }
        if (count($ip4) < 1) {
            return false;
        }
        else {
            return $ip4;
        }
    }
}

/**
 * 
 * @param unknown_type $url
 * @return multitype:number unknown |multitype:string number
 */
function jumpShortenURL($url = '')
{	
	$hostname = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
	if (!is_dir(API_PATH_IO_REFEREE))
		mkdirSecure(API_PATH_IO_REFEREE, 0777);
	if (!is_file($file = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . basename(__DIR__) . '.json'))
		$jumps = array();
	else 
		$jumps = json_decode(file_get_contents($file), true);
	if (isset($_REQUEST['custom'])&&!empty($_REQUEST['custom']))
	    $referee = encode_sef(trim($_REQUEST['custom']));
	else
		$referee = '';
	
	while(testForShortenURL($referee)==true || empty($referee))
	{
		if (!isset($jumps[md5($url)]))
		{
			set_time_limit(120);
			$crc = new xcp($url, mt_rand(0,254), mt_rand(5,9));
			$referee = $crc->calc($url);
		}
	}
	
	$alias = API_ALIAS_ADDRESS_PREFIX . ((strlen(API_ALIAS_ADDRESS_PREFIX) == 0 ? "" : ".")) . $referee . ((strlen(API_ALIAS_ADDRESS_SUFFIX) == 0 ? "" : ".")) . API_ALIAS_ADDRESS_SUFFIX . '@' . API_DEPLOYMENT_EMAILDOMAIN;
	if (!defined('API_DEPLOYMENT_EMAILDOMAIN') > 0) {
    	if (!is_dir($pgppath = dirname(__DIR__) . DIRECTORY_SEPARATOR . API_DEPLOYMENT_EMAILDOMAIN . DIRECTORY_SEPARATOR . '.pgp-keys'))
    	    mkdirSecure($pgppath, 0777, true);
    	    
        if (!file_exists($pgppath . DIRECTORY_SEPARATOR . "$alias.diz") && !file_exists($pgppath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, array_reverse(explode('.', basename(__DIR__)))) . DIRECTORY_SEPARATOR . $alias . ".asc")) {
            mkdirSecure($ascpath =$pgppath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, array_reverse(explode('.', basename(__DIR__)))), 0777);
            
            mt_srand(mt_rand(-time(), time()), MT_RAND_MT19937);
            mt_srand(mt_rand(-time(), time()), MT_RAND_MT19937);
            mt_srand(mt_rand(-time() * time(), time() * time()), MT_RAND_MT19937);
            mt_srand(mt_rand(-time() * time(), time() * time()), MT_RAND_MT19937);
            mt_srand(mt_rand(-time() * time() * time(), time() * time() * time()), MT_RAND_MT19937);
            mt_srand(mt_rand(-time() * time() * time() * time(), time() * time() * time() * time()), MT_RAND_MT19937);
            
            writeRawFile($diz = $pgppath . DIRECTORY_SEPARATOR . "$alias.diz", str_replace('%name', $alias, str_replace('%email', "$alias", str_replace('%subbits', mt_rand(API_OPENPGP_MINBITS, API_OPENPGP_MAXBITS), str_replace('%bits', mt_rand(API_OPENPGP_MINBITS, API_OPENPGP_MAXBITS), file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'gen-key-script.diz'))))));
            
            exec("gpg --batch --gen-key \"$diz\"", $output, $result);
            exec("unlink \"$diz\"", $output, $result);
            exec("gpg --armor --export $alias > \"" . ($pgparmor = $ascpath . DIRECTORY_SEPARATOR . $alias . ".asc") ."\"", $output, $result);
            foreach(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'keyservers-hostnames.diz') as $keyserver)
                exec("gpg --keyserver " . str_replace(array("\n", "\r", "\t"), "", trim($keyserver)) . " --send-key $alias", $output, $result);
        }
	}
	
	if (!is_file($jumpfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.json'))
        $jumps = array();
    else
        $jumps = json_decode(file_get_contents($jumpfile), true);
    
    if (!is_file($emailfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.emails.json'))
        $emails = array();
    else
        $emails = json_decode(file_get_contents($emailfile), true);
    
    if (constant('API_DEPLOYMENT_CALLING') == true) {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'myip.php';
        $myip = new myip();
        $ipdata = $myip->query('allmyip', 'json');
        if (!is_file($callfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.calling.json'))
            $calls = array();
        else
            $calls = json_decode(file_get_contents($callfile), true);
    }
    foreach($jumps as $finger => $values)
    {
        if (isset($values['last']) && isset($values['inactive']))
            if ($values['last'] + $values['inactive'] < microtime(true)) {
                $calls['expire'][$finger][time()] = array_merge(array('ipdata' => $ipdata), $emails[$finger], $jumps[$finger], array('hostname' => parse_url(API_URL, PHP_URL_HOST)));
                unset($jumps[$finger]);
                unset($emails[$finger]);
            }
    }
    $result = $jumps[$hash = md5($url.$referee.microtime(true))] = array('emails' => count($emailers = mailparse_rfc822_parse_addresses($_REQUEST['emails'])), "alias" => $alias, "pgpkey" => file_get_contents($pgparmor), "created" => microtime(true), "last" => microtime(true), 'inactive' => (API_DROP_DAYS_INACTIVE * (3600 * 24)), "short" => API_ROOT_PROTOCOL.API_HOSTNAME.'/v2/'.$referee . (isset($_REQUEST['username']) && !empty($_REQUEST['username']) ? '?' . $_REQUEST['username'] :''), "domain" => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME  . (isset($_REQUEST['username']) && !empty($_REQUEST['username']) ? '/?' . $_REQUEST['username'] :''), 'url' => $url, 'referee' => $referee, 'timezone' => date_default_timezone_get(), $alias => array('adding' => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME.'/adding', 'list' => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME.'/list', 'remove' => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME.'/remove'), 'data' => array('php' => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME.'/php', 'json' => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME.'/json', 'serial' => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME.'/serial', 'xml' => API_SUBS_PROTOCOL.$referee.'.'.API_HOSTNAME.'/xml'));
    $emails[$hash] = array('create-username' => $_REQUEST['username'], 'alias-pgpkey' => $ascpath, 'alias-emails' => $emailers, 'email' => $_REQUEST['email'], 'callback-hits' => $_REQUEST['callback-hits'], 'callback-stats' => $_REQUEST['callback-stats'], 'callback-reports' => $_REQUEST['callback-reports'], 'callback-expires' => $_REQUEST['callback-expires']);
    if (constant('API_DEPLOYMENT_CALLING') == true) {
        $calls['create'][$hash][time()] = array_merge(array('ipdata' => $ipdata), $emails[$hash], $jumps[$hash], array('hostname' => parse_url(API_URL, PHP_URL_HOST)));
    }
    writeRawFile($jumpfile, json_encode($jumps));
    writeRawFile($emailfile, json_encode($emails));
    if (constant('API_DEPLOYMENT_CALLING') == true) {
        writeRawFile($callfile, json_encode($calls));
    }
	return $result;
}


/**
 * 
 * @param unknown_type $url
 * @return multitype:number unknown |multitype:string number
 */
function jumpFromShortenURL($hash = '')
{	
	$hostname = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
	if (!is_dir(API_PATH_IO_REFEREE))
		mkdirSecure(API_PATH_IO_REFEREE, 0777);
	
	if (!is_file($jumpfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.json'))
	    $jumps = array();
    else
        $jumps = json_decode(file_get_contents($jumpfile), true);
	        
	if (!is_file($emailfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.emails.json'))
	    $emails = array();
    else
        $emails = json_decode(file_get_contents($emailfile), true);
	
	if (constant('API_DEPLOYMENT_CALLING') == true) {
	    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'myip.php';
	    $myip = new myip();
	    $ipdata = $myip->query('allmyip', 'json');
	    if (!is_file($callfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.calling.json'))
	        $calls = array();
        else
            $calls = json_decode(file_get_contents($callfile), true);
	}
	
	foreach($jumps as $finger => $values)
	{
		if (isset($values['last']) && isset($values['inactive']))
		    if ($values['last'] + $values['inactive'] < microtime(true)) {
		        $calls['expire'][$finger][time()] = array_merge(array('ipdata' => $ipdata), $emails[$finger], $jumps[$finger], array('hostname' => parse_url(API_URL, PHP_URL_HOST)));
		        unset($jumps[$finger]);
		        unset($emails[$finger]);
		    }
	}
	
	foreach($jumps as $finger => $values)
	{
		if (strtolower($values['referee']) == strtolower($hash))
		{
			if (!isset($jumps[$finger]['hits']))
				$jumps[$finger]['hits'] = 1;
			else
				$jumps[$finger]['hits']++;
			$jumps[$finger]['last'] = microtime(true);
			writeRawFile($jumpfile, json_encode($jumps));
			if (constant('API_DEPLOYMENT_CALLING') == true) {
			    $calls['hit'][$finger][microtime(true)] = array_merge(array('ipdata' => $ipdata, 'deploy-username' => parse_url(API_URL . $_SERVER['REQUEST_URI'], PHP_URL_QUERY)), $emails[$hash], $jumps[$hash], array('hostname' => parse_url(API_URL, PHP_URL_HOST)));
			}
			writeRawFile($emailfile, json_encode($emails));
			if (constant('API_DEPLOYMENT_CALLING') == true) {
			    writeRawFile($callfile, json_encode($calls));
			}
			
			// Redirect to ensourced URI
			header( "HTTP/1.1 301 Moved Permanently" );
			header("Location: ".$values['url']);
			exit(0);
		} else {
			
		}
	}
	writeRawFile($jumpfile, json_encode($jumps));

	// Redirect to ensourced URI
	header( "HTTP/1.1 301 Moved Permanently" );
	header("Location: " . API_URL);
	exit(0);
}

/**
 *
 * @param unknown_type $url
 * @return multitype:number unknown |multitype:string number
 */
function dataFromShortenURL($hash = '')
{
    
    if (!is_dir(API_PATH_IO_REFEREE))
        mkdirSecure(API_PATH_IO_REFEREE, 0777);
    
    if (!is_file($jumpfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.json'))
        $jumps = array();
    else
        $jumps = json_decode(file_get_contents($jumpfile), true);
                
    if (!is_file($emailfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.emails.json'))
        $emails = array();
    else
        $emails = json_decode(file_get_contents($emailfile), true);
                    
    if (constant('API_DEPLOYMENT_CALLING') == true) {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'myip.php';
        $myip = new myip();
        $ipdata = $myip->query('allmyip', 'json');
        if (!is_file($callfile = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . API_HOSTNAME . '.calling.json'))
            $calls = array();
        else
            $calls = json_decode(file_get_contents($callfile), true);
    }
                        
    foreach($jumps as $finger => $values)
    {
        if (isset($values['last']) && isset($values['inactive']))
            if ($values['last'] + $values['inactive'] < microtime(true)) {
                $calls['expire'][$finger][time()] = array_merge(array('ipdata' => $ipdata), $emails[$finger], $jumps[$finger], array('hostname' => parse_url(API_URL, PHP_URL_HOST)));
                unset($jumps[$finger]);
                unset($emails[$finger]);
        }
    }
    writeRawFile($jumpfile, json_encode($jumps));
    writeRawFile($emailfile, json_encode($emails));
    foreach($jumps as $finger => $values)
    {
        if (strtolower($values['referee']) == strtolower($hash))
        {
            if (constant('API_DEPLOYMENT_CALLING') == true) {
                $calls['data'][$finger][microtime(true)] = array_merge(array('ipdata' => $ipdata), $emails[$finger], $jumps[$finger], array('hostname' => parse_url(API_URL, PHP_URL_HOST)));
            }
            if (constant('API_DEPLOYMENT_CALLING') == true) {
                writeRawFile($callfile, json_encode($calls));
            }
            return $values;
        }
    }
    return array();
}

/**
 * 
 * @param unknown_type $url
 * @return multitype:number unknown |multitype:string number
 */
function testForShortenURL($hash = '')
{       
        $hostname = array_reverse(explode('.', $_SERVER['HTTP_HOST']));
        if (!is_dir(API_PATH_IO_REFEREE))
                mkdirSecure(API_PATH_IO_REFEREE, 0777);
        if (!is_file($file = API_PATH_IO_REFEREE  . DIRECTORY_SEPARATOR . basename(__DIR__) . '.json'))
                $jumps = array();
        else 
                $jumps = json_decode(file_get_contents($file), true);
	foreach($jumps as $finger => $values)
	{
                if (isset($values['last']) && isset($values['inactive']))
                        if ($values['last'] + $values['inactive'] < microtime(true))
                                unset($jumps[$finger]);
	}
        foreach($jumps as $finger => $values)
        {
                if (strtolower($values['referee']) == strtolower($hash))
                {
                        if (!isset($jumps[$finger]['hits']))
                                $jumps[$finger]['hits'] = 1;
                        else
                                $jumps[$finger]['hits']++;
                        $jumps[$finger]['last'] = microtime(true);
                        writeRawFile($file, json_encode($jumps)); 
                        return true;
                        exit(0);
                } else {
                        
                }
        }
	writeRawFile($file, json_encode($jumps)); 
	return false;
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

?>
