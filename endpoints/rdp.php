<?php
/**
 * Simple RDP connection file generator
 *
 * @package vboxwebmgr
 *
 * @originally written by
 *
 * @author Ian Moore (imoore76 at yahoo dot com)
 * @copyright Copyright (C) 2010-2015 Ian Moore (imoore76 at yahoo dot com)
 * @version $Id: rdp.php 591 2015-04-11 22:40:47Z imoore76 $
 * @package phpVirtualBox
 *
 */

function getrdpextralines()
{

    $results = array();

    $vbox = new vboxconnector(true);
    $vbox->skipSessionCheck = true;
    $vbox->connect();
    $p = $vbox->vbox->getExtraDataKeys();

    foreach ($p as $value) {
        if (strpos($value, 'RDP/extraline') !== false) {
            $line = $vbox->vbox->getExtraData($value);
            array_push($results, $line);
        }
        elseif (strpos($value, 'RDP/includedeflines') !== false) {
            if ($vbox->vbox->getExtraData($value) == 1)
            {
                array_push($results,'compression:i:1');
                array_push($results,'displayconnectionbar:i:1');
                array_push($results,'protocol:i:4');
            }
	}
    }

    return $results;
}


# Turn off PHP notices
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);

require_once(dirname(__FILE__).'/lib/utils.php');
require_once(dirname(__FILE__).'/lib/vboxconnector.php');
$_GET = clean_request();

foreach(array('port','host','vm') as $g) {
	@$_GET[$g] = str_replace(array("\n","\r","\0"),'',@$_GET[$g]);
}


header("Content-type: application/x-rdp",true);
header("Content-disposition: attachment; filename=\"". str_replace(array('"','.'),'_',$_GET['vm']) .".rdp\"",true);

$rdpfile = '
full address:s:'.@$_GET['host'].(@$_GET['port'] ? ':'.@$_GET['port'] : '').'
';

$results = getrdpextralines();

if (!empty($results)) {
    $rdpfile = $rdpfile . implode("\n",$results) . "\n\n";
}

echo($rdpfile);

