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
$host = 'declanpym.com';
$port = 21;
$user = 'declanpym';
$pass = 'Fatal1ty178';
$remote_dir = '/public_html/vision6/monitor';
$cache_file = 'ftp_cache';
$email_notify = 'declanpym@gmail.com';
$email_from = 'declanpym@gmail.com';

// Main Run Program ////////////////////////////////////////////////////////////

// Connect to FTP Host
$conn = ftp_connect($host, $port) or die("Could not connect to {$host}\n");

// Login
if(ftp_login($conn, $user, $pass)) {

  // Retrieve File List
  $files = ftp_nlist($conn, $remote_dir);

  // Filter out . and .. listings
  $ftpFiles = array();
  foreach($files as $file)
  {
    $thisFile = basename($file);
    if($thisFile != '.' && $thisFile != '..') {
      $ftpFiles[] = $thisFile;
    }
  }

  // Retrieve the current listing from the cache file
  $currentFiles = array();
  if(file_exists($cache_file))
  {
    // Read contents of file
    $handle = fopen($cache_file, "r");
    if($handle)
    {
      $contents = fread($handle, filesize($cache_file));
      fclose($handle);

      // Unserialize the contents
      $currentFiles = unserialize($contents);
    }
  }

  // Sort arrays before comparison
  sort($currentFiles, SORT_STRING);
  sort($ftpFiles, SORT_STRING);

  // Perform an array diff to see if there are changes
  $diff = array_diff($ftpFiles, $currentFiles);
  if(count($diff) > 0) // Below has been setup to email upon changes. However, can be modified to run vision6 commands
  {
    // Email the changes
    $msg = "<html><head><title>ftpMonitor Changes</title></head><body>" .
      "<h1>ftpMonitor Found Changes:</h1><ul>";
    foreach($diff as $file)
    {
      $msg .= "<li>{$file}</li>";//Loop through each file to print the output.
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
	
  }

  // Write new file list out to cache
  $handle = fopen($cache_file, "w");
  fwrite($handle, serialize($ftpFiles));
  fflush($handle);
  fclose($handle);
}
else {
  echo "Could not login to {$host}\n";
}

// Close Connection
ftp_close($conn);
?>