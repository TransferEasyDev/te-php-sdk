<?php
namespace Transfereasy\Pay\Tests;
use PHPUnit\Framework\TestCase;
use Transfereasy\Pay\Exception\Exception;
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

    public function testSign(){
        $config = [
            'm_private_key_path' => './tests/merchant_private_test.key',
            't_public_key_path' => './tests/te_public_test.key',
            't_merchant_no' => '80000138',
            't_product_code' => 'CP0001',
            'env' => 'test',
            'e' => 'a'
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
}