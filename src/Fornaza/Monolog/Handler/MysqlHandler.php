<?php

namespace Fornaza\Monolog\Handler;

use DB;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class MysqlHandler extends AbstractProcessingHandler
{
    protected $table;

    public function __construct($table = 'logs', $level = Logger::DEBUG, $bubble = true)
    {
        $this->table = $table;

        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $data = array(
            'channel'    => $record['channel'],
            'message'    => $record['message'],
            'level'      => $record['level'],
            'level_name' => $record['level_name'],
            'context'    => json_encode($record['context']),
            'formatted'  => $record['formatted'],
            'created_at' => $record['datetime']->format('Y-m-d H:i:s')
        );
        try {
            DB::connection()->table($this->table)->insert($data);
        } catch (\Exception $e) {
           // Database isn't yet ready.
        }
    }

}
