<?php

namespace addons\csmipr\library\xcore\xcore\utils;

use think\Request;

/**
 * HttpRequest的工具封装类
 */
class XcRequestUtils
{

    /**
     * request->root
     *
     * @param string $url
     * @return string
     */
    public static function root($url = null)
    {
        $request = Request::instance();
        return $request->root($url);
    }

    public static function action()
    {
        $request = Request::instance();
        return $request->action();
    }
    /**
     * http://134.fa.net
     *
     * @return void
     */
    public static function webRoot($url = "")
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        //$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $url;
    }

    /**
     * 在request的基础上增加了是否必填和默认值
     *
     * 示例:XcRequestUtils::header("username",false,"csm");
     * @param string $paramname
     * @param boolean $isRequired
     * @param string $defaultValue
     * @return string
     */
    public static function header($paramname, $isRequired = false, $defaultValue = null)
    {
        $request = Request::instance();
        $paramvalue = $request->header($paramname);
        if ($isRequired == true && ($paramvalue == "" || trim($paramvalue) == "")) {
            $msg = "info." . $paramname . "_required";
            XcResponseUtils::error($msg);
        }
        if ($paramvalue == null || $paramvalue == '') {
            $paramvalue = $defaultValue;
        }

        return trim($paramvalue);
    }
    /**
     * 在request的基础上增加了是否必填和默认值
     *
     * 示例:XcRequestUtils::get("username",false,"csm");
     * @param string $paramname
     * @param boolean $isRequired
     * @param string $defaultValue
     * @return string
     */
    public static function get($paramname, $isRequired = false, $defaultValue = null)
    {
        $request = Request::instance();
        $paramvalue = $request->request($paramname);
        if ($isRequired == true && ($paramvalue == "" || trim($paramvalue) == "")) {
            $msg = "info." . $paramname . "_required";
            XcResponseUtils::error($msg);
        }
        if ($paramvalue == null || $paramvalue == '') {
            $paramvalue = $defaultValue;
        }

        return $paramvalue;
    }
 


    public static function token()
    {
        $request = Request::instance();
        return $request->token();
    }


    /**
     * 在request的基础上增加了是否必填和默认值
     *
     * 示例:XcRequestUtils::param("username",false,"csm");
     * @param string $paramname
     * @param mixed $isRequiredMsg
     * @param string $defaultValue
     * @return string
     */
    public static function param($paramname, $isRequiredMsg = false, $defaultValue = null)
    {
        $request = Request::instance();
        $paramvalue = $request->param($paramname);
        if ($isRequiredMsg !== false && ($paramvalue == "" || trim($paramvalue) == "")) {
            $msg = ($isRequiredMsg === true) ? "info." . $paramname . "_required" : $isRequiredMsg;
            XcResponseUtils::error($msg);
        }
        if ($paramvalue == null || $paramvalue == '') {
            $paramvalue = $defaultValue;
        }

        return trim($paramvalue);
    }

    /**
     * 在post的基础上增加了是否必填和默认值
     *
     * 示例:XcRequestUtils::post("username",false,"csm");
     * @param string $paramname
     * @param mixed $isRequiredMsg
     * @param string $defaultValue
     * @return string
     */
    public static function post($paramname, $isRequiredMsg = false, $defaultValue = null)
    {
        $request = Request::instance();
        $paramvalue = $request->post($paramname);
        if ($isRequiredMsg !== false && ($paramvalue == "" || trim($paramvalue) == "")) {
            $msg = ($isRequiredMsg === true) ? "info." . $paramname . "_required" : $isRequiredMsg;
            XcResponseUtils::error($msg);
        }
        if ($paramvalue == null || $paramvalue == '') {
            $paramvalue = $defaultValue;
        }
        return $paramvalue;
    }

    /**
     * 在post body的基础上增加了是否必填和默认值
     *
     * @param string $paramname
     * @param boolean $isRequired
     * @param string $defaultValue
     * @return string
     */
    public static function requestJsonBody($paramname, $isRequired = false, $defaultValue = null)
    {
        $request = Request::instance();
        $body = json_decode($request->getInput(), true);
        $paramvalue = isset($body[$paramname]) ? $body[$paramname] : null;
        if ($isRequired == true && ($paramvalue == "")) {
            $msg = "info." . $paramname . "_required";
            XcResponseUtils::error($msg);
        }
        if ($paramvalue == null || $paramvalue == '') {
            $paramvalue = $defaultValue;
        }
        return $paramvalue;
    }

    public static function requestBody()
    {
        $request = Request::instance();
        return $request->getInput();
    }

    public static function requestBodyJson()
    {
        $body = static::requestBody();
        $param = json_decode($body, true);
        return $param;
    }

    public static function getHttpRefer()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $tt = $_SERVER['HTTP_REFERER'];
            if (str_ends_with($tt, "/")) {
                $tt = substr($tt, 0, strlen($tt) - 1);
            }
            return $tt;
        } else {
            return "";
        }
    }

    // 完整的绝对地址
    public static function urlBase($url = '')
    {
        return $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . $url;
    }

    // 拼接出Admin后台的完整地址
    public static function urlAdmin($url = '')
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $request = Request::instance();
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $request->root() . $url;
    }

    // 相比较cdnurl差别是返回全路径
    public static function cdnurl($url)
    {
        if (empty($url)) {
            return '';
        }
        if (strpos(strtolower($url), "http://") === false && strpos(strtolower($url), "https://") === false) {
            $url = cdnurl($url);
        }
        if (strpos(strtolower($url), "http://") === false && strpos(strtolower($url), "https://") === false) {
            $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . $url;
        }
        return $url;
    }
}
