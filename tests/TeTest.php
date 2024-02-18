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
            't_product_code' => 'CP0001',
            'env' => 'test',
        ];

        $params = [
            'amount' => 1000,
            'clientIp' => '127.0.0.1',
            'currency' => 'HKD',
            'notifyUrl' => 'https://demo-test.transfereasy.com/demo/paymentNotify',
            'osType' => 'IOS',
            'outTradeNo' => 'T2024021811010021',
            'productInfo' => [
                [
                    'description' => "测试商品 1 个",
                    'amount' => 1000,
                    'name' => "测试商品",
                    'quantity' => 1
                ]
            ],
            'returnUrl' => 'https://demo-test.transfereasy.com/demo/returnUrl',
            'tradeType' => 'APP',
            'walletBrand' => 'ALIPAY_HK'
        ];
        $get_payment_result = TE::transaction($config)->payment($params);
        $this->assertEquals('T2024021811010021', $get_payment_result['data']['outTradeNo']);
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
}