<?php
/**
 * @link https://github.com/devzyj/php-oauth2-server
 * @copyright Copyright (c) 2018 Zhang Yan Jiong
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace devzyj\oauth2\server\exceptions;

/**
 * ServerErrorException 表示服务器端错误，状态码为 500 的 HTTP 异常。
 * 
 * @author ZhangYanJiong <zhangyanjiong@163.com>
 * @since 1.0
 */
class ServerErrorException extends OAuthServerException
{
    /**
     * Constructor.
     * 
     * @param string $message 错误信息。
     * @param int $code 错误编码。
     * @param \Exception $previous 前一个异常。
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct(500, $message, $code, $previous);
    }
}
