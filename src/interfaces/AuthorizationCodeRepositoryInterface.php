<?php
/**
 * @link https://github.com/devzyj/php-oauth2-server
 * @copyright Copyright (c) 2018 Zhang Yan Jiong
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace devzyj\oauth2\server\interfaces;

use devzyj\oauth2\server\exceptions\UniqueIdentifierException;

/**
 * 授权码存储库接口。
 * 
 * @author ZhangYanJiong <zhangyanjiong@163.com>
 * @since 1.0
 */
interface AuthorizationCodeRepositoryInterface
{
    /**
     * 创建新的授权码实例。
     * 
     * @return AuthorizationCodeEntityInterface 新的授权码实例。
     */
    public function createAuthorizationCodeEntity();

    /**
     * 生成授权码唯一标识。
     *
     * @return string|null 授权码唯一标识。如果返回 `null`，则自动生成。
     */
    public function generateAuthorizationCodeUniqueIdentifier();
    
    /**
     * 保存授权码。
     * 
     * @param AuthorizationCodeEntityInterface $authorizationCodeEntity 授权码。
     * @throws UniqueIdentifierException 授权码标识重复。
     */
    public function saveAuthorizationCodeEntity(AuthorizationCodeEntityInterface $authorizationCodeEntity);
    
    /**
     * 撤销授权码。
     * 
     * @param string $identifier 授权码标识。
     */
    public function revokeAuthorizationCodeEntity($identifier);

    /**
     * 授权码是否已撤销。
     *
     * @param string $identifier 授权码标识。
     * @return boolean 是否已撤销。
     */
    public function isAuthorizationCodeEntityRevoked($identifier);

    /**
     * 序列化授权码，用于最终的响应结果。
     *
     * @param AuthorizationCodeEntityInterface $authorizationCodeEntity 授权码。
     * @param mixed $cryptKey 授权码密钥。
     * @return string 序列化的授权码。
     */
    public function serializeAuthorizationCodeEntity(AuthorizationCodeEntityInterface $authorizationCodeEntity, $cryptKey);
    
    /**
     * 反序列化授权码，用于从请求中接收到的授权码。
     * 
     * 返回的实例必需要设置的属性如下：
     *     - [[setIdentifier()]]
     *     - [[setExpires()]]
     *     - [[setRedirectUri()]]
     *     - [[setClientIdentifier()]]
     *     - [[setUserIdentifier()]]
     *     - [[addScopeIdentifier()]]
     * 
     * @param string $serializedAuthorizationCode 已序列化的授权码。
     * @param mixed $cryptKey 授权码密钥。
     * @return AuthorizationCodeEntityInterface 授权码实例。
     */
    public function unserializeAuthorizationCodeEntity($serializedAuthorizationCode, $cryptKey);
}