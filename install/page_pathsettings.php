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

include_once './class/pathcontroller.php';
include_once '../include/functions.php';

$pageHasForm = true;
$pageHasHelp = true;
global $wizard;

$ctrl = new PathStuffController($wizard->configs['apiPathDefault'], $wizard->configs['tmpPath']);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && @$_GET['var'] && @$_GET['action'] === 'checkpath') {
    $path                   = $_GET['var'];
    $ctrl->apiPath[$path] = htmlspecialchars(trim($_GET['path']));
    echo genPathCheckHtml($path, $ctrl->checkPath($path));
    exit();
}
$ctrl->execute();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    return null;
}
ob_start();
?>
    <script type="text/javascript">
        function removeTrailing(id, val) {
            if (val[val.length - 1] == '/') {
                val = val.substr(0, val.length - 1);
                $(id).value = val;
            }

            return val;
        }

        function updPath(key, val) {
            val = removeTrailing(key, val);
            $.get( "<?php echo $_SERVER['PHP_SELF']; ?>", { action: "checkpath", var: key, path: val } )
                .done(function( tmp ) {
                    $("#" + key + 'pathimg').html(tmp);
                });
            $("#" + key + 'perms').style.display = 'none';
        }
    </script>
    <div class="panel panel-info">
        <div class="panel-heading"><?php echo API_PATHS; ?></div>
        <div class="panel-body">

            <div class="form-group">
                <label class="xolabel" for="root"><?php echo API_ROOT_PATH_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_ROOT_PATH_HELP; ?></div>
                <input type="text" class="form-control" name="root" id="root" value="<?php echo $ctrl->apiPath['root']; ?>" onchange="updPath('root', this.value)"/>
                <span id="rootpathimg"><?php echo genPathCheckHtml('root', $ctrl->validPath['root']); ?></span>
            </div>

            <?php
            if ($ctrl->validPath['root'] && !empty($ctrl->permErrors['root'])) {
                echo '<div id="rootperms" class="x2-note">';
                echo CHECKING_PERMISSIONS . '<br><p>' . ERR_NEED_WRITE_ACCESS . '</p>';
                echo '<ul class="diags">';
                foreach ($ctrl->permErrors['root'] as $path => $result) {
                    if ($result) {
                        echo '<li class="success">' . sprintf(IS_WRITABLE, $path) . '</li>';
                    } else {
                        echo '<li class="failure">' . sprintf(IS_NOT_WRITABLE, $path) . '</li>';
                    }
                }
                echo '</ul></div>';
            } else {
                echo '<div id="rootperms" class="x2-note" style="display: none;"></div>';
            }
            ?>

            <div class="form-group">
                <label for="tmp"><?php echo API_TMP_PATH_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_TMP_PATH_HELP; ?></div>
                <input type="text" class="form-control" name="tmp" id="tmp" value="<?php echo $ctrl->apiPath['tmp']; ?>" onchange="updPath('tmp', this.value)"/>
                <span id="tmppathimg"><?php echo genPathCheckHtml('tmp', $ctrl->validPath['tmp']); ?></span>
            </div>
            <?php
            if ($ctrl->validPath['tmp'] && !empty($ctrl->permErrors['tmp'])) {
                echo '<div id="tmpperms" class="x2-note">';
                echo CHECKING_PERMISSIONS . '<br><p>' . ERR_NEED_WRITE_ACCESS . '</p>';
                echo '<ul class="diags">';
                foreach ($ctrl->permErrors['tmp'] as $path => $result) {
                    if ($result) {
                        echo '<li class="success">' . sprintf(IS_WRITABLE, $path) . '</li>';
                    } else {
                        echo '<li class="failure">' . sprintf(IS_NOT_WRITABLE, $path) . '</li>';
                    }
                }
                echo '</ul></div>';
            } else {
                echo '<div id="tmpperms" class="x2-note" style="display: none;"></div>';
            }
            ?>

            <div class="form-group">
                <label class="xolabel" for="lib"><?php echo API_LIB_PATH_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_LIB_PATH_HELP; ?></div>
                <input type="text" class="form-control" name="lib" id="lib" value="<?php echo $ctrl->apiPath['lib']; ?>" onchange="updPath('lib', this.value)"/>
                <span id="libpathimg"><?php echo genPathCheckHtml('lib', $ctrl->validPath['lib']); ?></span>
            </div>

            <div id="libperms" class="x2-note" style="display: none;"></div>
        </div>
    </div>


    <div class="panel panel-info">
        <div class="panel-heading"><?php echo API_URLS; ?></div>
        <div class="panel-body">

            <div class="form-group">
                <label class="xolabel" for="url"><?php echo API_URL_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_URL_HELP; ?></div>
                <input type="text" class="form-control" name="URL" id="url" value="<?php echo $ctrl->apiUrl; ?>" onchange="removeTrailing('url', this.value)"/>
            </div>

            <div class="form-group">
                <label class="xolabel" for="cookie_domain"><?php echo API_COOKIE_DOMAIN_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_COOKIE_DOMAIN_HELP; ?></div>
                <input type="text" class="form-control" name="COOKIE_DOMAIN" id="cookie_domain" value=".<?php echo $ctrl->apiCookieDomain; ?>" onchange="removeTrailing('url', this.value)"/>
            </div>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading"><?php echo SSL_APACHE; ?></div>
        <div class="panel-body">

			<div class="form-group">
                <label class="xolabel" for="ssl_certificates"><?php echo API_SSL_CERTIFICATES_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_SSL_CERTIFICATES_HELP; ?></div>
                <input type="text" class="form-control" name="ssl_certificates" id="ssl_certificates" value="<?php echo $ctrl->apiPath['ssl_certificates']; ?>" onchange="updPath('ssl_certificates', this.value)"/>
                <span id="ssl_certificatespathimg"><?php echo genPathCheckHtml('ssl_certificates', $ctrl->validPath['ssl_certificates']); ?></span>
            </div>
                        
            <div class="form-group">
                <label class="xolabel" for="root_domain"><?php echo API_ROOT_DOMAIN_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_ROOT_DOMAIN_HELP; ?></div>
                <input type="text" class="form-control" name="root_domain" id="root_domain" value="<?php echo $ctrl->apiRootDomain; ?>"/>
            </div>
                        
            <div class="form-group">
                <label class="xolabel" for="email_domain"><?php echo API_EMAIL_DOMAIN_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_EMAIL_DOMAIN_HELP; ?></div>
                <input type="text" class="form-control" name="email_domain" id="email_domain" value="<?php echo $ctrl->apiEmailDomain; ?>"/>
            </div>
            
            <div class="form-group">
                <label class="xolabel" for="www"><?php echo API_WWW_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_WWW_HELP; ?></div>
                <input type="text" class="form-control" name="www" id="www" value="<?php echo $ctrl->apiPath['www']; ?>" onchange="updPath('www', this.value)"/>
                <span id="wwwpathimg"><?php echo genPathCheckHtml('www', $ctrl->validPath['www']); ?></span>
            </div>

            <div class="form-group">
                <label class="xolabel" for="sites_available"><?php echo API_SITES_AVAILABLE_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_SITES_AVAILABLE_HELP; ?></div>
                <input type="text" class="form-control" name="sites_available" id="sites_available" value="<?php echo $ctrl->apiPath['sites_available']; ?>" onchange="updPath('sites_available', this.value)"/>
                <span id="sites_availablepathimg"><?php echo genPathCheckHtml('sites_available', $ctrl->validPath['sites_available']); ?></span>
            </div>
            
            <div class="form-group">
                <label class="xolabel" for="awstats"><?php echo API_AWSTATS_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_AWSTATS_HELP; ?></div>
                <input type="text" class="form-control" name="awstats" id="awstats" value="<?php echo $ctrl->apiPath['awstats']; ?>" onchange="updPath('awstats', this.value)"/>
                <span id="awstatspathimg"><?php echo genPathCheckHtml('awstats', $ctrl->validPath['awstats']); ?></span>
            </div>
        </div>
    </div>
<?php
$content = ob_get_contents();
ob_end_clean();

include './include/install_tpl.php';
