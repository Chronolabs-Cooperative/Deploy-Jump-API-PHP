<?php

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';

$start = time();
if ($staters = APICache::read('awstats-crons'))
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
    APICache::write('awstats-crons', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

$domainids = array();
$sh = array();
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE `awstats-crons-configured` = 0 OR `awstats-crons-configured` < UNIX_TIMESTAMP()";
$result = $GLOBALS['APIDB']->queryF($sql);
while($jump = $GLOBALS['APIDB']->fetchArray($result)) {
    $domain = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `id` = '" . $jump['domain-id'] . "'"));
    $domainids[$domain['id']] = $domain['id'];
}

if (count($domainsids) > 0) {
    $jumpids = array();
    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE `domain-id` IN (" . implode(', ', $domainids) . ")";
    $result = $GLOBALS['APIDB']->queryF($sql);
    while($jump = $GLOBALS['APIDB']->fetchArray($result)) {
        $domain = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('domains') . "` WHERE `id` = '" . $jump['domain-id'] . "'"));
        $jumpids[$jump['id']] = $jump['id'];
        $sh[($hostname = $jump['sub-domain'] . '.' . $jump['hostname'])]['cmd'] = '/usr/lib/cgi-bin/awstats.pl -config=' . $hostname . " -update ";
        $sh[$hostname]['jumpid'] = $jump['id']; 
    }
}
$keys = array_keys($sh);
shuffle($keys);
shuffle($keys);
shuffle($keys);
shuffle($keys);

$step = (floor(count($sh) / 4) > 1 ? 4 : (floor(count($sh) / 3) > 1 ? 3 : (floor(count($sh) / 2) > 1 ? 2 : 1)));
$ii=1;
$shs = $cmds = array();
foreach($keys as $key) {
    $shs[$ii][$key] = $sh[$key]['cmd'];
    $cmds[$ii][$key] = 'php -q "' . ($file = __DIR__ . DS . 'configure-awstats-crons-' . $ii . '-' . $key . '.php') . "\"";
    $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'awstats-crons.php.txt');
    $php = str_replace('%jumpid', $jump['id'], $php);
    file_put_contents($file, $php);
    $ii++;
    if ($ii > $step)
        $ii = 1;
}

foreach($shs as $filenum => $values) {
    $sh = array();
    if (!file_exists($file = __DIR__ . DS . 'awstats-crons-' . $filenum . '.sh'))
        $sh[] = "rm \"" . $file . "\"";
    else {
        $sh = explode("\n", file_get_contents($file));
    }
    foreach($values as $hostname => $cmd) {
        $sh[] = $cmd;
        $sh[] = $cmds[$filenum][$hostname];
    }
    file_put_contents($file, implode("\n", $sh));
}