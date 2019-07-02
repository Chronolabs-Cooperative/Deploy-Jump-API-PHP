<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';

$start = time();
if ($staters = APICache::read('apache2-ssl-configure'))
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
    APICache::write('apache2-ssl-configure', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

$domainids = array();
$result = $GLOBALS['APIDB']->queryF("SELECT `id` FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `ssl` = 'Yes'");
while( $row = $GLOBALS['APIDB']->fetchArray($result))
    $domainids[$row['id']] = $row['id'];

if (count($domainids) > 0) {
    $sh = array();
    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE (`apache2-ssl-configured` = 0 OR `apache2-ssl-configured` > UNIX_TIMESTAMP()) AND `domain-id` IN (" . implode(', ', $domainids) . ')';
    $result = $GLOBALS['APIDB']->queryF($sql);
    while($jump = $GLOBALS['APIDB']->fetchArray($result)) {
        $domain = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `id` = '" . $jump['domain-id'] . "'"));
        $sh[] = 'unlink "' . __DIR__ . DS . 'configure-apache2-ssl-' . ($hostname = $jump['sub-domain'] . '.' . $jump['hostname']) . '.sh"';
        $sh[] = 'a2dissite ' . $hostname. '-ssl';
        $sh[] = 'rm ' . API_SITES_AVAILABLE_PATH . DIRECTORY_SEPARATOR . $hostname . '-ssl.conf';
        if (file_exists($file = __DIR__ . DS . 'apache2-' . $hostname . '-ssl.conf'))
            unlink($file);
        $conf = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'apache2-ssl.conf.txt');
        $conf = str_replace('%adminemail', $domain['admin-email'], $conf);
        $conf = str_replace('%hostname', $hostname, $conf);
        $conf = str_replace('%hostname', $hostname, str_replace('%docroot', $jump['apache2-path'], $conf));
        $conf = str_replace('%hostname', $hostname, str_replace('%errorlog', $jump['apache2-error-log'], $conf));
        $conf = str_replace('%hostname', $hostname, str_replace('%customlog', $jump['apache2-access-log'], $conf));
        $conf = str_replace('%sslpath', API_SSL_CERTIFICATES_PATH, str_replace('%rootsslcertfile', $domain['root-ssl-certificate-file'], $conf));
        $conf = str_replace('%sslpath', API_SSL_CERTIFICATES_PATH, str_replace('%rootsslcertkey', $domain['root-ssl-certificate-key-file'], $conf));
        $conf = str_replace('%sslpath', API_SSL_CERTIFICATES_PATH, str_replace('%rootsslchainfile', $domain['root-ssl-ca-certificate-file'], $conf));
        $conf = str_replace('%sslpath', API_SSL_CERTIFICATES_PATH, str_replace('%subsslcertfile', $domain['sub-ssl-certificate-file'], $conf));
        $conf = str_replace('%sslpath', API_SSL_CERTIFICATES_PATH, str_replace('%subsslcertkey', $domain['sub-ssl-certificate-key-file'], $conf));
        $conf = str_replace('%sslpath', API_SSL_CERTIFICATES_PATH, str_replace('%subsslchainfile', $domain['sub-ssl-ca-certificate-file'], $conf));
        file_put_contents($file, $conf);
        $sh[] = 'mv "' . $file . '" "' . API_SITES_AVAILABLE_PATH . DIRECTORY_SEPARATOR . $hostname . '-ssl.conf"';
        $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'zones.php.txt');
        $php = str_replace('%hostname', $hostname, $php);
        $php = str_replace('%domain', $domain['domain'], $php);
        $php = str_replace('%zoneurl', API_ZONES_API_URL, $php);
        $php = str_replace('%zoneuser', API_ZONES_USERNAME_URL, $php);
        $php = str_replace('%zonepass', API_ZONES_PASSWORD_URL, $php);
        file_put_contents($file = __DIR__ . DS . 'zones-ssl-' . $hostname . '.php', $php);
        $sh[] = 'php -q "' . $file . '"';
        $sh[] = 'cp "' . str_replace('%hostname', $hostname, $jump['apache2-path']) . '/deployment.php" "' . __DIR__ . "/deployment-$hostname.php\"";
        $sh[] = 'rm -Rf "' . str_replace('%hostname', $hostname, $jump['apache2-path']) . '"';
        $sh[] = 'git clone https://github.com/Chronolabs-Cooperative/Jump-API-PHP.git "' . str_replace('%hostname', $hostname, $jump['apache2-path']) . '"';
        $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'git-cloned.php.txt');
        $php = str_replace('%jumpid', $jump['id'], $php);
        file_put_contents($file = __DIR__ . DS . 'git-cloned-' . $hostname . '.php', $php);
        $sh[] = 'php -q "' . $file . '"';
        $sh[] = 'chown -Rf www-data:www-data "' . str_replace('%hostname', $hostname, $jump['apache2-path']) . '"';
        $sh[] = 'chmod -Rf 0777 "' . str_replace('%hostname', $hostname, $jump['apache2-path']) . '"';
        $sh[] = 'rm "' . str_replace('%hostname', $hostname, $jump['apache2-path']) . '/deployment.php';
        $sh[] = 'mv "' . __DIR__ . "/deployment-$hostname.php\" \"" . str_replace('%hostname', $hostname, $jump['apache2-path']) . '/deployment.php\"';
        $sh[] = "sed -i 's/snails.email/" . $domain['domain'] . "/g' \"" . str_replace('%hostname', $hostname, $jump['apache2-path']) . '/.htaccess\"';
        $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'htaccess-ssl.php.txt');
        $php = str_replace('%file', str_replace('%hostname', $hostname, $jump['apache2-path']) . '/.htaccess', $php);
        file_put_contents($file = __DIR__ . DS . 'htaccess-ssl-' . $hostname . '.php', $php);
        $sh[] = 'php -q "' . $file . '"';
        $sh[] = 'a2ensite ' . ($hostname = $jump['sub-domain'] . '.' . $jump['hostname']) . '-ssl';
        $sh[] = 'service apache2 reload';
        $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'apache2-ssl.php.txt');
        $php = str_replace('%jumpid', $jump['id'], $php);
        file_put_contents($file = __DIR__ . DS . 'apache2-ssl-' . $hostname . '.php', $php);
        $sh[] = 'php -q "' . $file . '"';
        file_put_contents(__DIR__ . DS . 'configure-apache2-ssl-' . $hostname . '.sh', implode("\n", $sh));
    }
}