<?php
sleep(mt_rand(1, 59));

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';

$start = time();
if ($staters = APICache::read('configure'))
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
    APICache::write('configure', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

$files = APILists::getFileListAsArray(__DIR__);
$sh = array();
if (!file_exists($file = __DIR__ . DS . 'configure.sh'))
    $sh[] = "rm " . $file . "";
else {
    $sh = explode("\n", file_get_contents($file));
}
foreach($files as $key => $file) {
    if (substr($file, 0, strlen('configure-')) == 'configure-' && substr($file, strlen($file) - 3) == '.sh') {
        foreach($sh as $cmd) {
            if (strpos($cmd, $file)) {
                unset($files[$key]);
            }
        }
    } else {
        unset($files[$key]);;
    }
}
foreach($files as $key => $file)
    $sh[] = "sh '" . __DIR__ . DS . $file ."'";
file_put_contents(__DIR__ . DS . 'configure.sh', implode("\n", $sh));
?>