<?php
/*
Date : 1st July 2006
Author : Sumit Gupta

PHP script to connect to pop3 Mail server
Fetch Emails and Delete from Mail Server that are not required
*/

class POP3Mail
{
	// Public Variables
	var $UserName;
	var $Password;
	var $Server; //Can be Server IP or Domain Name
	var $Port; // Port to connect for Pop3 if different from default 110 port
	var $MailCount; //Will Be -2 if no connection otherwise, -1 if connection but fail to count on server
	var $MailCache; //Store the Emails in an Array, You need to handle this cache according to your choice, its out of scope to this class

	//Private Variables

	var $_isConnected;
	var $_Error;
	var $_Connection; //Holds the Stream Connection with Server;
	var $_Buffer;
	var $_MessageExchange;
	//Functions

	//Constructor function
	function POP3Mail($server = '127.0.0.1' , $username = '' , $pass = '')
	{
		$this->Server = $server;
		$this->UserName = $username;
		$this->Password = $pass;
		$this->Port = 110;

		$this->_isConnected = false;
		$this->_Error=array();
		$this->MailCache = array();
		$this->MailCount = -2;
		$this->_Buffer = 4096;
		$this->_MessageExchange = array();
	}

	//Function to Change Port no.
	function setPort($newport)
	{
		if (is_numeric($newport) && $newport > 0 ) {
			$this->Port = $newport;
			return 1;
		}
		else{
			setError("Attempt to set Invalid Port No. Port No ask for is [$newport]\n\r");
			return 0;
		}
	}

	//Function to Set errors
	function setError($msg)
	{
		$this->_Error[]=$msg;
	}

	//Function to retreive error
	function getAllError()
	{
		return $this->_Error;
	}

	//function to get Latest Error
	function getLatestError()
	{
		return $this->_Error[(count($this->_Error) -1 )];
	}

	//Function to Connect to Database
	// Return 0 if fail to connect to server
	// Return 1 for Successful Authentication
	// Return -1 if fail to login
	function Connect()
	{
		$this->_Connection = @fsockopen($this->Server,$this->Port, $errorno, $errorstr,30);
		if ($this->_Connection === false) { //Connection Fails
			$this->setError("Fail to Connect to server error return is \n\r Error No.: $errorno \r\n Error Message : $errorstr\r\n");
			$this->_isConnected =false;

			echo "<h1>Falha na conexão com o servidor de e-mails. Consulte o STI.</h1>";

			foreach($this->_Error as $chave => $valor)
			{
				echo "<h2>".$valor."</2>";
			}

			exit();
		}
		else {
			$this->_isConnected=true;
			$response= $this->Receive();
			$response = $this->Ask("USER $this->UserName");
			if ($this->isError($response))
				return -1;
			$response= $this->Ask("PASS $this->Password");
			if ($this->isError($response))
				return -1;
			return 1;
		}
	}

	function Send($command)
	{
		fputs ($this->_Connection,$command . "\r\n");
		$this->_MessageExchange[] = $command;
	}

	function Receive()
	{
		$response = fgets($this->_Connection, $this->_Buffer);
		$this->_MessageExchange[] = $response;
		return $response;
	}

	function Ask ( $command , $longanswer = 0 )
    {
    	$this->Send( $command );
    	$response = $this->Receive();
    	if ( $longanswer )
        {
        	while ( ! ( trim( $response ) == "." ) )
            {
            	if ( ! ( trim( $response ) == "." ) )
                {
                	$answer[] .= $response;
                	$response = $this->Receive();
                }
            }
        	$response = $answer;
        }
    	return $response;
    }

    function isError ( $response )
    {
        return ( substr( $response, 0, 3 ) == "-ER" ) ? true : false;
	}

	function getMessage ($messageno)
	{
		$body = $this->Ask( "RETR $messageno" , 1 );

        if ( $this->isError( $body[0] ) )
		{
            return "301"; // Invalid RETR command
        }
        array_shift( $body );
        return $body;
	}

	function getStat()
	{
		$response= $this->Ask( "STAT" );
		if ($this->isError($response))
			return 0;
		$stats = explode ( " " , $response );
        $this->MailCount = $stats[1];
        return 1;
	}

	function Disconnect()
	{
		$this->Ask("QUIT");
	}

	function getUniqueID()
	{
		$body = $this->Ask( "UIDL" , 1 );

        if ( $this->isError( $body[0] ) )
		{
            return "301"; // Invalid RETR command
        }
        array_shift( $body );
        return $body;
	}

	function getRefinedUID()
	{
		$uniquerId = $this->getUniqueID();
		$MessageList= array();
		while (list($index,$value) = each($uniquerId))
		{
			$value= str_replace("\r","",$value);
			$value= str_replace("\n","",$value);
			$splitedvalue = explode(" ",$value);
			$MessageList[$index]["UID"] = $splitedvalue[1];
			$MessageList[$index]["SERIAL"] = $splitedvalue[0];
		}
		return $MessageList;
	}

	function getFullMessage($limit = -1)
	{
		set_time_limit(90);
		$message= $this->getRefinedUID();
		$MessageList= array();
		$ctr=0;
		while (list($index,$value)=each ($message))
		{
			$MessageList[$index]["UID"] = $value["UID"];
			$MessageList[$index]["SERIAL"] = $value["SERIAL"];
			if ($limit != -1 && $limit > $ctr){
				$tmpmessage=$this->_MessageParser($MessageList[$index]["SERIAL"]);
				$MessageList[$index]["FULLMESSAGE"] = $tmpmessage["FULLMESSAGE"];
				$MessageList[$index]["HEADER"] = $tmpmessage["HEADER"];
				$MessageList[$index]["BODY"] = $tmpmessage["BODY"];
			}
			else {
				$MessageList[$index]["FULLMESSAGE"] = "Yet To Fetch Message";
				$MessageList[$index]["HEADER"] = "Yet To Fetch Message";
				$MessageList[$index]["BODY"] = "Yet To Fetch Message";
			}
			$ctr++;
		}
		return $MessageList;
	}

	//Function to parse Message coming from Ask Function to Three part: full message, Header and Body
	function _MessageParser($messageno)
	{
		$tmpmessage = $this->getMessage($messageno);
		$ctr=0;
		$headerfound = false;
		$header = "";
		$messagebody = "";
		while (list($index,$value)=each($tmpmessage))
		{
			if ($headerfound == false && $value != "\r\n") {
				$header.=$value;
				$headerfound = false;
			}
			elseif ($value == "\r\n" && $headerfound ==false) {
				$headerfound=true;
			}
			elseif($headerfound==true){
				$messagebody.=$value;
			}
			$ctr++;
		}
		$tmpmessage= str_replace("\r","",$tmpmessage);
		$tmpmessage= str_replace("\n","",$tmpmessage);
		$tmpmessage = implode("\r\n",$tmpmessage);
		$MessageList["FULLMESSAGE"] = $tmpmessage;
		$MessageList["HEADER"] = $header;
		$MessageList["BODY"] = $messagebody;
		return $MessageList;
	}

	function Delete($messageno)
	{
		if (is_numeric($messageno) && $messageno > -1)
			$response = $this->Ask("DELE $messageno");
	}
}


?>