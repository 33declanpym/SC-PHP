<?php
include 'jsonRPCClient.php';
        if (!defined('CRLF')) {
            define('CRLF', "\r\n");
        }
$lines = file('data.txt') OR DIE ("ERROR");	
$url = "http://www.vision6.com.au/api/jsonrpcserver.php?version=3.0";		
$apikey = '';
$list_id = 384488;
$api = new JsonRpcClient($url);

$fields = csv_to_array('data.txt','') OR DIE ("CANNOT COMPUTE");

foreach ($fields as $field => $fields){
	$contact = array();
	$contact[] = array(
		'First Name' => $fields["NAME"],
		'Email' => $fields["EMAIL"]		
	);
//	print $fields["NAME"] . " has the email " . $fields["EMAIL"] . " and the status " . $fields["STATUS"] . "<br/>";
	$returns = $api->addContacts($apikey, $list_id, $contact);
};

var_dump($returns);

function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}
?>