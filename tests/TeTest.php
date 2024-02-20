<?php
namespace Transfereasy\Pay\Tests;
use PHPUnit\Framework\TestCase;
use Transfereasy\Pay\Exception\CustomerException;
use Transfereasy\Pay\Exception\Exception;
use Transfereasy\Pay\Exception\ServerException;
use Transfereasy\Pay\Exception\SignException;
use Transfereasy\Pay\TE;

class TeTest extends TestCase
{
    public function testConfig()
    {
        $config = [
            'm_private_key_path' => './merchant_private_test.key',
            't_public_key_path' => './te_public_test.key',
            't_merchant_no' => '80000138',
            //'t_product_code' => 'CP0001',
        ];

        try {
            TE::config($config);
        } catch (Exception $e) {
             $this->assertEquals('t_product_code, not enough',$e->getMessage());
        }


    }

    /**
     * @throws CustomerException
     * @throws SignException
     */
    public function testSign(){
        $config = [
            'm_private_key_path' => './tests/merchant_private_test.key',
            't_public_key_path' => './tests/te_public_test.key',
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0001',
            'env' => 'test',
        ];

        $data = [
            'amount' => 3000,
            'currency' => 'HKD',
            'outTradeNo' => 'DE20211206000002',
            'merOrderNo' => 'DE20211206000002',
            'walletBrand' => 'ALIPAY_HK',
            'tradeType' => 'APP',
            'subAppid' => 'wx8888888888888888',
            'openid' => 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
        ];

        $getSignStr = TE::transaction($config)->getSign($data, 1707448808);

        $this->assertEquals("a23ebe47f7914ba063de7c754ef2dc3f20550c28de342aab6c30dec3b575be02", base64_decode($getSignStr));
    }

    /**
     * @return void
     * @throws CustomerException
     * @throws ServerException
     * @throws SignException
     */
    public function testPayment() {
        $config = [
            'm_private_key_path' => __DIR__.'/merchant_private_test.key',
            't_public_key_path' => __DIR__.'/te_public_test.key',
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0009',
            'env' => 'test',
        ];

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
        $get_payment_result = TE::transaction($config)->payment($params);
        var_dump($get_payment_result);
        $this->assertEquals('T2024021811010030', $get_payment_result['data']['outTradeNo']);
    }

    /**
     * @return void
     * @throws CustomerException
     * @throws ServerException
     * @throws SignException
     */
    public function testSearch()
    {
        $config = [
            'm_private_key_path' => __DIR__.'/merchant_private_test.key',
            't_public_key_path' => __DIR__.'/te_public_test.key',
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0001',
            'env' => 'test',
        ];

        $get_select_result = TE::transaction($config)->search('2023062713051977872139');
        $this->assertEquals(101, $get_select_result['data']['amount']);
    }

    /**
     * @return void
     * @throws CustomerException
     * @throws ServerException
     * @throws SignException
     */
    public function testSearchV2() {
        $config = [
            'm_private_key_path' => __DIR__.'/merchant_private_test.key',
            't_public_key_path' => __DIR__.'/te_public_test.key',
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0001',
            'env' => 'test',
            'version' => '2',
        ];

        $get_select_result = TE::transactionV2($config)->search('DE20230406094334', 'DE20230406094335');

        $this->assertEquals(700, $get_select_result['data']['orders'][0]['amount']);
    }

    /**
     * @return void
     * @throws CustomerException
     * @throws SignException
     */
    public function testNotify() {
        $config = [
            'm_private_key_path' => __DIR__.'/merchant_private_test.key',
            't_public_key_path' => __DIR__.'/te_public_test.key',
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0001',
            'env' => 'test',
        ];
        //从请求头中获取
        $sign_str = 'jQyGLbwAAlyKmayHfrWPfjXmWe5vy79GF/vn2EmlAnxc3qHBtortY39Xr1Tx7H77QY8klqg1HjlWoej3wnySX3gRH2t7D9Wa/UTypE9GmmW5GrqjGDOCghT/VVU+iWTV8yHf73z2TZzip6/aAHjU9RevD50sPlON2oAvpmrNTGM+J/3xPD2Iop6IYxFcCP0a8lq2QTVEaEBEW2/U6zONr7n6vUOUm323QElU8x/ny7RFzzJJYrxEeZNT6JgxebKchD/CYqia3FiIFIfPQ913Qodmid9Bdh9f74ELiR4eP47MHMxsKR2CdMQB2/Vx+1tW+WOEXcZ5AdopAuM1qGmXLA==';
        $timestamp = 1708307754;
        $params = '{"amount":"100","completeDateTime":"1708307753000","settleAmount":"14","settleCurrency":"USD","orderStatus":"SUCCESS","remark":"remark——DE20240219095530","paymentNo":"20240219095533P1141","walletBrand":"Alipay","payAmount":"100","outTradeNo":"DE20240219095530","settleExchangeRate":"7.21606417","customerId":"2102209000002586815D7","payCurrency":"CNY","currency":"CNY","tradeType":"WEB"}';
        $get_notify_result = TE::transaction($config)->notify($params, $sign_str, $timestamp);

        $this->assertArrayHasKey('amount', $get_notify_result);
    }

    /**
     * @return void
     * @throws CustomerException
     * @throws ServerException
     * @throws SignException
     */
    public function testRefund()
    {
        $config = [
            'm_private_key_path' => __DIR__ . '/merchant_private_test.key',
            't_public_key_path' => __DIR__ . '/te_public_test.key',
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0001',
            'env' => 'test',
        ];

        $params = [
            'outTradeNo' => 'oSBRVrYsGz',
            'paymentNo' => '20240220111636P4781',
            'refundAmount' => 100,
        ];
        $get_notify_result = TE::transaction($config)->refund($params);

        $this->assertEquals(1000, $get_notify_result['code']);
    }
}