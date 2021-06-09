Copy the app/Logging/ to your app/Logging/

In the config/logging.php , you need to add 

```
'logstash' => [
            'driver' => 'custom',
            'via' => App\Logging\LogstashLogger::class,
            'channel' => 'logstash_sample',
            'with' => [
                'address' => "192.168.31.249",
                'port' => "5428"
            ]
],
```

`address` is your logstash IP.

`port` is your logstash port.

`channel` is for tag in logstash.

After that , you can call like

```
\Log::channel('logstash')->info("HELLO");
```

