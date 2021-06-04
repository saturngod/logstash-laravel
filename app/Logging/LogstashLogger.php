<?php

namespace App\Logging;
use Monolog\Logger;

class LogstashLogger {

    public function __invoke(array $config)
    {
        
        $logger = new Logger($config['channel']);
        return $logger->pushHandler(new LogstashHandler(Logger::DEBUG,true,$config['with']));
    }
}
