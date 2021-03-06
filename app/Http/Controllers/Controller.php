<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    const SUCCESS_MSG = 'success';
    const FAIL_MSG = 'fail';

    public function __construct(Request $request){
//        $this->staff = session('staff');
        $this->request = $request;
    }

    /**
     * 返回结果
     * @param array|string $data
     * @param string $message
     * @param int $code
     * @return string
     * @throws \Exception
     */
    public function output($data = '', $message = '', $code = 200)
    {
        if (!empty($data)) {
            $ret['data'] = $data;
        } else {
            $ret['data'] = null;
        }
        if (!empty($message)) {
            $ret['message'] = $message;
        } else {
            $ret['message'] = self::SUCCESS_MSG;
        }
        $ret['code'] = 0;
        $headers = [];
        if (!empty($this->request->get('newToken'))) {
            $headers['token'] = $this->request->newToken;
        }
        return response()->json($ret, $code, $headers);

    }

    /**
     * 错误output
     * @param string $message 信息
     * @param int $code 错误代码
     * @param bool | array |string $data 额外数据
     * @return string
     */
    public function error_output($message = self::FAIL_MSG, $code = -1, $data = null)
    {
        $ret['data'] = $data;//空
        $ret['message'] = $message;
        $ret['code'] = $code;
        return response()->json($ret);
    }

    public function validate($request, $rules) {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Exception($validator->messages());
        }

        return true;

    }
}
