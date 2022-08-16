<?php
namespace App\Lib;

use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
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
     * 本方法用于解析jwt并和给定的信息比对 若有效载荷解析后与给定信息一致则返回true 否则返回false
     * @access public
     * @author Roach<18410269837@163.com>
     * @param array $claims 用于比对的有效信息
     * @return bool
    */
    public function parse($claims) {
        $signer = new HS256($this->secret);
        $parser = new Parser($signer);
        try {
            $jwt = $parser->parse($this->token);
        } catch (ValidationException $e) {
            return false;
        }

        if ($jwt == $claims) {
            return true;
        }
        return false;
    }
}
