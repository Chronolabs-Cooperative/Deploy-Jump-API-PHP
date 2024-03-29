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

$pageHasForm = false;
$pageHasHelp = false;

$vars =& $_SESSION['settings'];

if (empty($vars['ROOT_PATH'])) {
    $wizard->redirectToPage('pathsettings');
    exit();
} elseif (empty($vars['DB_HOST'])) {
    $wizard->redirectToPage('dbsettings');
    exit();
}

$writeFiles = array(
    $vars['ROOT_PATH'] . '/mainfile.php',
    $vars['ROOT_PATH'] . '/include/dbconfig.php',
);

$writeCheck = checkFileWriteablity($writeFiles);
if (true === $writeCheck) {
    $rewrite = array(
        'GROUP_ADMIN' => 1,
        'GROUP_USERS' => 2,
        'GROUP_ANONYMOUS' => 3);
    $rewrite = array_merge($rewrite, $vars);
    
    $result = writeConfigurationFile($rewrite, $vars['ROOT_PATH'] . '/include', 'constants.dist.php', 'constants.php');
    $GLOBALS['error'] = !($result === true);
    
    $result = writeConfigurationFile($rewrite, $vars['ROOT_PATH'] . '/include', 'dbconfig.dist.php', 'dbconfig.php');
    $GLOBALS['error'] = !($result === true);
    if ($result === true) {
        $result = writeConfigurationFile($rewrite, $vars['ROOT_PATH'], 'mainfile.dist.php', 'mainfile.php');
        $GLOBALS['error'] = !($result === true);
    }

    $_SESSION['settings']['authorized'] = false;

    if ($result === true) {
        $_SESSION['UserLogin'] = true;
        $_SESSION['settings']['authorized'] = true;
        ob_start();
        ?>

        <div class="alert alert-success"><span class="fa fa-check text-success"></span> <?php echo SAVED_MAINFILE; ?></div>
        <div class='well'><?php echo SAVED_MAINFILE_MSG; ?>
        <ul class='diags'>
            <?php
            foreach ($vars as $k => $v) {
                if ($k === 'authorized') {
                    continue;
                }
                echo "<li><strong>API_{$k}</strong> " . IS_VALOR . " {$v}</li>";
            }
            ?>
        </ul>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
    } else {
        $GLOBALS['error'] = true;
        $pageHasForm = true; // will redirect to same page
        $content = '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> ' . $result . '</div>';
    }
} else {
    $content = '';
    foreach ($writeCheck as $errorMsg) {
        $GLOBALS['error'] = true;
        $pageHasForm = true; // will redirect to same page
        $content .= '<div class="alert alert-danger"><span class="fa fa-ban text-danger"></span> ' . $errorMsg . '</div>' . "\n";
    }
}
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


/**
 * Get file stats
 *
 * @param string $filename file or directory name
 *
 * @return array|false false on error, or array of file stat information
 */
function getStats($filename)
{
    $stat = stat($filename);
    if (false === $stat) {
        return false;
    }
    return prepStats($stat);
}

/**
 * Get file stats on a created temp file
 *
 * @return array|false false on error, or array of file stat information
 */
function getTmpStats()
{
    $temp = tmpfile();
    if (false === $temp) {
        return false;
    }
    $stat = fstat($temp);
    fclose($temp);
    if (false === $stat) {
        return false;
    }
    return prepStats($stat);
}

/**
 * Get stat() info in a more usable form
 *
 * @param array $stat return from PHP stat()
 *
 * @return array selected information gleaned from $stat
 */
function prepStats($stat)
{
    $subSet = array();
    $mode = $stat['mode'];
    $subSet['mode'] = $mode;
    $subSet['uid'] = $stat['uid'];
    $subSet['gid'] = $stat['gid'];

    $subSet['user']['read']   = (bool) ($mode & 0400);
    $subSet['user']['write']  = (bool) ($mode & 0200);
    $subSet['user']['exec']   = (bool) ($mode & 0100);
    $subSet['group']['read']  = (bool) ($mode & 040);
    $subSet['group']['write'] = (bool) ($mode & 020);
    $subSet['group']['exec']  = (bool) ($mode & 010);
    $subSet['other']['read']  = (bool) ($mode & 04);
    $subSet['other']['write'] = (bool) ($mode & 02);
    $subSet['other']['exec']  = (bool) ($mode & 01);

    return $subSet;
}

/**
 * Attempt to check if a set of files can be written
 *
 * @param string[] $files fully qualified file names to check
 *
 * @return string[]|true true if no issues found, array
 */
function checkFileWriteablity($files)
{
    if (isset($_POST['op']) && $_POST['op'] === 'proceed') {
        return true; // user said skip this
    }
    $tmpStats = getTmpStats();
    if (false === $tmpStats) {
        return true; // tests are not applicable
    }

    $message = array();

    foreach ($files as $file) {
        $dirName = dirname($file);
        $fileName = basename($file);
        $dirStat = getStats($dirName);
        if (false !== $dirStat) {
            $uid = $tmpStats['uid'];
            $gid = $tmpStats['gid'];
            if (!(($uid === $dirStat['uid'] && $dirStat['user']['write'])
                || ($gid === $dirStat['gid'] && $dirStat['group']['write'])
                || (file_exists($file) && is_writable($file))
                || (false !== stripos(PHP_OS, 'WIN'))
            )
            ) {
                $uidStr = (string) $uid;
                $dUidStr = (string) $dirStat['uid'];
                $gidStr = (string) $gid;
                $dGidStr = (string) $dirStat['gid'];
                if (function_exists('posix_getpwuid')) {
                    $tempUsr = posix_getpwuid($uid);
                    $uidStr = isset($tempUsr['name']) ? $tempUsr['name'] : (string) $uid;
                    $tempUsr = posix_getpwuid($dirStat['uid']);
                    $dUidStr = isset($tempUsr['name']) ? $tempUsr['name'] : (string) $dirStat['uid'];
                }
                if (function_exists('posix_getgrgid')) {
                    $tempGrp = posix_getgrgid($gid);
                    $gidStr = isset($tempGrp['name']) ? $tempGrp['name'] : (string) $gid;
                    $tempGrp = posix_getgrgid($dirStat['gid']);
                    $dGidStr = isset($tempGrp['name']) ? $tempGrp['name'] : (string) $dirStat['gid'];
                }
                $message[] = sprintf(
                    CHMOD_CHGRP_ERROR,
                    $fileName,
                    $uidStr,
                    $gidStr,
                    basename($dirName),
                    $dUidStr,
                    $dGidStr
                );
            }
        }
    }
    return empty($message) ? true : $message;
}
