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

class PathStuffController
{
    public $apiPath = array(
        'root' => '',
        'lib'  => '',
        'tmp' => '',
        'www' => '/var/www',
        'sites_available' => '/etc/apache2/sites-available',
        'ssl_certificates' => '/etc/ssl/certs',
        'awstats' => '/etc/awstats'
    );

    public $apiPathDefault = array(
        'lib'  => 'tmp',
        'tmp' => 'caches',
        'www' => '/var/www',
        'sites_available' => '/etc/apache2/sites-available',
        'ssl_certificates' => '/etc/ssl/certs',
        'awstats' => '/etc/awstats');

    public $tmpPath = array();

    public $path_lookup = array(
        'root' => 'ROOT_PATH',
        'tmp' => 'VAR_PATH',
        'lib'  => 'PATH',
        'www' => 'WWW_PATH',
        'sites_available' => 'SITES_AVAILABLE_PATH',
        'ssl_certificates' => 'SSL_CERTIFICATES_PATH',
        'awstats' => 'AWSTATS_PATH'
    );

    public $apiUrl = '';
    public $apiCookieDomain = '';
    public $apiRootDomain = '';
    
    public $validPath = array(
        'root' => 0,
        'tmp' => 0,
        'lib'  => 0,
        'www' => 0,
        'sites_available' => 0,
        'ssl_certificates' => 0,
        'awstats' => 0
    );

    public $validUrl = false;

    public $permErrors = array(
        'root' => null,
        'tmp' => null);

    /**
     * @param $apiPathDefault
     * @param $tmpPath
     */
    public function __construct($apiPathDefault, $tmpPath)
    {
        $this->apiPathDefault = $apiPathDefault;
        $this->tmpPath         = $tmpPath;

        if (isset($_SESSION['settings']['ROOT_PATH'])) {
            foreach ($this->path_lookup as $req => $sess) {
                $this->apiPath[$req] = $_SESSION['settings'][$sess];
            }
        } else {
            $path = str_replace("\\", '/', realpath('../'));
            if (substr($path, -1) === '/') {
                $path = substr($path, 0, -1);
            }
            if (file_exists("$path/apiconfig.php")) {
                $this->apiPath['root'] = $path;
            }
            // Firstly, locate API lib folder out of API root folder
            $this->apiPath['lib'] = dirname($path) . '/' . $this->apiPathDefault['lib'];
            // If the folder is not created, re-locate API lib folder inside API root folder
            if (!is_dir($this->apiPath['lib'] . '/')) {
                $this->apiPath['lib'] = $path . '/' . $this->apiPathDefault['lib'];
            }
            // Firstly, locate API tmp folder out of API root folder
            $this->apiPath['tmp'] = '/tmp';
            // If the folder is not created, re-locate API tmp folder inside API root folder
            if (!is_dir($this->apiPath['tmp'] . '/')) {
                $this->apiPath['tmp'] = $path . '/' . $this->apiPathDefault['tmp'];
            }
            if (!is_dir($this->apiPath['www'] . '/')) {
                $this->apiPath['www'] = $this->apiPathDefault['www'];
            }
            if (!is_dir($this->apiPath['ssl_certificates'] . '/')) {
                $this->apiPath['ssl_certificates'] = $this->apiPathDefault['ssl_certificates'];
            }
            if (!is_dir($this->apiPath['sites_available'] . '/')) {
                $this->apiPath['sites_available'] = $this->apiPathDefault['sites_available'];
            }
            if (!is_dir($this->apiPath['awstats'] . '/')) {
                $this->apiPath['awstats'] = $this->apiPathDefault['awstats'];
            }
        }
        if (isset($_SESSION['settings']['URL'])) {
            $this->apiUrl = $_SESSION['settings']['URL'];
        } else {
            $path           = $GLOBALS['wizard']->baseLocation();
            $this->apiUrl = substr($path, 0, strrpos($path, '/'));
        }
        if (isset($_SESSION['settings']['COOKIE_DOMAIN'])) {
            $this->apiCookieDomain = $_SESSION['settings']['COOKIE_DOMAIN'];
        } else {
            $this->apiCookieDomain = getBaseDomain($this->apiUrl);
        }
        if (isset($_SESSION['settings']['ROOT_DOMAIN'])) {
            $this->apiRootDomain = $_SESSION['settings']['ROOT_DOMAIN'];
        } else {
            $this->apiRootDomain = getBaseDomain($this->apiUrl);
        }
        if (isset($_SESSION['settings']['ROOT_DOMAIN'])) {
            $this->apiEmailDomain = $_SESSION['settings']['EMAIL_DOMAIN'];
        } else {
            $this->apiEmailDomain = 'alias.'.getBaseDomain($this->apiUrl);
        }
    }

