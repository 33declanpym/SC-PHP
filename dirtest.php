<?php
class FileAlterationMonitor
{
    private $scanFolder, $initialFoundFiles;

    public function __construct($scanFolder)
    {
        $this->scanFolder = $scanFolder;
        $this->updateMonitor();
    }

    private function _arrayValuesRecursive($array)
    {
        $arrayValues = array();

        foreach ($array as $value)
        {
            if (is_scalar($value) OR is_resource($value))
            {
                 $arrayValues[] = $value;
            }
            elseif (is_array($value))
            {
                 $arrayValues = array_merge( $arrayValues, $this->_arrayValuesRecursive($value));
            }
        }

        return $arrayValues;
    }

    private function _scanDirRecursive($directory)
    {
        $folderContents = array();
        $directory = realpath($directory).DIRECTORY_SEPARATOR;

        foreach (scandir($directory) as $folderItem)
        {
            if ($folderItem != "." AND $folderItem != "..")
            {
                if (is_dir($directory.$folderItem.DIRECTORY_SEPARATOR))
                {
                    $folderContents[$folderItem] = $this->_scanDirRecursive( $directory.$folderItem."\\");
                }
                else
                {
                    $folderContents[] = $folderItem;
                }
            }
        }

        return $folderContents;
    }

    public function getNewFiles()
    {
        $finalFoundFiles = $this->_arrayValuesRecursive( $this->_scanDirRecursive($this->scanFolder));

        if ($this->initialFoundFiles != $finalFoundFiles)
        {
            $newFiles = array_diff($finalFoundFiles, $this->initialFoundFiles);
            return empty($newFiles) ? FALSE : $newFiles;
        }
    }

    public function getRemovedFiles()
    {
        $finalFoundFiles = $this->_arrayValuesRecursive( $this->_scanDirRecursive($this->scanFolder));

        if ($this->initialFoundFiles != $finalFoundFiles)
        {
            $removedFiles = array_diff( $this->initialFoundFiles, $finalFoundFiles);
            return empty($removedFiles) ? FALSE : $removedFiles;
        }
    }

    public function updateMonitor()
    {
        $this->initialFoundFiles = $this->_arrayValuesRecursive($this->_scanDirRecursive( $this->scanFolder));
    }
}

$f = new FileAlterationMonitor("/home/declanpym/public_html/vision6/");

while (TRUE)
{
    sleep(1);

    if ($newFiles = $f->getNewFiles() or die ("ERROR")) ;
    {
        // Code to handle new files
        // $newFiles is an array that contains added files
        
	$to = "declan@smartcomm.net.au";
	$subject = "Test mail";
	$message = "Hello! This is a simple email message.";
	$from = "declan@smartcomm.net.au";
	$headers = "From:" . $from;
	mail($to,$subject,$message,$headers);
        
    }

    if ($removedFiles = $f->getRemovedFiles() or die ("ERROR"))
    {
        // Code to handle removed files
        // $newFiles is an array that contains removed files
            
	$to = "declan@smartcomm.net.au";
	$subject = "Test mail";
	$message = "Hello! This is a simple email message.";
	$from = "declan@smartcomm.net.au";
	$headers = "From:" . $from;
	mail($to,$subject,$message,$headers);    
        
    }

    $f->updateMonitor() or die ("ERROR");
}


?>