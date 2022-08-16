<?php
namespace App\Lib;

use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\Exceptions\InvalidSignatureException;
use MiladRahimi\Jwt\Exceptions\ValidationException;
use MiladRahimi\Jwt\Generator;
use MiladRahimi\Jwt\Parser;
use MiladRahimi\Jwt\Validator\DefaultValidator;

class Jwt {
    /**
     * @var string $token JWT中的payload部分
    */
    public $token;

    /**
     * @var string $secret 密钥值
    */
    public $secret = '12345678901234567890123456789012';

    /**
     * 本方法用于根据给定信息生成JWT中的payload部分
     * @access public
     * @author Roach<18410269837@163.com>
     * @param array $claims 用于加密的有效信息
     * @return void
    */
    public function generate($claims) {
        $signer = new HS256($this->secret);
        $generator = new Generator($signer);
        $this->token = $generator->generate($claims);
    }

    /**
     * 本方法用于解析jwt 若解析成功则返回payload部分解析后的数据 失败则返回空
     * @access public
     * @author Roach<18410269837@163.com>
     * @return array|null
    */
    public function parse() {
        $signer = new HS256($this->secret);
        $validator = new DefaultValidator();
        $parser = new Parser($signer, $validator);
        try {
            $claims = $parser->parse($this->token);
        } catch (InvalidSignatureException $e) {
            $claims = null;
        } finally {
            return $claims;
        }
    }
}
