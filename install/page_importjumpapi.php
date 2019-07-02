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

require_once './include/common.inc.php';
defined('API_INSTALL') || die('API Installation wizard die');

include_once './include/functions.php';
include_once '../class/apilists.php';

$pageHasForm = true;
$pageHasHelp = true;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && @$_GET['var'] && @$_GET['action'] === 'checkfile') {
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wizard->redirectToPage('+1');
    return 302;
}
ob_start();

$jumpapis = array();
$folders = APILists::getDirListAsArray(API_WWW_PATH);
foreach($folders as $folder) {
    if (file_exists($file = API_WWW_PATH . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'index.php')) {
        $jumpapi = false;
        foreach(file($file) as $line)
            if (strpos($line, "@subpackage") && strpos($line, "shortening-url")) {
                $jumpapi = true;
                continue;
            }
        $jumpapis[$folder] = API_WWW_PATH . DIRECTORY_SEPARATOR . $folder;
    }
}
?>
    <div class="panel panel-info">
        <div class="panel-heading"><?php echo API_CONFIG_JUMPAPI; ?></div>
        <div class="panel-body">
        <div class="form-group">
        <div class="form-group">
                <div id="article" class="article">
		   <?php foreach($jumpapis as $folder => $path) { 
		      $sql = "SELECT `id` FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `domain` LIKE '" . $hostname = getBaseDomain("http://".$folder) ."'";
		      list($domainid) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
		      if ($domainid==0) {
		          $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('domains') . "` (`api-uid`, `domains`, `admin-email`, `created`) VALUES ('1', '" . $hostname . "', 'webmaster@" . $hostname . ", UNIX_TIMESTAMP())";
		          if ($GLOBALS['APIDB']->queryF($sql)) {
		              $domainid = $GLOBALS['APIDB']->getInsertId();
?>					<div id="item" class="item">Domain: <?php echo $hostname; ?> (<?php echo $domainid; ?>) - Created!</div>
<?php 
		          } else {
?>					<div id="item" class="item error">Domain: <?php echo $hostname; ?> - Error Creating!</div>
<?php 
		          }
		      }
		      $parts = explode('.', $folder);
		      $subdomain = $parts[0];
		      unset($parts[0]);
		      $host = implode('.', $parts);
		      $sql = "SELECT `id` FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE `domain-id` = '$domainid' AND `sub-domain` LIKE '" . $subdomain ."' AND `hostname` LIKE '" . $host ."'";
		      list($jumpid) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
		      if ($jumpid==0) {
		          $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('domains') . "` (`api-uid`, `domain-id`, `sub-domain`, `hostname`, `created`) VALUES ('1', '" . $domainid . "', '". $subdomain. ", '". $host. ", UNIX_TIMESTAMP())";
		          if ($GLOBALS['APIDB']->queryF($sql)) {
		            $jumpid = $GLOBALS['APIDB']->getInsertId();
?>					<div id="item" class="item">Jump Sub-domain: <?php echo $folder; ?> (<?php echo $jumpid; ?> / <?php echo $domainid; ?> ) - Created!</div>
<?php 
		          } else {
?>					<div id="item" class="item error">Jump Sub-domain: <?php echo $folder; ?> - Error Creating!</div>
<?php 
		          }
		      }
		      if (writeConfigurationFile(array('DEPLOYMENT_URL' => API_URL, 'DEPLOYMENT_USERNAME' => $_SESSION['settings']['ADMIN_UNAME'], 'DEPLOYMENT_USERNAME' => $_SESSION['settings']['ADMIN_PASS']), $path, 'deployment.dist.php', 'deployment.php')) {
?>					<div id="item" class="item">Jump Deployment: <?php echo $path . DIRECTORY_SEPARATOR . 'deployment.php'; ?> (<?php echo $jumpid; ?> / <?php echo $domainid; ?> ) - Created!</div>
<?php 
		      } else {
?>					<div id="item" class="item error">Jump Deployment: <?php echo $path . DIRECTORY_SEPARATOR . 'deployment.php'; ?> (<?php echo $jumpid; ?> / <?php echo $domainid; ?> ) - Error Creating!</div>
<?php 
		      }
		       
		   }?>
           		</div>
           </div>
           
        </div>
        
   </div>
<?php
$content = ob_get_contents();
ob_end_clean();

include './include/install_tpl.php';


/**
 * Copy a configuration file from template, then rewrite with actual configuration values
 *
 * @param string[] $vars       config values
 * @param string   $path       directory path where files reside
 * @param string   $sourceName template file name
 * @param string   $fileName   configuration file name
 *
 * @return true|string true on success, error message on failure
 */
function writeConfigurationFile($vars, $path, $sourceName, $fileName)
{
    $path .= '/';
    if (!@copy(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . $sourceName, $path . $fileName)) {
        return sprintf(ERR_COPY_MAINFILE, $fileName);
    } else {
        clearstatcache();
        if (!$file = fopen($path . $fileName, 'r')) {
            return sprintf(ERR_READ_MAINFILE, $fileName);
        } else {
            $content = fread($file, filesize($path . $fileName));
            fclose($file);
            
            foreach ($vars as $key => $val) {
                if (is_int($val) && preg_match("/(define\()([\"'])(API_{$key})\\2,\s*(\d+)\s*\)/", $content)) {
                    $content = preg_replace("/(define\()([\"'])(API_{$key})\\2,\s*(\d+)\s*\)/", "define('API_{$key}', {$val})", $content);
                } elseif (preg_match("/(define\()([\"'])(API_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", $content)) {
                    $val     = str_replace('$', '\$', addslashes($val));
                    $content = preg_replace("/(define\()([\"'])(API_{$key})\\2,\s*([\"'])(.*?)\\4\s*\)/", "define('API_{$key}', '{$val}')", $content);
                }
            }
            $file = fopen($path . $fileName, 'w');
            if (false === $file) {
                return sprintf(ERR_WRITE_MAINFILE, $fileName);
            }
            $writeResult = fwrite($file, $content);
            fclose($file);
            if (false === $writeResult) {
                return sprintf(ERR_WRITE_MAINFILE, $fileName);
            }
        }
    }
    return true;
}

