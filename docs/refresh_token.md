# Refresh Token

### /token?grant_type=refresh_token

```php
use devzyj\oauth2\server\AuthorizationServer;
use devzyj\oauth2\server\grants\RefreshTokenGrant;
use devzyj\oauth2\server\exceptions\OAuthServerException;

// 实例化授权服务器。
$authorizationServer = new AuthorizationServer([
    'accessTokenRepository' => new AccessTokenRepository(), // AccessTokenRepositoryInterface 实例。
    'clientRepository' => new ClientRepository(), // ClientRepositoryInterface 实例。
    'refreshTokenRepository' => new RefreshTokenRepository(), // RefreshTokenRepositoryInterface 实例。
    'scopeRepository' => new ScopeRepository(), // ScopeRepositoryInterface 实例。
    'userRepository' => new UserRepository(), // UserRepositoryInterface 实例。
    'accessTokenDuration' => 3600, // 访问令牌持续 1 小时。
    'accessTokenCryptKey' => [
        'privateKey' => '/path/to/privateKey', // 访问令牌的私钥路径。
        'passphrase' => null, // 访问令牌的私钥密码。没有密码可以为 `null`。
    ],
    //'accessTokenCryptKey' => 'string key', // 访问令牌的字符串密钥。
    'refreshTokenDuration' => 2592000, // 访问令牌持续 30 天。
    ‘refreshTokenCryptKey’ => [
        'ascii' => 'def0000086937b.....', // 使用 `vendor/bin/generate-defuse-key` 生成的字符串。
        //'path' => '/path/to/asciiFile', // 保存了 `vendor/bin/generate-defuse-key` 生成的字符串的文件路径。
        //'password' => 'string key', // 字符串密钥。
    ],
]);

// 添加授予类型。
$authorizationServer->addGrantType(new RefreshTokenGrant());

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