<?php

namespace Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use App\Rules\CpfCnpj;

class CpfCnpjTest extends TestCase
{
    public function test_validates_correct_cpf()
    {
        $rule = new CpfCnpj();
        $result = $rule->passes('cpf_cnpj', '509.697.020-51');

        $this->assertTrue($result);
    }

    public function test_validates_correct_cnpj()
    {
        $rule = new CpfCnpj();
        $result = $rule->passes('cpf_cnpj', '94.202.554/0001-15');

        $this->assertTrue($result);
    }

    public function test_validates_incorrect_cpf()
    {
        $rule = new CpfCnpj();
        $result = $rule->passes('cpf_cnpj', '111.111.111-51');

        $this->assertFalse($result);
    }

    public function test_validates_incorrect_cnpj()
    {
        $rule = new CpfCnpj();
        $result = $rule->passes('cpf_cnpj', '11.111.111/1111-11');

        $this->assertFalse($result);
    }

    public function test_ignores_ponctuation_cnpj()
    {
        $rule = new CpfCnpj();
        $result = $rule->passes('cpf_cnpj', '94202554000115');

        $this->assertTrue($result);
    }

    public function test_ignores_ponctuation_cpf()
    {
        $rule = new CpfCnpj();
        $result = $rule->passes('cpf_cnpj', '50969702051');

        $this->assertTrue($result);
    }
}
