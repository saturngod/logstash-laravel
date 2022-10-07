<?php

namespace App\Logging;
use Monolog\Logger;

class LogstashLogger {
    
    public function __invoke(array $config)
    {
        $logger = new Logger($config['channel']);
        
        $server_info =  [
            'address' => config('logging.address'),
            'port' => config('logging.port')
        ];
        
        return $logger->pushHandler(new LogstashHandler($server_info, Logger::DEBUG, true));
        
    }
}
