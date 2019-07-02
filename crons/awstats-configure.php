<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';

$start = time();
if ($staters = APICache::read('awstats-configure'))
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
    APICache::write('awstats-configure', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

$jumpids = array();
$sh = array();
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE `awstats-configured` = 0 OR `awstats-configured` > UNIX_TIMESTAMP()";
$result = $GLOBALS['APIDB']->queryF($sql);
while($jump = $GLOBALS['APIDB']->fetchArray($result)) {
    $domain = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `id` = '" . $jump['domain-id'] . "'"));
    $sh[] = 'unlink "' . __DIR__ . DS . 'configure-awstats-' . ($hostname = $jump['sub-domain'] . '.' . $jump['hostname']) . '.sh"';
    $sh[] = 'rm ' . API_AWSTATS_PATH . DIRECTORY_SEPARATOR . 'awstats.' . $hostname . '.conf';
    if (!file_exists(API_AWSTATS_PATH . DS . ($file = 'awstats.' . $domain['domain'] . '.conf')))
    {
        $conf = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'awstats.domain.conf.txt');
        $conf = str_replace('%adminemail', $domain['admin-email'], $conf);
        $conf = str_replace('%awstatspath', API_AWSTATS_PATH, $conf);
        $conf = str_replace('%domain', $domain['domain'], $conf);
        $conf = str_replace('%logfile', $jump['apache2-access-log'], $conf);
        $conf = str_replace('%hostname', $hostname, $conf);
        $conf = str_replace('%wwwpath', API_WWW_PATH, $conf);
        file_put_contents(__DIR__ . DS . $file, $conf);
        $sh[] = 'mv "' . __DIR__ . DS . $file . "' '" . API_AWSTATS_PATH . DS . $file . "\"";
        $sh[] = 'chmod 0744 "' . API_AWSTATS_PATH . DS . $file . "\"";
        $sh[] = 'chown www-data:root "' . API_AWSTATS_PATH . DS . $file . "\"";
    }
    if (file_exists($file = __DIR__ . DS . 'awstats.' . $hostname . '.conf'))
        unlink($file);
    $conf = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'awstats.conf.txt');
    $conf = str_replace('%adminemail', $domain['admin-email'], $conf);
    $conf = str_replace('%awstatspath', API_AWSTATS_PATH, $conf);
    $conf = str_replace('%domain', $domain['domain'], $conf);
    $conf = str_replace('%logfile', $jump['apache2-access-log'], $conf);
    $conf = str_replace('%hostname', $hostname, $conf);
    $conf = str_replace('%wwwpath', API_WWW_PATH, $conf);
    file_put_contents($file, $conf);
    $sh[] = 'mv "' . __DIR__ . DS . $file . "' '" . API_AWSTATS_PATH . DS . basename($file) . "\"";
    $sh[] = 'chmod 0744 "' . API_AWSTATS_PATH . DS . basename($file) . "\"";
    $sh[] = 'chown www-data:root "' . API_AWSTATS_PATH . DS . basename($file) . "\"";
    if (!is_dir(API_WWW_PATH."/$hostname/awstats-data/"))
        mkdir(API_WWW_PATH."/$hostname/awstats-data/", 0777, true);
    $sh[] = '/usr/lib/cgi-bin/awstats.pl -config=' . $hostname . " -update ";
    $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'awstats.php.txt');
    $php = str_replace('%jumpid', $jump['id'], $php);
    file_put_contents($file = __DIR__ . DS . 'awstats-' . $hostname . '.php', $php);
    $sh[] = 'php -q "' . $file . '"';
    file_put_contents(__DIR__ . DS . 'configure-awstats-' . $hostname . '.sh', implode("\n", $sh));
}
    