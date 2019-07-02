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

$ctrl = new PathStuffController($wizard->configs['apiPathDefault'], $wizard->configs['tmpPath']);
$ctrl->execute(__FILE__);
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
        <div class="panel-heading"><?php echo ZONES_API; ?></div>
        <div class="panel-body">

            <div class="form-group">
                <label class="xolabel" for="zones"><?php echo API_ZONES_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_ZONES_HELP; ?></div>
                <input type="text" class="form-control" name="ZONES" id="zones" value="<?php echo $wizard->configs['api_url']['zones']; ?>" onchange="removeTrailing('url', this.value)"/>
            </div>

            <div class="form-group">
                <label class="xolabel" for="zones_username"><?php echo API_ZONES_USERNAME_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_ZONES_USERNAME_HELP; ?></div>
                <input type="text" class="form-control" name="ZONES_USERNAME" id="zones_username" value=""/>
            </div>

            <div class="form-group">
                <label class="xolabel" for="zones_password"><?php echo API_ZONES_PASSWORD_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_ZONES_PASSWORD_HELP; ?></div>
                <input type="password" class="form-control" name="ZONES_PASSWORD" id="zones_password" value=""/>
            </div>

        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading"><?php echo REST_APIS; ?></div>
        <div class="panel-body">

			<div class="form-group">
                <label class="xolabel" for="strata"><?php echo API_STRATA_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_STRATA_HELP; ?></div>
                <input type="text" class="form-control" name="STRATA" id="strata" value="<?php echo $wizard->configs['api_url']['strata']; ?>" onchange="removeTrailing('url', this.value)"/>
            </div>
                        
            <div class="form-group">
                <label class="xolabel" for="whois"><?php echo API_WHOIS_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_WHOIS_HELP; ?></div>
                <input type="text" class="form-control" name="WHOIS" id="whois" value="<?php echo $wizard->configs['api_url']['whois']; ?>" onchange="removeTrailing('url', this.value)"/>
            </div>
            
            <div class="form-group">
                <label class="xolabel" for="masterhost"><?php echo API_MASTERHOST_LABEL; ?></label>
                <div class="xoform-help alert alert-info"><?php echo API_MASTERHOST_HELP; ?></div>
                <input type="text" class="form-control" name="MASTERHOST" id="masterhost" value="<?php echo $wizard->configs['api_url']['masterhost']; ?>" onchange="removeTrailing('url', this.value)"/>
            </div>
        </div>
    </div>
<?php
$content = ob_get_contents();
ob_end_clean();

include './include/install_tpl.php';
