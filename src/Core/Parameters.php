<?php

namespace HuanLe\DBQueryV2\Core;

/**
 * Created by PhpStorm.
 * User: lc-timorchao
 * Date: 2019/6/21
 * Time: 15:00
 */

class Parameters
{
    public static function getGlobalParam()
    {
        $baseParam = [
            'db'            => true,
            'catalog'       => true,
            'sql'           => true,
            'is_limit'      => false,
            'page'          => false,
            'PageSize'      => false,
            'CurrentPage'   => false,
            'cache'         => false,
            'operator'      => false
        ];
        $download = [
            'async_request_id'  => true,
            'operator'          => false,
        ];

        return array(
            'base' => $baseParam,
            'download' => $download,
        );

//        return $baseParam;
    }
}