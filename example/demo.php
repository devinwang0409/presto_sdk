<?php
/**
 * Created by PhpStorm.
 * User: lc-timorchao
 * Date: 2019/6/21
 * Time: 15:00
 */

use HuanLe\DBQueryV2\QueryClient;

require(dirname(__DIR__) . '/vendor/autoload.php');

/**
 * use Office_MySQL and BJ_KUDU and BJ_MySQL
 */
$url            = 'https://digger.123u.com:8443/v2/dbquery';
$requestTimeOut = 60;
$catalog        = 'kudu';
$action         = 'download';
$asyncRequestId = 'beb72d32ceed4cb0fc85981b0c93d29e';


$dbQuery = new QueryClient($url, 60);
$dbQuery->setAction($action);
$dbQuery->setAsyncRequestId($asyncRequestId);
$dbQuery->setIsDataFormat(true);
$data = $dbQuery->getQueryResult();

print_r($data);

