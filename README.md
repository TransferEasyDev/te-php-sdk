欢迎使用 Transfereasy  SDK for PHP 。

Transfereasy SDK for PHP SDK可以自动帮您满足能力调用过程中所需的证书校验、加签、验签、发送HTTP请求等非功能性要求。

Transfereasy SDK主要目标是提升开发者在**服务端**集成Transfereasy的各类能力的效率。

## 环境要求
1. Transfereasy SDK for PHP 需要配合`PHP 7.4`或其以上版本。

2. 使用 Transfereasy SDK for PHP 之前 ，您需要先前往[Transfereasy-商户系统](https://mch.transfereasy.com/)完成开发者接入的一些准备工作，或联系开发支持人员配置等。

3. 准备工作完成后，注意保存如下信息，后续将作为使用SDK的输入。

`产品CODE`、`TE的公钥`、`TE的商户号`


## 安装依赖
### 通过[Composer](https://packagist.org/packages/transfereasy/pay/)在线安装依赖（推荐）

`composer require transfereasy/pay`

### 本地手动集成依赖（适用于自己修改源码后的本地重新打包安装）
1. 本机安装配置[Composer](https://getcomposer.org/)工具。
2. 在本`README.md`所在目录下，执行`composer install`，下载SDK依赖。
3. 依赖文件会下载到`vendor`目录下。

## 使用
以下这段代码示例向您展示了使用Transfereasy SDK for PHP调用一个API的步骤：

1. 设置config
2. 发起API调用。
3. 处理响应或异常。

```php
<?php

require 'vendor/autoload.php';
use Transfereasy\Pay\TE;
use Transfereasy\Pay\Exception\Exception;

//1. 设置config
 $config = [
            'm_private_key_path' => '',//商户私钥文件路径，如：'./merchant_private_test.key'
            't_public_key_path' => '', //TE公钥文件路径 如： './te_public_test.key'
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0001',
            'env' => 'test', //设置为测试环境，生产环境可忽略该参数
        ];

try {
    //2. 发起API调用（以收单下单接口为例）
   $params = [
            'amount' => 100,
            'clientIp' => '127.0.0.1',
            'currency' => 'HKD',
            'notifyUrl' => 'https://demo-test.transfereasy.com/demo/paymentNotify',
            'outTradeNo' => 'T2024021811010030',
            'storeTerminalId' => 's',
            'settleCurrency' => 'HKD',
            'productId' => 'xx',
            'productInfo' => [
                [
                    'description' => "测试商品 1 个",
                    'amount' => 100,
                    'name' => "测试商品",
                    'quantity' => 1
                ]
            ],
            'returnUrl' => 'https://demo-test.transfereasy.com/demo/returnUrl',
            'tradeType' => 'NATIVE'
        ];
        //如果调用V2接口使用TE::transactionV2($config)
        $get_payment_result = TE::transaction($config)->payment($params);
        //处理自己的业务逻辑
        //...
} catch (Exception $e) {
    echo "调用失败，". $e->getMessage(). PHP_EOL;;
}

```
## 已支持的API列表

| 类别       | 接口方法名称        | 版本    |
|----------|---------------|-------|
| 收单       | payment（下单）   | V1    | 
| 收单 | getSign（获取签名） | V1    |
| 收单 | search        | V1，V2 |
| 收单 | notify        | V1    | 
| 收单 | refund        | V1    |

> 注：更多场景的API持续更新中或联系技术支持进行更新，敬请期待。

## Transfereasy接口文档
[API Doc](https://mrdoc.transfereasy.com/)
