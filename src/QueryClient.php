<?php

namespace HuanLe\DBQueryV2;

/**
 * Created by PhpStorm.
 * Date: 2019/11/14
 * Time: 10:54
 */

use HuanLe\DBQueryV2\Core\DbQueryException;
use HuanLe\DBQueryV2\Core\Helper;
use HuanLe\DBQueryV2\Http\Client;
use HuanLe\DBQueryV2\Result\QueryResponse;

/**
 * init
 * Class QueryClient
 * @package HuanLe\DBQueryV2
 */
class QueryClient
{

    //actions
    const DBQUERY_SYNC_PRESTO = 'sync-presto';
    const DBQUERY_ASYNC_PRESTO = 'async-presto';
    const DBQUERY_DOWNLOAD = 'download';


    //default
    const DB_QUERY_HOST = 'https://digger.123u.com:8443/v2/dbquery';
    const DB_QUERY_TIMEOUT = 60;
    const DB_QUERY_PARAM_ISLIMIT = true;
    const DB_QUERY_PARAM_CACHE = true;
    const DB_QUERY_PARAM_CACHE_TIMEOUT = 86400;
    const DB_QUERY_PARAM_ASYNC = false;
    const DB_QUERY_PARAM_TIMEOUT = 360;
    const DB_QUERY_PARAM_PAGESIZE = 20;
    const DB_QUERY_PARAM_CURRENTPAGE = 1;

    private $host;
    private $requestTimeOut;
    private $param = [];

    private $action;
    private $db;
    private $catalog;
    private $sql;
    private $page;
    private $pageSize;
    private $currentPage;
    private $isLimit;
    private $cache;
    private $operator;
    private $isDataFormat;
    private $asyncRequestId;


    /**
     * @return mixed
     */
    public function getAsyncRequestId()
    {
        return $this->asyncRequestId;
    }

    /**
     * @param mixed $isDataFormat
     */
    public function setAsyncRequestId($asyncRequestId)
    {
        $this->asyncRequestId = $asyncRequestId;
        $this->param['async_request_id'] = $asyncRequestId;
    }

    /**
     * @return mixed
     */
    public function getIsDataFormat()
    {
        return $this->isDataFormat;
    }

    /**
     * @param mixed $isDataFormat
     */
    public function setIsDataFormat($isDataFormat)
    {
        $this->isDataFormat = $isDataFormat;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getRequestTimeOut()
    {
        return $this->requestTimeOut;
    }

    /**
     * @param mixed $requestTimeOut
     */
    public function setRequestTimeOut($requestTimeOut)
    {
        $this->requestTimeOut = $requestTimeOut;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->param['db'] = $db;
        $this->db          = $db;
    }

    /**
     * @return mixed
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * @param mixed $instance
     */
    public function setCatalog($catalog)
    {
        $this->param['catalog'] = $catalog;
        $this->catalog          = $catalog;
    }

    /**
     * @return mixed
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param mixed $query
     */
    public function setSql($sql)
    {
        $this->param['sql'] = $sql;
        $this->sql          = $sql;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action          = $action;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->param['page'] = $page;
        $this->page          = $page;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->param['PageSize'] = $pageSize;
        $this->pageSize          = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->param['CurrentPage'] = $currentPage;
        $this->currentPage          = $currentPage;
    }

    /**
     * @return mixed
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param mixed $cache
     */
    public function setCache($cache)
    {
        $this->param['cache'] = $cache;
        $this->cache          = $cache;
    }

    /**
     * @return mixed
     */
    public function getIsLimit()
    {
        return $this->isLimit;
    }

    /**
     * @param mixed $cacheTimeOut
     */
    public function setIsLimit($isLimit)
    {
        $this->param['is_limit'] = $isLimit;
        $this->isLimit           = $isLimit;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     */
    public function setOperator($operator)
    {
        $this->param['operator'] = $operator;
        $this->operator          = $operator;
    }

    /**
     * QueryClient constructor.
     *
     * @param $host
     * @param $requestTimeOut
     * @param $version
     *
     * @throws DbQueryException
     */
    public function __construct($host, $requestTimeOut)
    {
        $host           = trim($host);
        $requestTimeOut = trim($requestTimeOut);

        if (empty($host)) {
            throw new DbQueryException("host is empty");
        }
        if (empty($requestTimeOut)) {
            throw new DbQueryException("request timeout is empty");
        }

        $this->setHost($host);
        $this->setRequestTimeOut($requestTimeOut);

        //default param
        $this->param['is_limit']      = self::DB_QUERY_PARAM_ISLIMIT;
        $this->param['cache']         = self::DB_QUERY_PARAM_CACHE;
        $this->param['PageSize']      = self::DB_QUERY_PARAM_PAGESIZE;
        $this->param['CurrentPage']   = self::DB_QUERY_PARAM_CURRENTPAGE;

        Helper::checkEnv();
    }

    /**
     * @explain
     * @return array
     * @throws DbQueryException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author timorchao
     */
    public function getQueryResult(): array
    {
        //check param
        Helper::checkParam($this->action, $this->param);

        //start request
        $Client = new Client($this->getHost().'/'.$this->action, $this->getRequestTimeOut(), $this->param);

        $result = $Client->getData();

        if ($this->getIsDataFormat()) {
            $obj = new QueryResponse($this->param, $result);
            return $obj->response();
        }
        return $result;
    }
}