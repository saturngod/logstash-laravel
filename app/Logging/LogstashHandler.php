<?php
namespace App\Logging;
// use Illuminate\Log\Logger;
use DB;
use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class LogstashHandler extends AbstractProcessingHandler{
    private $config;
    public function __construct($level = Logger::DEBUG, $bubble = true,$with) {
        
        $this->config = $with;
        parent::__construct($level, $bubble);
    }

    
    protected function write(array $record):void
    {

        $text = $record["message"];

        $re = '/("|\')data:image(.+);base64,(.+)/m';
        $subst = '"[IMAGE FILE]"';
        $text =  preg_replace($re, $subst, $text);
      
        $json = [
        "tag" => $record["channel"],
        "type" => $record["level_name"],
        "message" => $text
        ];

        $message = json_encode($json);

        $server_ip = $this->config['address'];
        $server_port = "".$this->config['port'];
       
        if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            
            die("Couldn't create socket: [$errorcode] $errormsg \n");
        }

        if(!socket_connect($sock , $server_ip , $server_port))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            
            die("Could not connect: [$errorcode] $errormsg \n");
        }


        //Send the message to the server
        if( ! socket_send ( $sock , $message , strlen($message) , 0))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            
            die("Could not send data: [$errorcode] $errormsg \n");
        }
                
        socket_close($sock);
        
        
    }

}
