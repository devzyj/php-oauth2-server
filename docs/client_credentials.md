# Client Credentials

### /token?grant_type=client_credentials

```php
use devzyj\oauth2\server\AuthorizationServer;
use devzyj\oauth2\server\grants\ClientCredentialsGrant;
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
        //'signKey' => 'sign key', // 字符串签名密钥。
    ],
]);

// 添加授予类型。
$authorizationServer->addGrantType(new ClientCredentialsGrant());

try {
    // 运行并返回授予的认证信息。
    $credentials = $authorizationServer->runGrantTypes($request);
} catch (OAuthServerException $e) {
    $e->getHttpStatusCode();
    $e->getMessage();
    $e->getCode();
    
    // 处理异常。
    throw $e;
}

// 显示认证信息。
echo json_encode($credentials);
```