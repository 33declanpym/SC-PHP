datasplit.php 	- Gets an input data file, a delimiter, and assigns each delimited item into a field. The first row is the heading. Each element can then be referred to via $array[HEADERNAME]
dirtest.php	- An OO approach to a file monitoring program.
ftpMonitor.php	- Monitors an external ftp for files. Once its activated, looks into ftp_cache file for the list of files and the ftp for the files on the ftp. If a new file exists (not in ftp_cache), it emails the new file name. This can be replaced with any function. Once that file has been 'processed' it adds the file to ftp_cache.
sendEmail.php	- sends an email. Simples.
Smartcomm.php 	- Basic Vision6 program to loop through data file and add contacts to a list. 