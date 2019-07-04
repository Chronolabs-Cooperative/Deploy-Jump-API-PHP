<?php
mt_srand(mt_rand(-time(), time()), MT_RAND_MT19937);
mt_srand(mt_rand(-time(), time()), MT_RAND_MT19937);
mt_srand(mt_rand(-time() * time(), time() * time()), MT_RAND_MT19937);
mt_srand(mt_rand(-time() * time(), time() * time()), MT_RAND_MT19937);
mt_srand(mt_rand(-time() * time() * time(), time() * time() * time()), MT_RAND_MT19937);
mt_srand(mt_rand(-time() * time() * time() * time(), time() * time() * time() * time()), MT_RAND_MT19937);

sleep(mt_rand(1,59));

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';

$start = time();
if ($staters = APICache::read('jumpapi-pgpkeys'))
{
    $staters[] = $start;
    sort($staters, SORT_ASC);
    if (count($starters)>50)
        unset($starters[0]);
        sort($staters, SORT_ASC);
        APICache::write('jumpapi-pgpkeys', $staters, 3600 * 24 * 7 * 4 * 6);
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
    APICache::write('jumpapi-pgpkeys', array(0=>$start), 3600 * 24 * 7 * 4 * 6);
    $seconds = 1800;
}

foreach(APILists::getFileListAsArray($path = API_WWW_PATH . DIRECTORY_SEPARATOR . API_EMAIL_DOMAIN . DIRECTORY_SEPARATOR . '.pgp-keys') as $file) {
    if (substr($file, strlen($file) - 3) == '.sh')
        echo @shell_exec('sh "' . $path . DIRECTORY_SEPARATOR . $file . '"');
}