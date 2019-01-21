# Validate Access Token

```php
use devzyj\oauth2\server\ResourceServer;
use devzyj\oauth2\server\exceptions\BadRequestException;
use devzyj\oauth2\server\exceptions\InvalidAccessTokenException;

// 实例化对像。
$resourceServer = new ResourceServer([
    'accessTokenRepository' => new AccessTokenRepository(), // AccessTokenRepositoryInterface 实例。
    'accessTokenCryptKey' => [
        'publicKey' => '/path/to/publicKey' // 访问令牌的公钥路径。
        //'signKey' => 'sign key', // 字符串签名密钥。
    ],
    //'accessTokenQueryParam' => 'access-token', // 只在 [[validateServerRequest()]] 中有效。
]);

// 调用方法一：
try {
    // 验证请求。
    $accessTokenEntity = $resourceServer->validateServerRequest($request);
} catch (BadRequestException $exception) {
    // 请求中缺少访问令牌参数。
    
} catch (InvalidAccessTokenException $exception) {
    // 无效的访问令牌。
    
}

// 或者方法二：
try {
    // 已获取到的访问令牌。
    $strAccessToken = '';
    
    // 验证访问令牌。
    $accessTokenEntity = $resourceServer->validateAccessToken($strAccessToken);
} catch (InvalidAccessTokenException $exception) {
    // 无效的访问令牌。
    
}
```