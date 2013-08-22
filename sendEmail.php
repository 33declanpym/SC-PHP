<?php
/**
 * File : ftpMonitor.php
 * Monitors a remote directory via FTP and emails a list of changes if any are
 * found.
 *
 * @version August 12, 2013
 * @author <a href="http://www.declanpym.com">Declan Pym</a>
 */

// Configuration ///////////////////////////////////////////////////////////////
$email_notify = 'declanpym@gmail.com';
$email_from = 'declanpym@gmail.com';

// Main Run Program ////////////////////////////////////////////////////////////

    $msg = "<html><head><title>ftpMonitor Changes</title></head><body>" .
      "<h1>ftpMonitor Found Changes:</h1><ul>";
    foreach($somethings as $something)
    {
      $msg .= "<li>{$something}</li>";//Loop through each file to print the output.
    }
    $msg .= "</ul>";
    $msg .= '<em>Script by <a href="http://www.declanpym.com">Declan Pym</a></em>';
    $msg .= "</body></html>";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "To: {$email_notify}\r\n";
    $headers .= "From: {$email_from}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    mail($email_notify, "ftpMonitor Changes Found", $msg, $headers);
?>