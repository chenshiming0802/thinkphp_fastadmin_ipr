<?php

namespace addons\csmipr\library\xcore\xcore\utils;

/**
 * Assert的工具封装类
 */
class XcAssertUtils
{
    public static function assertNotNull($value)
    {
        if (empty($value)) {
            throw Exception("");
        }
    }
}
