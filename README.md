# php-oauth2-server
PHP OAuth2 Server


# Installation

```bash
composer require --prefer-dist "devzyj/php-oauth2-server" "~1.0.0"
```

or add

```json
"devzyj/php-oauth2-server" : "~1.0.0"
```


# Usage

### /authorize?response_type=xxx

```php
use devzyj\oauth2\server\AuthorizationServer;
use devzyj\oauth2\server\authorizes\CodeAuthorize;
use devzyj\oauth2\server\authorizes\ImplicitAuthorize;
use devzyj\oauth2\server\exceptions\OAuthServerException;

// 实例化授权服务器。
$authorizationServer = new AuthorizationServer([
    'accessTokenRepository' => new AccessTokenRepository(), // AccessTokenRepositoryInterface 实例。
    'authorizationCodeRepository' => new AuthorizationCodeRepository(), // AuthorizationCodeRepositoryInterface 实例。
    'clientRepository' => new ClientRepository(), // ClientRepositoryInterface 实例。
    'scopeRepository' => new ScopeRepository(), // ScopeRepositoryInterface 实例。
    'defaultScopes' => ['basic', 'basic2'], // 默认权限。
    'accessTokenDuration' => 3600, // 访问令牌持续 1 小时。
    'accessTokenCryptKey' => [
        'privateKey' => '/path/to/privateKey', // 访问令牌的私钥路径。
        'passphrase' => null, // 访问令牌的私钥密码。没有密码可以为 `null`。
        //'signKey' => 'sign key', // 字符串签名密钥。
    ],
    'authorizationCodeDuration' => 600, // 授权码持续 10 分钟。
    'authorizationCodeCryptKey' => [
        'ascii' => 'def0000086937b.....', // 使用 `vendor/bin/generate-defuse-key` 生成的字符串。
        //'path' => '/path/to/asciiFile', // 保存了 `vendor/bin/generate-defuse-key` 生成的字符串的文件路径。
        //'password' => 'string key', // 字符串密钥。
    ],
]);

// 添加授权类型。
$authorizationServer->addAuthorizeType(new CodeAuthorize());
$authorizationServer->addAuthorizeType(new ImplicitAuthorize());

try {
    // 获取并验证授权请求。
    // $request 不强制要求实现 ServerRequestInterface 接口，只需要实例中包函接口中的方法。
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

### /token?grant_type=xxx

```php
use devzyj\oauth2\server\AuthorizationServer;
use devzyj\oauth2\server\grants\AuthorizationCodeGrant;
use devzyj\oauth2\server\grants\PasswordGrant;
use devzyj\oauth2\server\grants\ClientCredentialsGrant;
use devzyj\oauth2\server\grants\RefreshTokenGrant;
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
        //'signKey' => 'sign key', // 字符串签名密钥。
    ],
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
$authorizationServer->addGrantType(new PasswordGrant());
$authorizationServer->addGrantType(new ClientCredentialsGrant());
$authorizationServer->addGrantType(new RefreshTokenGrant());

try {
    // 运行并返回授予的认证信息。
    // $request 不强制要求实现 ServerRequestInterface 接口，只需要实例中包函接口中的方法。
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

- [Authorization Code](docs/authorization_code.md)
- [Implicit](docs/implicit.md)
- [Password](docs/password.md)
- [Client Credentials](docs/client_credentials.md)
- [Refresh Token](docs/refresh_token.md)
- [Validate Access Token](docs/validate_access_token.md)

# Interfaces

需要实现的接口。

- devzyj\oauth2\server\interfaces\AccessTokenEntityInterface
- devzyj\oauth2\server\interfaces\AccessTokenRepositoryInterface
- devzyj\oauth2\server\interfaces\AuthorizationCodeEntityInterface
- devzyj\oauth2\server\interfaces\AuthorizationCodeRepositoryInterface
- devzyj\oauth2\server\interfaces\ClientEntityInterface
- devzyj\oauth2\server\interfaces\ClientRepositoryInterface
- devzyj\oauth2\server\interfaces\RefreshTokenEntityInterface
- devzyj\oauth2\server\interfaces\RefreshTokenRepositoryInterface
- devzyj\oauth2\server\interfaces\ScopeEntityInterface
- devzyj\oauth2\server\interfaces\ScopeRepositoryInterface
- devzyj\oauth2\server\interfaces\UserEntityInterface
- devzyj\oauth2\server\interfaces\UserRepositoryInterface
- devzyj\oauth2\server\interfaces\ServerRequestInterface 不强制要求实现该接口，只需要实例中包函接口中的方法。

# Traits

实现了接口中的一些方法。

- devzyj\oauth2\server\traits\AccessTokenEntityTrait
- devzyj\oauth2\server\traits\AccessTokenRepositoryTrait
- devzyj\oauth2\server\traits\AuthorizationCodeEntityTrait
- devzyj\oauth2\server\traits\AuthorizationCodeRepositoryTrait
- devzyj\oauth2\server\traits\RefreshTokenEntityTrait
- devzyj\oauth2\server\traits\RefreshTokenRepositoryTrait

# Generating public and private keys

```bash
openssl genrsa -out private.key 2048
openssl rsa -in private.key -pubout -out public.key
```

```bash
openssl genrsa -passout pass:_passphrase_ -out private.key 2048
openssl rsa -in private.key -passin pass:_passphrase_ -pubout -out public.key
```

# Usaging Code Challenge

```php
// 配置授权服务器。
$authorizationServer = new AuthorizationServer();
$authorizationServer->addAuthorizeType(new CodeAuthorize([
    'enableCodeChallenge' => true,
    'defaultCodeChallengeMethod' => 'S256',
]));
$authorizationServer->addGrantType(new AuthorizationCodeGrant([
    'enableCodeChallenge' => true,
]));

// 生成 Code Verifier
$codeVerifier = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

// 生成 Code Challenge
$codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
```