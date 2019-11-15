<?php
/**
 * Created by PhpStorm.
 * User: lc-timorchao
 * Date: 2019/6/24
 * Time: 16:07
 */

namespace HuanLe\DBQueryV2\Core;


use HuanLe\DBQueryV2\QueryClient;


class Helper
{
    /**
     * @explain check extension
     * @throws DbQueryException
     * @author timorchao
     */
    public static function checkEnv()
    {
        if (function_exists('get_loaded_extensions')) {
            //check curl extension
            $enabled_extension = ["curl"];
            $extensions        = get_loaded_extensions();
            if ($extensions) {
                foreach ($enabled_extension as $item) {
                    if (!in_array($item, $extensions)) {
                        throw new DbQueryException("Extension {" . $item . "} is not installed or not enabled, please check your php env.");
                    }
                }
            } else {
                throw new DbQueryException("function get_loaded_extensions not found.");
            }
        } else {
            throw new DbQueryException('Function get_loaded_extensions has been disabled, please check php config.');
        }
    }


    /**
     * @explain
     *
     * @param $param
     *
     * @throws DbQueryException
     * @author timorchao
     */
    public static function checkParam($action, $param)
    {
        if($action === QueryClient::DBQUERY_DOWNLOAD) {
            self::paramIsNecessary(Parameters::getGlobalParam()['download'], $param);
        }
        else {
            self::paramIsNecessary(Parameters::getGlobalParam()['base'], $param);
        }
    }

    /**
     * @explain
     *
     * @param $param
     * @param $needCheckParam
     *
     * @throws DbQueryException
     * @author timorchao
     */
    private static function paramIsNecessary($param, $needCheckParam)
    {
        foreach ($param as $key => $value) {
            if ($value) {
                if (isset($needCheckParam[$key]) || array_key_exists($key, $needCheckParam)) {
                    continue;
                } else {
                    throw new DbQueryException("param {$key} is necessary,Please input it");
                }
            } else {
                continue;
            }
        }

        //check pagination
        if (isset($needCheckParam['page']) || array_key_exists('page', $needCheckParam)) {
            if (!(isset($needCheckParam['PageSize']) || array_key_exists('PageSize',
                        $needCheckParam)) || !(isset($needCheckParam['CurrentPage']) || array_key_exists('CurrentPage',
                        $needCheckParam))) {
                throw new DbQueryException("param pagination is necessary,Please input it");
            }
        }
    }
}