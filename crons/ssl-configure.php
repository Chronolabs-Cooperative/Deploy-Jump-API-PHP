<?php
sleep(mt_rand(1, 59));

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';

$start = time();
if ($staters = APICache::read('apache2-configure'))
{
    $staters[] = $start;
    sort($staters, SORT_ASC);
    if (count($starters)>50)
        unset($starters[0]);
        sort($staters, SORT_ASC);
        APICache::write('find-mx-services', $staters, 3600 * 24 * 7 * 4 * 6);
        $keys = array_key(array_reverse($starters));
        $avg = array();
        foreach(array_reverse($starters) as $key => $starting) {
            if (isset($keys[$key - 1])) {
                $avg[] = abs($starting - $starters[$keys[$key - 1]]);
            }
        }
        if (count($avg) > 0 ) {
            foreach($avg as $average)
                $seconds += $average;
                $seconds = $seconds / count($avg);
        } else
            $seconds = 1800;
} else {
    APICache::write('apache2-configure', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `root-ssl-csr-file` LIKE ''";
$result = $GLOBALS['APIDB']->queryF($sql);
while($domain = $GLOBALS['APIDB']->fetchArray($result)) {
    $sh = array();
    if (!is_dir(dirname(API_SSL_CERTIFICATES_PATH) . DS . 'keys'))
        $sh[] = 'mkdir ' . dirname(API_SSL_CERTIFICATES_PATH) . DS . 'keys';
    if (!is_dir(dirname(API_SSL_CERTIFICATES_PATH) . DS . 'csr'))
        $sh[] = 'mkdir ' . dirname(API_SSL_CERTIFICATES_PATH) . DS . 'csr';
    $sh[] = 'openssl req -nodes -newkey rsa:2048 -keyout ' . ($rootkey = dirname(API_SSL_CERTIFICATES_PATH) . DS . 'keys' . DS . 'star.'.$domain['domain'].'.key') . ' -out ' . ($rootcsr = dirname(API_SSL_CERTIFICATES_PATH) . DS . 'csr' . DS . 'star.'.$domain['domain'].'.csr') . ' -subj "/C=AU/ST=New South Wales/L=Sydney, Australia/O=' . API_LICENSE_COMPANY . '/OU=IT Department/CN=*.'.$domain['domain'].'"';
    $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'ssl-domain.php.txt');
    $php = str_replace('%domainid', $domain['id'], $php);
    $php = str_replace('%basis', '*.'.$domain['domain'], $php);
    $php = str_replace('%sslkey', $rootkey, $php);
    $php = str_replace('%sslcsr', $rootcsr, $php);
    $php = str_replace('%fieldkey', 'root-ssl-certificate-key-file', $php);
    $php = str_replace('%fieldcsr', 'root-ssl-csr-file', $php);
    file_put_contents($file = __DIR__ . DS . 'ssl-domain-star.' . $domain['domain'] . '.php', $php);
    $sh[] = 'php -q "' . $file . '"';
    file_put_contents($cmd = __DIR__ . DS . 'configure-ssl-star.' . $domain['domain'] . '.sh', implode("\n", $sh));
    $sh = array();
    if (!file_exists($file = __DIR__ . DS . 'configure.sh'))
        $sh[] = "rm " . $file . "";
    else {
        $sh = explode("\n", file_get_contents($file));
    }
    $sh[] = "sh '$cmd'";
    file_put_contents($file, implode("\n", $sh));
}
    

$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE `root-ssl-csr-file` LIKE ''";
$result = $GLOBALS['APIDB']->queryF($sql);
while($jump = $GLOBALS['APIDB']->fetchArray($result)) {
    $hostname = $jump['sub-domain'] . '.' . $jump['hostname'];
    $sh = array();
    if (!is_dir(dirname(API_SSL_CERTIFICATES_PATH) . DS . 'keys'))
        $sh[] = 'mkdir ' . dirname(API_SSL_CERTIFICATES_PATH) . DS . 'keys';
    if (!is_dir(dirname(API_SSL_CERTIFICATES_PATH) . DS . 'csr'))
        $sh[] = 'mkdir ' . dirname(API_SSL_CERTIFICATES_PATH) . DS . 'csr';
    $sh[] = 'openssl req -nodes -newkey rsa:2048 -keyout ' . ($rootkey = dirname(API_SSL_CERTIFICATES_PATH) . DS . 'keys' . DS . 'star.'.$hostname.'.key') . ' -out ' . ($rootcsr = dirname(API_SSL_CERTIFICATES_PATH) . DS . 'csr' . DS . 'star.'.$hostname.'.csr') . ' -subj "/C=AU/ST=New South Wales/L=Sydney, Australia/O=' . API_LICENSE_COMPANY . '/OU=IT Department/CN=*.'.$hostname.'"';
    $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'ssl-jump.php.txt');
    $php = str_replace('%jumpid', $jump['id'], $php);
    $php = str_replace('%basis', '*.'.$hostname, $php);
    $php = str_replace('%sslkey', $rootkey, $php);
    $php = str_replace('%sslcsr', $rootcsr, $php);
    $php = str_replace('%fieldkey', 'root-ssl-certificate-key-file', $php);
    $php = str_replace('%fieldcsr', 'root-ssl-csr-file', $php);
    file_put_contents($file = __DIR__ . DS . 'ssl-jump-star.' . $hostname . '.php', $php);
    $sh[] = 'php -q "' . $file . '"';
    file_put_contents($cmd = __DIR__ . DS . 'configure-ssl-star.' . $hostname . '.sh', implode("\n", $sh));
    $sh = array();
    if (!file_exists($file = __DIR__ . DS . 'configure.sh'))
        $sh[] = "rm " . $file . "";
    else {
        $sh = explode("\n", file_get_contents($file));
    }
    $sh[] = "sh '$cmd'";
    file_put_contents($file, implode("\n", $sh));
}
