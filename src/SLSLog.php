<?php

namespace Lokielse\LaravelSLS;

use Aliyun\SLS\Client;
use Aliyun\SLS\Models\LogItem;
use Aliyun\SLS\Requests\GetHistogramsRequest;
use Aliyun\SLS\Requests\GetLogsRequest;
use Aliyun\SLS\Requests\ListLogStoresRequest;
use Aliyun\SLS\Requests\ListTopicsRequest;
use Aliyun\SLS\Requests\PutLogsRequest;
use Aliyun\SLS\Responses\GetHistogramsResponse;
use Aliyun\SLS\Responses\GetLogsResponse;
use Aliyun\SLS\Responses\ListLogStoresResponse;
use Aliyun\SLS\Responses\ListTopicsResponse;

class SLSLog
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $project;

    /**
     * @var string
     */
    protected $logStore;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * List log stores in project
     *
     * @param string $project
     *
     * @return ListLogStoresResponse
     */
    public function listLogStores($project = null)
    {
        $project  = $project ?: $this->project;
        $request  = new ListLogStoresRequest($project);
        $response = $this->client->listLogStores($request);

        return $response;
    }


    /**
     * Write logs to store
     *
     * @param array  $data
     *
     * @param string $topic
     *
     * @return bool
     */
    public function putLogs($data, $topic = null, $source = null, $time = null)
    {
        $logItem  = new LogItem($data, $time);
        $request  = new PutLogsRequest($this->project, $this->logStore, $topic, $source, [ $logItem ]);
        $response = $this->client->putLogs($request);

        return array_get($response->getAllHeaders(), '_info.http_code') === 200;
    }


    /**
     * List topics in store
     *
     * @return ListTopicsResponse
     */
    public function listTopics()
    {
        $request  = new ListTopicsRequest($this->project, $this->logStore);
        $response = $this->client->listTopics($request);

        return $response;
    }


    /**
     * Get history logs
     *
     * @param integer $from
     * @param integer $to
     * @param string  $query
     * @param string  $topic
     *
     * @return GetHistogramsResponse
     */
    public function getHistograms($from = null, $to = null, $query = null, $topic = null)
    {
        $request  = new GetHistogramsRequest($this->project, $this->logStore, $from, $to, $topic, $query);
        $response = $this->client->getHistograms($request);

        return $response;
    }


    /**
     * Get logs in store
     *
     * @param string  $from
     * @param string  $to
     * @param string  $query
     * @param string  $topic
     * @param int     $line
     * @param string  $offset
     * @param boolean $reverse
     *
     * @return GetLogsResponse
     */
    public function getLogs(
        $from = null,
        $to = null,
        $query = null,
        $topic = null,
        $line = 100,
        $offset = null,
        $reverse = true
    ) {
        $request  = new GetLogsRequest($this->project, $this->logStore, $from, $to, $topic, $query, $line, $offset,
            $reverse);
        $response = $this->client->getLogs($request);

        return $response;
    }


    /**
     * @return mixed|string
     */
    public function getProject()
    {
        return $this->project;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setProject($value)
    {
        $this->project = $value;

        return $this;
    }


    /**
     * @return mixed|string
     */
    public function getLogStore()
    {
        return $this->logStore;
    }


    /**
     * @param $value
     *
     * @return $this
     */
    public function setLogStore($value)
    {
        $this->logStore = $value;

        return $this;
    }


    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}