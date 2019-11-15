<?php
/**
 * Created by PhpStorm.
 * User: lc-timorchao
 * Date: 2019/6/24
 * Time: 20:02
 */

namespace HuanLe\DBQueryV2\Result;


class QueryResponse
{
    /**
     * @var
     */
    private $requestParam;

    /**
     * @var
     */
    private $responseData;

    /**
     * QueryResponse constructor.
     *
     * @param $requestParam
     * @param $responseData
     */
    public function __construct($requestParam, $responseData)
    {
        $this->requestParam = $requestParam;
        $this->responseData = $responseData;
    }

    /**
     * @explain assemble data of response
     * @return array
     * @author timorchao
     */
    public function response(): array
    {
        //If different types of return values are required, they can be handled separately according to requestParam
        //instance default (BJ_KUDU,BJ_MySql,Office_MySql)
        $datas = ['results' => []];

        //get RequestId
        if (isset($this->responseData['request_id'])) {
            $datas['RequestId'] = $this->responseData['request_id'];
        }

        //get AsyncRequestId
        if (isset($this->responseData['request_id'], $this->responseData['async_request_id'])) {
            $datas['AsyncRequestId'] = $this->responseData['async_request_id'];
        }

        if (isset($this->responseData['msg'])) {
            $datas['msg'] = $this->responseData['msg'];
        }

        if (isset($this->responseData['code'])) {
            $datas['code'] = $this->responseData['code'];
        }

        // datas
        if (isset($this->responseData['data']['values']) && !empty($this->responseData['data']['values'])) {
//            foreach ($this->responseData['data']['values'] as $data) {
//                $tmp = [];
//                foreach ($data as $key => $value) {
//                    if (isset($this->responseData['data']['columns'][$key])) {
//                        $tmp[$this->responseData['data']['columns'][$key]] = $value;
//                    }
//                }
//                $datas['results'][] = $tmp;
//            }
            $datas['results'] = $this->responseData['data']['values'];
        }

        if (!empty($this->requestParam['async_request_id']) && !empty($this->responseData['data'])) {
            $datas['results'] = $this->responseData['data'];
        }

        // handle pagination
        if (!empty($this->requestParam['page'])) {
            $datas['total']      = isset($this->responseData['data']['RowCount']) && $this->responseData['data']['RowCount'] > 0 ?
                $this->responseData['data']['RowCount'] : 0;
            $datas['pagination'] = [
                'total'        => 0,
                'per_page'     => isset($this->responseData['data']['RowCount']) ? (int)$this->responseData['data']['PageSize'] : (int)$this->requestParam['PageSize'],
                'current_page' => isset($this->responseData['data']['CurrentPage']) ? (int)$this->responseData['data']['CurrentPage'] : (int)$this->requestParam['CurrentPage'],
            ];
            if (!empty($datas['total'])) {
                $datas['pagination']['total'] = (int)$datas['total'];
            }
        }
        return $datas;
    }
}