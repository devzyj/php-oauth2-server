<?php
/**
 * @link https://github.com/devzyj/php-oauth2-server
 * @copyright Copyright (c) 2018 Zhang Yan Jiong
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace devzyj\oauth2\server\interfaces;

/**
 * 用户实体接口。
 * 
 * @author ZhangYanJiong <zhangyanjiong@163.com>
 * @since 1.0
 */
interface UserEntityInterface
{
    /**
     * 获取用户的标识符。
     *
     * @return string
     */
    public function getIdentifier();
    
    /**
     * 获取用户的默认权限。
     * 只对 `password` 授予模式生效。
     * 优先使用方法的返回值，如果返回值不是数组，则使用全局的默认权限。
     *
     * @return ScopeEntityInterface[]
     */
    public function getDefaultScopeEntities();
}