    public function execute($file = '')
    {
        switch (basename($file)) {
            default:
                $this->readRequest();
                $valid = $this->validate();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    foreach ($this->path_lookup as $req => $sess) {
                        $_SESSION['settings'][$sess] = $this->apiPath[$req];
                    }
                    $_SESSION['settings']['URL'] = $this->apiUrl;
                    $_SESSION['settings']['COOKIE_DOMAIN'] = $this->apiCookieDomain;
                    $_SESSION['settings']['ROOT_DOMAIN'] = $this->apiRootDomain;
                    $_SESSION['settings']['EMAIL_DOMAIN'] = $this->apiEmailDomain;
                    if ($valid) {
                        $GLOBALS['wizard']->redirectToPage('+1');
                    } else {
                        $GLOBALS['wizard']->redirectToPage('+0');
                    }
                }
                break;
            case 'page_extrasettings.php':
                global $wizard;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    foreach ($wizard->configs['api_url'] as $req => $sess) {
                        $_SESSION['settings'][strtoupper($req). '_URL'] = $_REQUEST[$req];
                    }
                    $GLOBALS['wizard']->redirectToPage('+1');
                }
        }
    }

    public function readRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request = $_POST;
            foreach ($this->path_lookup as $req => $sess) {
                if (isset($request[$req])) {
                    $request[$req] = str_replace("\\", '/', trim($request[$req]));
                    if (substr($request[$req], -1) === '/') {
                        $request[$req] = substr($request[$req], 0, -1);
                    }
                    $this->apiPath[$req] = $request[$req];
                }
            }
            if (isset($request['URL'])) {
                $request['URL'] = trim($request['URL']);
                if (substr($request['URL'], -1) === '/') {
                    $request['URL'] = substr($request['URL'], 0, -1);
                }
                $this->apiUrl = $request['URL'];
            }
            if (isset($request['COOKIE_DOMAIN'])) {
                $tempCookieDomain = trim($request['COOKIE_DOMAIN']);
                $tempParts = parse_url($tempCookieDomain);
                if (!empty($tempParts['host'])) {
                    $tempCookieDomain = $tempParts['host'];
                }
                $request['COOKIE_DOMAIN'] = $tempCookieDomain;
                $this->apiCookieDomain = $tempCookieDomain;;
            }
            if (isset($request['ROOT_DOMAIN'])) {
                $tempRootDomain = getBaseDomain(trim($request['URL']));
                $request['ROOT_DOMAIN'] = $tempRootDomain;
                $this->apiRootDomain = $tempRootDomain;;
            }
            
            if (isset($request['EMAIL_DOMAIN'])) {
                $tempRootDomain = 'alias.'.getBaseDomain(trim($request['URL']));
                $request['EMAIL_DOMAIN'] = $tempRootDomain;
                $this->apiRootDomain = $tempRootDomain;;
            }
        }
    }

    /**
     * @return bool
     */
    public function validate()
    {
        foreach (array_keys($this->apiPath) as $path) {
            if ($this->checkPath($path)) {
                $this->checkPermissions($path);
            }
        }
        $this->validUrl = !empty($this->apiUrl);
        $validPaths     = (array_sum(array_values($this->validPath)) == count(array_keys($this->validPath))) ? 1 : 0;
        $validPerms     = true;
        foreach ($this->permErrors as $key => $errs) {
            if (empty($errs)) {
                continue;
            }
            foreach ($errs as $path => $status) {
                if (empty($status)) {
                    $validPerms = false;
                    break;
                }
            }
        }

        return ($validPaths && $this->validUrl && $validPerms);
    }

    /**
     * @param string $PATH
     *
     * @return int
     */
    public function checkPath($PATH = '')
    {
        $ret = 1;
        if ($PATH === 'root' || empty($PATH)) {
            $path = 'root';
            if (is_dir($this->apiPath[$path]) && is_readable($this->apiPath[$path])) {
                @include_once "{$this->apiPath[$path]}/include/version.php";
                if (file_exists("{$this->apiPath[$path]}/apiconfig.php") && defined('API_VERSION')) {
                    $this->validPath[$path] = 1;
                }
            }
            $ret *= $this->validPath[$path];
        }
        if ($PATH === 'lib' || empty($PATH)) {
            $path = 'lib';
            if (is_dir($this->apiPath[$path]) && is_readable($this->apiPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        if ($PATH === 'tmp' || empty($PATH)) {
            $path = 'tmp';
            if (is_dir($this->apiPath[$path]) && is_readable($this->apiPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        if ($PATH === 'www' || empty($PATH)) {
            $path = 'www';
            if (is_dir($this->apiPath[$path]) && is_readable($this->apiPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        if ($PATH === 'ssl_certificates' || empty($PATH)) {
            $path = 'ssl_certificates';
            if (is_dir($this->apiPath[$path]) && is_readable($this->apiPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        if ($PATH === 'sites_available' || empty($PATH)) {
            $path = 'sites_available';
            if (is_dir($this->apiPath[$path]) && is_readable($this->apiPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        if ($PATH === 'awstats' || empty($PATH)) {
            $path = 'awstats';
            if (is_dir($this->apiPath[$path]) && is_readable($this->apiPath[$path])) {
                $this->validPath[$path] = 1;
            }
            $ret *= $this->validPath[$path];
        }
        return $ret;
    }

    /**
     * @param $parent
     * @param $path
     * @param $error
     * @return null
     */
    public function setPermission($parent, $path, &$error)
    {
        if (is_array($path)) {
            foreach (array_keys($path) as $item) {
                if (is_string($item)) {
                    $error[$parent . '/' . $item] = $this->makeWritable($parent . '/' . $item);
                    if (empty($path[$item])) {
                        continue;
                    }
                    foreach ($path[$item] as $child) {
                        $this->setPermission($parent . '/' . $item, $child, $error);
                    }
                } else {
                    $error[$parent . '/' . $path[$item]] = $this->makeWritable($parent . '/' . $path[$item]);
                }
            }
        } else {
            $error[$parent . '/' . $path] = $this->makeWritable($parent . '/' . $path);
        }

        return null;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function checkPermissions($path)
    {
        $paths  = array(
            'root' => array('mainfile.php','owner.php','dbconfig.php'),
            'tmp' => $this->tmpPath);
        $errors = array(
            'root' => null,
            'tmp' => null);

        if (!isset($this->apiPath[$path])) {
            return false;
        }
        if (!isset($errors[$path])) {
            return true;
        }
        $this->setPermission($this->apiPath[$path], $paths[$path], $errors[$path]);
        if (in_array(false, $errors[$path])) {
            $this->permErrors[$path] = $errors[$path];
        }

        return true;
    }

    /**
     * Write-enable the specified folder
     *
     * @param string $path
     * @param bool   $create
     *
     * @internal param bool $recurse
     * @return false on failure, method (u-ser,g-roup,w-orld) on success
     */
    public function makeWritable($path, $create = true)
    {
        $mode = intval('0777', 8);
        if (!file_exists($path)) {
            if (!$create) {
                return false;
            } else {
                mkdir($path, $mode);
            }
        }
        if (!is_writable($path)) {
            chmod($path, $mode);
        }
        clearstatcache();
        if (is_writable($path)) {
            $info = stat($path);
            if ($info['mode'] & 0002) {
                return 'w';
            } elseif ($info['mode'] & 0020) {
                return 'g';
            }

            return 'u';
        }

        return false;
    }
}
