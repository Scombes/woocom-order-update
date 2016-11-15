<?php
/**
*
*FTP CLASS
*
**/

Class FTP_Client {

   public function __construct(){}

    //Variables
    private $connectionId;
    private $loginOk = false;
    private $messageArray = array();

    //function to log messages
    private function logMessage($message)
    {
        $this->messageArray[] = $message;
    }

    //Function to show messages
    public function getMessages()
    {
        return $this->messageArray;
    }

    //Function to connect to remote server
    public function connect ($server, $ftpUser, $ftpPassword, $isPassive = true)
    {

        // *** Set up basic connection
        $this->connectionId = ftp_connect($server);

        // *** Login with username and password
        $loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);

        // *** Sets passive mode on/off (default on)
        ftp_pasv($this->connectionId, $isPassive);

        // *** Check connection
        if ((!$this->connectionId) || (!$loginResult)) {
            $this->logMessage('FTP connection has failed!');
            $this->logMessage('Attempted to connect to ' . $server . ' for user ' . $ftpUser, true);
            return false;
        } else {
            $this->logMessage('Connected to ' . $server . ', for user ' . $ftpUser);
            $this->loginOk = true;
            return true;
        }
    }

    public function downloadFile($fileFrom, $fileTo){

      // try to download $remote_file and save it to $handle
      if (ftp_get($this->connectionId, $fileTo, $fileFrom, FTP_ASCII, 0)) {
          return true;
          $this->logMessage(' file "' . $fileTo . '" successfully downloaded');
      } else {
          return false;
          $this->logMessage('There was an error downloading file "' . $fileFrom . '" to "' . $fileTo . '"');
      }
    }

    public function listAllFiles(){
      //Get list of files in current directory
      $content = ftp_nlist($this->connectionId, ".");
      return $content;
    }

    public function deleteFile($file){
      // try to delete $file
      if (ftp_delete($this->connectionId, $file)) {
          return true;
          $this->logMessage(' file "' . $file . '" successfully deleted');
      } else {
          return false;
          $this->logMessage(' Error file "' . $file . '" was not deleted');
      }

    }

    public function closeConnection(){
      if ($this->connectionId) {
          ftp_close($this->connectionId);
      }
    }

    //Close FTP Connection
    public function __deconstruct()
    {
        if ($this->connectionId) {
            ftp_close($this->connectionId);
        }
    }


}//End FTP Class

?>
