<?php

$filename = data.txt;

$lines = file('data.txt') OR DIE ("ERROR");

//$data = "var1|var2|var3|var4|var5.pdf";
//$lines = trim($lines, '|');
//$fields = explode("|", $lines);

//print $lines;
//print $fields;

//$variables = array();
//foreach ($lines as $line => $lines){
//	$variables[$lines];
//	print $Variables[0];
//};

$fields = csv_to_array('data.txt','|') OR DIE ("CANNOT COMPUTE");
foreach ($fields as $field => $fields){
	print $fields["NAME"] . " has the email " . $fields["EMAIL"] . " and the status " . $fields["STATUS"] . "<br/>";
};


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