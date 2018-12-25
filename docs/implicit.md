# Implicit

### /authorize?response_type=token

```php
use devzyj\oauth2\server\AuthorizationServer;
use devzyj\oauth2\server\authorizes\ImplicitAuthorize;
use devzyj\oauth2\server\exceptions\OAuthServerException;

// 实例化授权服务器。
$authorizationServer = new AuthorizationServer([
    'accessTokenRepository' => new AccessTokenRepository(), // AccessTokenRepositoryInterface 实例。
    'clientRepository' => new ClientRepository(), // ClientRepositoryInterface 实例。
    'scopeRepository' => new ScopeRepository(), // ScopeRepositoryInterface 实例。
    'defaultScopes' => ['basic', 'basic2'], // 默认权限。
    'accessTokenDuration' => 3600, // 访问令牌持续 1 小时。
    'accessTokenCryptKey' => [
        'privateKey' => '/path/to/privateKey', // 访问令牌的私钥路径。
        'passphrase' => null, // 访问令牌的私钥密码。没有密码可以为 `null`。
    ],
    //'accessTokenCryptKey' => 'string key', // 访问令牌的字符串密钥。
]);

// 添加授权类型。
$authorizationServer->addAuthorizeType(new ImplicitAuthorize());

try {
    // 获取并验证授权请求。
    $authorizeRequest = $authorizationServer->getAuthorizeRequest($request);
    
    // 设置授权的用户。
    // 用户未登录时，需要重定向到登录页面。
    $authorizeRequest->setUserEntity(new UserEntity()); // UserEntityInterface 实例。
    
    // 设置是否同意授权，同意授权设置为 `true`，拒绝授权设置为 `false`。
    // 如果用户未确认授权，需要引导用户到授权页面。
    $authorizeRequest->setIsApproved(true);
    
    // 运行并返回授权成功的回调地址。
    $redirectUri = $authorizationServer->runAuthorizeTypes($authorizeRequest);
} catch (OAuthServerException $e) {
    $e->getHttpStatusCode();
    $e->getMessage();
    $e->getCode();
    
    // 处理异常。
    throw $e;
}

// 重定向到回调地址。
header("Location: {$redirectUri}");
exit();
```