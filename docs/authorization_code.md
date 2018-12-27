# Authorization Code

### /authorize?response_type=code

```php
use devzyj\oauth2\server\AuthorizationServer;
use devzyj\oauth2\server\authorizes\CodeAuthorize;
use devzyj\oauth2\server\exceptions\OAuthServerException;

// 实例化授权服务器。
$authorizationServer = new AuthorizationServer([
    'authorizationCodeRepository' => new AuthorizationCodeRepository(), // AuthorizationCodeRepositoryInterface 实例。
    'clientRepository' => new ClientRepository(), // ClientRepositoryInterface 实例。
    'scopeRepository' => new ScopeRepository(), // ScopeRepositoryInterface 实例。
    'defaultScopes' => ['basic', 'basic2'], // 默认权限。
    'authorizationCodeDuration' => 600, // 授权码持续 10 分钟。
    'authorizationCodeCryptKey' => [
        'ascii' => 'def0000086937b.....', // 使用 `vendor/bin/generate-defuse-key` 生成的字符串。
        //'path' => '/path/to/asciiFile', // 保存了 `vendor/bin/generate-defuse-key` 生成的字符串的文件路径。
        //'password' => 'string key', // 字符串密钥。
    ],
]);

// 添加授权类型。
$authorizationServer->addAuthorizeType(new CodeAuthorize());

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
    $redirectUrl = $authorizationServer->runAuthorizeTypes($authorizeRequest);
} catch (OAuthServerException $e) {
    $e->getHttpStatusCode();
    $e->getMessage();
    $e->getCode();
    
    // 处理异常。
    throw $e;
}

// 重定向到回调地址。
header("Location: {$redirectUrl}");
exit();
```

### /token?grant_type=authorization_code

```php
use devzyj\oauth2\server\AuthorizationServer;
use devzyj\oauth2\server\grants\AuthorizationCodeGrant;
use devzyj\oauth2\server\exceptions\OAuthServerException;

// 实例化授权服务器。
$authorizationServer = new AuthorizationServer([
    'accessTokenRepository' => new AccessTokenRepository(), // AccessTokenRepositoryInterface 实例。
    'authorizationCodeRepository' => new AuthorizationCodeRepository(), // AuthorizationCodeRepositoryInterface 实例。
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
    'authorizationCodeCryptKey' => [
        'ascii' => 'def0000086937b.....', // 使用 `vendor/bin/generate-defuse-key` 生成的字符串。
        //'path' => '/path/to/asciiFile', // 保存了 `vendor/bin/generate-defuse-key` 生成的字符串的文件路径。
        //'password' => 'string key', // 字符串密钥。
    ],
    'refreshTokenDuration' => 2592000, // 访问令牌持续 30 天。
    'refreshTokenCryptKey' => [
        'ascii' => 'def0000086937b.....', // 使用 `vendor/bin/generate-defuse-key` 生成的字符串。
        //'path' => '/path/to/asciiFile', // 保存了 `vendor/bin/generate-defuse-key` 生成的字符串的文件路径。
        //'password' => 'string key', // 字符串密钥。
    ],
]);

// 添加授予类型。
$authorizationServer->addGrantType(new AuthorizationCodeGrant());

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