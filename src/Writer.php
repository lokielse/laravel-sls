<?php

namespace Lokielse\LaravelSLS;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

class Writer implements PsrLoggerInterface
{

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var SLSLog
     */
    private $logger;

    /**
     * @var string
     */
    private $topic;


    public function __construct(SLSLog $logger, Dispatcher $dispatcher = null, $topic)
    {
        if (isset( $dispatcher )) {
            $this->dispatcher = $dispatcher;
        }

        $this->logger = $logger;
        $this->topic  = $topic;
    }


    /**
     * Log an alert message to the logs.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function alert($message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Log a critical message to the logs.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function critical($message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Log an error message to the logs.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function error($message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Log a warning message to the logs.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function warning($message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Log a notice to the logs.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function notice($message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Log an informational message to the logs.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function info($message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Log a debug message to the logs.
     *
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function debug($message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Log a message to the logs.
     *
     * @param  string $level
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [ ])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Register a file log handler.
     *
     * @param  string $path
     * @param  string $level
     *
     * @return void
     */
    public function useFiles($path, $level = 'debug')
    {

    }


    /**
     * Register a daily file log handler.
     *
     * @param  string $path
     * @param  int    $days
     * @param  string $level
     *
     * @return void
     */
    public function useDailyFiles($path, $days = 0, $level = 'debug')
    {

    }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }


    /**
     * Write a message to Monolog.
     *
     * @param  string $level
     * @param  string $message
     * @param  array  $context
     *
     * @return boolean
     */
    protected function writeLog($level, $message, $context)
    {
        $this->fireLogEvent($level, $message = $this->formatMessage($message), $context);

        $this->logger->putLogs([
            'level'   => $level,
            'message' => $message,
            'context' => json_encode($context),
        ], $this->topic);
    }


    /**
     * Fires a log event.
     *
     * @param  string $level
     * @param  string $message
     * @param  array  $context
     *
     * @return void
     */
    protected function fireLogEvent($level, $message, array $context = [ ])
    {
        // If the event dispatcher is set, we will pass along the parameters to the
        // log listeners. These are useful for building profilers or other tools
        // that aggregate all of the log messages for a given "request" cycle.
        if (isset( $this->dispatcher )) {
            $this->dispatcher->fire('illuminate.log', compact('level', 'message', 'context'));
        }
    }


    /**
     * Format the parameters for the logger.
     *
     * @param  mixed $message
     *
     * @return mixed
     */
    protected function formatMessage($message)
    {
        if (is_array($message)) {
            return var_export($message, true);
        } elseif ($message instanceof Jsonable) {
            return $message->toJson();
        } elseif ($message instanceof Arrayable) {
            return var_export($message->toArray(), true);
        }

        return $message;
    }
}