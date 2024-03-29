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

define('SHOW_HIDE_HELP', 'Show/hide help text');
// License
define('LICENSE_NOT_WRITEABLE', 'License file "%s" is NOT writable!');
define('LICENSE_IS_WRITEABLE', '%s License is writable.');
define('ADMIN_COMPANY_LABEL', 'Admin Organisation');
define('_API_FATAL_MESSAGE', 'Fault/Fatal Errors has occured: %s');
// Configuration check page
define('SERVER_API', 'Server API');
define('PHP_EXTENSION', '%s extension');
define('CHAR_ENCODING', 'Character encoding');
define('XML_PARSING', 'XML parsing');
define('REQUIREMENTS', 'Requirements');
define('_PHP_VERSION', 'PHP version');
define('RECOMMENDED_SETTINGS', 'Recommended settings');
define('RECOMMENDED_EXTENSIONS', 'Recommended extensions');
define('SETTING_NAME', 'Setting name');
define('RECOMMENDED', 'Recommended');
define('CURRENT', 'Current');
define('RECOMMENDED_EXTENSIONS_MSG', 'These extensions are not required for normal use, but may be necessary to explore
    some specific features (like the multi-language or RSS support). Thus, it is recommended to have them installed.');
define('NONE', 'None');
define('SUCCESS', 'Success');
define('WARNING', 'Warning');
define('FAILED', 'Failed');
// Titles (main and pages)
define('REST_APIS', 'URL for REST API\'s');
define('ZONES_API', 'URL, Username + Password for Zones DNS REST API');
define('API_INSTALL_WIZARD', 'API Installation Wizard');
define('LANGUAGE_SELECTION', 'Language selection');
define('LANGUAGE_SELECTION_TITLE', 'Select your language');        // L128
define('INTRODUCTION', 'Introduction');
define('INTRODUCTION_TITLE', 'Welcome to the API Installation Wizard');        // L0
define('CONFIGURATION_CHECK', 'Configuration check');
define('CONFIGURATION_CHECK_TITLE', 'Checking your server configuration');
define('PATHS_SETTINGS', 'Paths settings');
define('PATHS_SETTINGS_TITLE', 'Paths settings');
define('EXTRA_SETTINGS', 'Extra settings + URLs');
define('EXTRA_SETTINGS_TITLE', 'Extra settings + URLs');
define('API_IMPORT_JUMPAPI', 'Current Import of Existing Short URL REST API\'s');
define('IMPORT_JUMPAPI', 'Import Jump API');
define('IMPORT_JUMPAPI_TITLE', 'Import Jump API + Deployment');
define('DATABASE_CONNECTION', 'Database connection');
define('DATABASE_CONNECTION_TITLE', 'Database connection');
define('DATABASE_CONFIG', 'Database configuration');
define('DATABASE_CONFIG_TITLE', 'Database configuration');
define('CONFIG_SAVE', 'Save Configuration');
define('CONFIG_SAVE_TITLE', 'Saving your system configuration');
define('TABLES_CREATION', 'Tables creation');
define('TABLES_CREATION_TITLE', 'Database tables creation');
define('INITIAL_SETTINGS', 'Initial settings');
define('INITIAL_SETTINGS_TITLE', 'Please enter your initial settings');
define('TMP_INSERTION', 'Data insertion');
define('TMP_INSERTION_TITLE', 'Saving your settings to the tmpbase');
define('WELCOME', 'Welcome');
define('WELCOME_TITLE', 'Welcome to your API site');        // L0
// Settings (labels and help text)
define('API_PATHS', 'API Physical paths');
define('API_URLS', 'Web locations');
define('SSL_APACHE', 'SSL, Apache2 Paths');
define('API_ROOT_PATH_LABEL', 'API documents root physical path');
define('API_ROOT_PATH_HELP', 'Physical path to the API documents (served) directory WITHOUT trailing slash');
define('API_LIB_PATH_LABEL', 'API library directory');
define('API_LIB_PATH_HELP', 'Physical path to the API library directory WITHOUT trailing slash, for forward compatibility. Locate the folder out of ' . API_ROOT_PATH_LABEL . ' to make it secure.');
define('API_TMP_PATH_LABEL', 'API tmp files directory');
define('API_TMP_PATH_HELP', 'Physical path to the API tmp files (writable) directory WITHOUT trailing slash, for forward compatibility. Locate the folder out of ' . API_ROOT_PATH_LABEL . ' to make it secure.');
define('API_URL_LABEL', 'Website location (URL)'); // L56
define('API_URL_HELP', 'Main URL that will be used to access your API installation'); // L58
define('API_STRATA_LABEL', 'Internet Domain+Fallout REST Services API (see: <a href="https://github.com/Chronolabs-Cooperative/Strata-API-PHP" target="_blank">github.com...</a>)');
define('API_STRATA_HELP', 'Physical URL to the REST API as installed from github.com');
define('API_WHOIS_LABEL', 'IP+Domain Whois REST Services API (see: <a href="https://github.com/Chronolabs-Cooperative/Whois-API-PHP" target="_blank">github.com...</a>)');
define('API_WHOIS_HELP', 'Physical URL to the REST API as installed from github.com');
define('API_LOOKUPS_LABEL', 'IPv4, IPv6 Locational Lookups REST Services API (see: <a href="https://github.com/Chronolabs-Cooperative/Lookups-API-PHP" target="_blank">github.com...</a>)');
define('API_LOOKUPS_HELP', 'Physical URL to the REST API as installed from github.com');
define('API_PLACES_LABEL', 'Locational Places REST Services API (see: <a href="https://github.com/Chronolabs-Cooperative/Places-API-PHP" target="_blank">github.com...</a>)');
define('API_PLACES_HELP', 'Physical URL to the REST API as installed from github.com');
define('API_MASTERHOST_LABEL', 'The URL to the physical master peering hostname and URL basenamed');
define('API_MASTERHOST_HELP', 'Physical URL to this REST API as installed from github.com as the master peering hostname + URL path');
define('API_SSL_CERTIFICATES_LABEL', 'SSL Certificates Storage Paths');
define('API_SSL_CERTIFICATES_HELP', 'Physical path to the SSL Certificates (served) directory WITHOUT trailing slash');
define('API_ROOT_DOMAIN_LABEL', 'Root WWW Domain');
define('API_ROOT_DOMAIN_HELP', 'Internet hostname path to the root of the WWW directory WITHOUT trailing slash, for forward compatibility. Locate the folder out of ' . API_ROOT_PATH_LABEL . ' to make it secure.');
define('API_WWW_LABEL', 'Root WWW Documents');
define('API_WWW_HELP', 'Physical path to the Root of the WWW directory WITHOUT trailing slash, for forward compatibility. Locate the folder out of ' . API_ROOT_PATH_LABEL . ' to make it secure.');
define('API_SITES_AVAILABLE_LABEL', 'Apache2 `sites-available` directory');
define('API_SITES_AVAILABLE_HELP', 'Physical path to the Apahce2 `sites-available` (writable) directory WITHOUT trailing slash, for forward compatibility. Locate the folder out of ' . API_ROOT_PATH_LABEL . ' to make it secure.');
define('API_AWSTATS_LABEL', 'Physical path to the AWSTATS Configuration');
define('API_AWSTATS_HELP', 'Physical path to the AWSTATS Configuration');
define('API_ZONES_LABEL', 'Internet URL to Zones API');
define('API_ZONES_HELP', 'Physical URL to the REST API as installed from github.com');
define('API_ZONES_USERNAME_LABEL', 'Zones REST API - Username');
define('API_ZONES_USERNAME_HELP', 'The username for the zones api as from github.com/Zones-API-PHP');
define('API_ZONES_PASSWORD_LABEL', 'Zones REST API - Password');
define('API_ZONES_PASSWORD_HELP', 'The password for the zones api as from github.com/Zones-API-PHP');
define('LEGEND_CONNECTION', 'Server connection');
define('LEGEND_DATABASE', 'Database'); // L51
define('DB_HOST_LABEL', 'Server hostname');    // L27
define('DB_HOST_HELP', 'Hostname of the tmpbase server. If you are unsure, <em>localhost</em> works in most cases'); // L67
define('DB_USER_LABEL', 'User name');    // L28
define('DB_USER_HELP', 'Name of the user account that will be used to connect to the tmpbase server'); // L65
define('DB_PASS_LABEL', 'Password');    // L52
define('DB_PASS_HELP', 'Password of your tmpbase user account'); // L68
define('DB_NAME_LABEL', 'Database name');    // L29
define('DB_NAME_HELP', 'The name of tmpbase on the host. The installer will attempt to create the tmpbase if not exist'); // L64
define('DB_CHARSET_LABEL', 'Database character set');
define('DB_CHARSET_HELP', 'MySQL includes character set support that enables you to store tmp using a variety of character sets and perform comparisons according to a variety of collations.');
define('DB_COLLATION_LABEL', 'Database collation');
define('DB_COLLATION_HELP', 'A collation is a set of rules for comparing characters in a character set.');
define('DB_PREFIX_LABEL', 'Table prefix');    // L30
define('DB_PREFIX_HELP', 'This prefix will be added to all new tables created to avoid name conflicts in the tmpbase. If you are unsure, just keep the default'); // L63
define('DB_PCONNECT_LABEL', 'Use persistent connection');    // L54
define('DB_PCONNECT_HELP', "Default is 'No'. Leave it blank if you are unsure"); // L69
define('DB_DATABASE_LABEL', 'Database');
define('LEGEND_ADMIN_ACCOUNT', 'Administrator account');
define('ADMIN_LOGIN_LABEL', 'Admin login'); // L37
define('ADMIN_EMAIL_LABEL', 'Admin e-mail'); // L38
define('ADMIN_PASS_LABEL', 'Admin password'); // L39
define('ADMIN_CONFIRMPASS_LABEL', 'Confirm password'); // L74
// Buttons
define('BUTTON_PREVIOUS', 'Previous'); // L42
define('BUTTON_NEXT', 'Continue'); // L47
// Messages
define('API_FOUND', '%s found');
define('CHECKING_PERMISSIONS', 'Checking file and directory permissions...'); // L82
define('IS_NOT_WRITABLE', '%s is NOT writable.'); // L83
define('IS_WRITABLE', '%s is writable.'); // L84
define('API_PATH_FOUND', 'Path found.');
//define('READY_CREATE_TABLES', 'No API tables were detected.<br>The installer is now ready to create the API system tables.');
define('API_TABLES_FOUND', 'The API system tables already exist in your tmpbase.'); // L131
define('API_TABLES_CREATED', 'API system tables have been created.');
//define('READY_INSERT_TMP', 'The installer is now ready to insert initial tmp into your tmpbase.');
//define('READY_SAVE_MAINFILE', 'The installer is now ready to save the specified settings to <em>mainfile.php</em>.');
define('SAVED_MAINFILE', 'Settings saved');
define('SAVED_MAINFILE_MSG', 'The installer has saved the specified settings to <em>mainfile.php</em> and <em>secure.php</em>.');
define('TMP_ALREADY_INSERTED', 'API tmp found in tmpbase.');
define('TMP_INSERTED', 'Initial tmp has been inserted into tmpbase.');
// %s is tmpbase name
define('DATABASE_CREATED', 'Database %s created!'); // L43
// %s is table name
define('TABLE_NOT_CREATED', 'Unable to create table %s'); // L118
define('TABLE_CREATED', 'Table %s created.'); // L45
define('ROWS_INSERTED', '%d entries inserted to table %s.'); // L119
define('ROWS_FAILED', 'Failed inserting %d entries to table %s.'); // L120
define('TABLE_ALTERED', 'Table %s updated.'); // L133
define('TABLE_NOT_ALTERED', 'Failed updating table %s.'); // L134
define('TABLE_DROPPED', 'Table %s dropped.'); // L163
define('TABLE_NOT_DROPPED', 'Failed deleting table %s.'); // L164
// Error messages
define('ERR_COULD_NOT_ACCESS', 'Could not access the specified folder. Please verify that it exists and is readable by the server.');
define('ERR_NO_API_FOUND', 'No API installation could be found in the specified folder.');
define('ERR_INVALID_EMAIL', 'Invalid Email'); // L73
define('ERR_REQUIRED', 'Information is required.'); // L41
define('ERR_PASSWORD_MATCH', 'The two passwords do not match');
define('ERR_NEED_WRITE_ACCESS', 'The server must be given write access to the following files and folders<br>(i.e. <em>chmod 775 directory_name</em> on a UNIX/LINUX server)<br>If they are not available or not created correctly, please create manually and set proper permissions.');
define('ERR_NO_DATABASE', 'Could not create tmpbase. Contact the server administrator for details.'); // L31
define('ERR_NO_DBCONNECTION', 'Could not connect to the tmpbase server.'); // L106
define('ERR_WRITING_CONSTANT', 'Failed writing constant %s.'); // L122
define('ERR_COPY_MAINFILE', 'Could not copy the distribution file to %s');
define('ERR_WRITE_MAINFILE', 'Could not write into %s. Please check the file permission and try again.');
define('ERR_READ_MAINFILE', 'Could not open %s for reading');
define('ERR_INVALID_DBCHARSET', "The charset '%s' is not supported.");
define('ERR_INVALID_DBCOLLATION', "The collation '%s' is not supported.");
define('ERR_CHARSET_NOT_SET', 'Default character set is not set for API tmpbase.');
define('_INSTALL_CHARSET', 'UTF-8');
define('SUPPORT', 'Support');
define('LOGIN', 'Authentication');
define('LOGIN_TITLE', 'Authentication');
define('USER_LOGIN', 'Administrator Login');
define('USERNAME', 'Username :');
define('PASSWORD', 'Password :');
define('ICONV_CONVERSION', 'Character set conversion');
define('ZLIB_COMPRESSION', 'Zlib Compression');
define('IMAGE_FUNCTIONS', 'Image functions');
define('IMAGE_METAS', 'Image meta tmp (exif)');
define('FILTER_FUNCTIONS', 'Filter functions');
define('ADMIN_EXIST', 'The administrator account already exists.');
define('CONFIG_SITE', 'Site configuration');
define('CONFIG_SITE_TITLE', 'Site configuration');
define('MODULES', 'Modules installation');
define('MODULES_TITLE', 'Modules installation');
define('THEME', 'Select theme');
define('THEME_TITLE', 'Select the default theme');
define('INSTALLED_MODULES', 'The following modules have been installed.');
define('NO_MODULES_FOUND', 'No modules found.');
define('NO_INSTALLED_MODULES', 'No module installed.');
define('THEME_NO_SCREENSHOT', 'No screenshot found');
define('IS_VALOR', ' => ');
// password message
define('PASSWORD_LABEL', 'Password strength');
define('PASSWORD_DESC', 'Password not entered');
define('PASSWORD_GENERATOR', 'Password generator');
define('PASSWORD_GENERATE', 'Generate');
define('PASSWORD_COPY', 'Copy');
define('PASSWORD_VERY_WEAK', 'Very Weak');
define('PASSWORD_WEAK', 'Weak');
define('PASSWORD_BETTER', 'Better');
define('PASSWORD_MEDIUM', 'Medium');
define('PASSWORD_STRONG', 'Strong');
define('PASSWORD_STRONGEST', 'Strongest');
//2.5.7
define('WRITTEN_LICENSE', 'Wrote API %s License Key: <strong>%s</strong>');
//2.5.8
define('CHMOD_CHGRP_REPEAT', 'Retry');
define('CHMOD_CHGRP_IGNORE', 'Use Anyway');
define('CHMOD_CHGRP_ERROR', 'Installer may not be able to write the configuration file %1$s.<p>PHP is writing files under user %2$s and group %3$s.<p>The directory %4$s/ has user %5$s and group %6$s');
//2.5.9
define("CURL_HTTP", "Client URL Library (cURL)");
define('API_COOKIE_DOMAIN_LABEL', 'Cookie Domain for the Website');
define('API_COOKIE_DOMAIN_HELP', 'Domain to set cookies. May be blank, the full host from the URL (www.example.com), or the registered domain without subdomains (example.com) to share across subdomains (www.example.com and blog.example.com.)');
define('INTL_SUPPORT', 'Internationalization functions');
define('API_SOURCE_CODE', "API on GitHub");
define('API_INSTALLING', 'Installing');
define('API_ERROR_ENCOUNTERED', 'Error');
define('API_ERROR_SEE_BELOW', 'See below for messages.');
define('MODULES_AVAILABLE', 'Available Modules');
define('INSTALL_THIS_MODULE', 'Add %s');

// Emails REST API
define('API_IMPORT_JUMPAPI', 'Import Jump API');
