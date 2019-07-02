<?php
sleep(mt_rand(1, 59));

define("API_GUTHUB_COMMITS", "https://github.com/Chronolabs-Cooperative/Jump-API-PHP/commits/master");

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'simple_html_dom.php';

$start = time();
if ($staters = APICache::read('github-configure'))
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
    APICache::write('github-configure', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

$html = file_get_html(API_GUTHUB_COMMITS);
foreach( $html->find('relative-time') as $id => $element) {
    if (!isset($timestamp))
        $timestamp = strtotime($element->getAttribute('datetime'), time());
}
if ($timestamp > 0) {
    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('jumps') . "` WHERE `apache2-configured` > 0 AND (`github-pulled` < $timestamp)";
    $result = $GLOBALS['APIDB']->queryF($sql);
    while($jump = $GLOBALS['APIDB']->fetchArray($result)) {
        $hostname = $jump['sub-domain'] . '.' . $jump['hostname'];
        $sh = array();
        $sh[] = 'cd ' . str_replace('%wwwpath', API_WWW_PATH, str_replace('%hostname', $hostname, $jump['apache2-path'])) . '';
        $sh[] = 'git pull';
        $php = file_get_contents(dirname(__DIR__) . DS . 'include' . DS . 'data' . DS . 'git-pulled.php.txt');
        $php = str_replace('%jumpid', $jump['id'], $php);
        file_put_contents($file = __DIR__ . DS . 'git-pulled-' . $hostname . '.php', $php);
        $sh[] = 'php -q "' . $file . '"';
        file_put_contents($cmd = __DIR__ . DS . 'configure-github-pulled-' . $hostname . '.sh', implode("\n", $sh));
        $sh = array();
        if (!file_exists($file = __DIR__ . DS . 'configure.sh'))
            $sh[] = "rm " . $file . "";
        else {
            $sh = explode("\n", file_get_contents($file));
        }
        $sh[] = "sh '$cmd'";
        file_put_contents($file, implode("\n", $sh));
    }
}