<?php
/**
 * Deploy Short URL REST API Account Propogation REST Services API
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
 * @link            https://github.com/Chronolabs-Cooperative/Deploy-Jump-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 *
 */

    
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'apiconfig.php';
    
    $odds = $inner = array();
    foreach($_GET as $key => $values) {
        if (!isset($inner[$key])) {
            $inner[$key] = $values;
        } elseif (!in_array(!is_array($values)?$values:md5(json_encode($values, true)), array_keys($odds[$key]))) {
            if (is_array($values)) {
                $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
            } else {
                $odds[$key][$inner[$key] = $values] = "$values--$key";
            }
        }
    }
    foreach($_POST as $key => $values) {
        if (!isset($inner[$key])) {
            $inner[$key] = $values;
        } elseif (!in_array(!is_array($values)?$values:md5(json_encode($values, true)), array_keys($odds[$key]))) {
            if (is_array($values)) {
                $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
            } else {
                $odds[$key][$inner[$key] = $values] = "$values--$key";
            }
        }
    }
    foreach(parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?')?'&':'?').$_SERVER['QUERY_STRING'], PHP_URL_QUERY) as $key => $values) {
        if (!isset($inner[$key])) {
            $inner[$key] = $values;
        } elseif (!in_array(!is_array($values)?$values:md5(json_encode($values, true)), array_keys($odds[$key]))) {
            if (is_array($values)) {
                $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
            } else {
                $odds[$key][$inner[$key] = $values] = "$values--$key";
            }
        }
    }
    
    $return = checkAuthKey($inner['authkey']);
    if (isset($return['code'])) {
        if (function_exists('http_response_code'))
            http_response_code($return['code']);
        unset($return['code']);
        die(json_encode($return));
    }
    unset($inner['authkey']);
    
    $reserved = array('id', 'modal', 'uid', 'api-uid', 'created');
    $fieldbasis = array();
    foreach($inner as $name => $value) {
        if (in_array($name, $reserved)) {
            unset($inner[$name]);
            $inner[$name = "$name-$name"] = $value;
        }
        if (is_array($value))
            $fieldbasis[$name]['type'] = 'array';
        elseif (is_numeric($value))
            $fieldbasis[$name]['type'] = 'numeric';
        elseif (is_string($value))
            $fieldbasis[$name]['type'] = 'string';
        $fieldbasis[$name]['length'] = strlen($value);
    }

    foreach($_FILES as $name => $value) {
        if (!empty($value['tmp_name'])) {
            $fieldbasis["file-$name"]['type'] = 'file';
            $fieldbasis["file-$name"]['id'] = $name;
            $fieldbasis["file-$name"]['length'] = 0;
        }
    }
    
    $create = $alter = $fields = array();
    $sql = "SHOW FIELDS FROM `" . $GLOBALS['APIDB']->prefix('calling') . '`';
    $results = $GLOBALS['APIDB']->queryF($sql);
    while($row = $GLOBALS['APIDB']->fetchArray($results)) {
        $fields[$row['Field']] = $row['Type'];
        if (in_array($row['Field'], array_keys($fieldbasis))) {
            switch ($fieldbasis[$row['Field']]['type']) {
                case 'array':
                    if ($row['Type']!='mediumblob') {
                        $alter[$row['Field']]['type'] = 'mediumblob';
                    }
                    break;
                case 'numeric':
                    if ((integer)str_replace(array('int(', 'mediumint(', 'longint(', 'tinyint(', ')'), '', $row['Type']) < $fieldbasis[$row['Field']]['length']) {
                        $alter[$row['Field']]['type'] = "int(".$fieldbasis[$row['Field']]['length'].")";
                        $alter[$row['Field']]['default'] = "0";
                    }
                    break;
                case 'string':
                    if ((integer)str_replace(array('varchar(', 'char(', 'string(', ')'), '', $row['Type']) < $fieldbasis[$row['Field']]['length']) {
                        $alter[$row['Field']] = "varchar(".$fieldbasis[$row['Field']]['length'].")";
                        $alter[$row['Field']]['default'] = "";
                    }
                    break;
                case 'file':
                    if ((integer)str_replace(array('varchar(', 'char(', 'string(', ')'), '', $row['Type']) < 200) {
                        $alter[$row['Field']] = "varchar(200)";
                        $alter[$row['Field']]['default'] = "";
                    }
                    break;
            }
        }
    }
    
    if (count($alter) > 0)
        foreach($alter as $field => $values) {
            if (isset($values['default']))
                @$GLOBALS['APIDB']->queryF("ALTER TABLE `" . $GLOBALS['APIDB']->prefix('calling') . "` MODIFY COLUMN `$field` " . $values['type']. " NOT NULL DEFAULT '" . $values['default'] . "'");
            else
                @$GLOBALS['APIDB']->queryF("ALTER TABLE `" . $GLOBALS['APIDB']->prefix('calling') . "` MODIFY COLUMN `$field` " . $values['type']. "");
        }
    
    $lastfield = 'modal';
    foreach($fieldbasis as $field => $values) {
        if (!in_array($field, array_keys($fields))) {
            switch ($fieldbasis[$field]['type']) {
                case 'array':
                    $create[$field]['type'] = 'mediumblob';
                    if (!empty($lastfield))
                        $create[$field]['after'] = $lastfield;
                    break;
                case 'numeric':
                    $create[$field]['type'] = "int(".$fieldbasis[$field]['length'].")";
                    $create[$field]['default'] = "0";
                    if (!empty($lastfield))
                        $create[$field]['after'] = $lastfield;
                    break;
                case 'string':
                    $create[$field] = "varchar(".$fieldbasis[$field]['length'].")";
                    $create[$field]['default'] = "";
                    if (!empty($lastfield))
                        $create[$field]['after'] = $lastfield;
                    break;
                case 'file':
                    $create[$field] = "varchar(200)";
                    $create[$field]['default'] = "";
                    if (!empty($lastfield))
                        $create[$field]['after'] = $lastfield;
                    $create["$field-mimetype"]['after'] = $field;
                    $create["$field-mimetype"] = "varchar(64)";
                    $create["$field-mimetype"]['default'] = "";
                    break;
            }
        }
        $lastfield = $field;
    }
    
    if (count($create) > 0)
        foreach($create as $field => $values) {
            if (isset($values['default']))
                @$GLOBALS['APIDB']->queryF("ALTER TABLE `" . $GLOBALS['APIDB']->prefix('calling') . "` ADD COLUMN `$field` " . $values['type']. " NOT NULL DEFAULT '" . $values['default'] . (!empty($values['after'])?"  AFTER `" . $values['after'] . "`":""));
            else
                @$GLOBALS['APIDB']->queryF("ALTER TABLE `" . $GLOBALS['APIDB']->prefix('calling') . "` ADD COLUMN `$field` " . $values['type'] . (!empty($values['after'])?"  AFTER `" . $values['after'] . "`":""));
        }
    
    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('calling') . "` (`created`, `modal`, `api-uid`, %s) VALUES(UNIX_TIMESTAMP(), 'new', '" . $GLOBALS['uid'] . "', %s)";
    $data = array();
    foreach($fieldbasis as $field => $values) {
        switch ($fieldbasis[$field]['type']) {
            case 'array':
                $data[$field] = $GLOBALS['APIDB']->escape(json_encode($inner[$field]));
                break;
            case 'numeric':
                $data[$field] = $inner[$field];
                break;
            case 'string':
                $data[$field] = $GLOBALS['APIDB']->escape($inner[$field]);
                break;
            case 'file':
                if (!isset($uploadpath)) {
                    $uploadpath = API_ROOT_PATH . "/uploads/" . date("D") . '/' . date("W") . '/'  . date("Y") . '/'  . date("m") . '/' . date("d") . '/'  . date("H") . '/' . date("n") . '/'  . date("s");
                    if (!is_dir($uploadpath))
                        mkdir($uploadpath, 0777, true);
                }
                
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $file = $uploadpath . DIRECTORY_SEPARATOR . $_FILES[$field]['name'])) {
                    $data[$field] = $file;
                    $data["$field-mimetype"] = $_FILES[$field]['mimetype'];
                }
                break;
        }
    }
    if ($GLOBALS['APIDB']->queryF(sprintf($sql, "`" . implode("`, `", array_keys($data)) . "`", "'" . implode("', '", $data) . "'"))) {
        if (function_exists('http_response_code'))
            http_response_code(201);
        die(json_encode(array("key"=> md5(API_URL . 'calling' . $GLOBALS['APIDB']->getInsertId()))));
    }
    if (function_exists('http_response_code'))
        http_response_code(501);
    die(json_encode(array("errors"=> array($GLOBALS['APIDB']->errno() => $GLOBALS['APIDB']->error()))));
    