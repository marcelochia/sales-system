<?php

namespace Tests\Unit;

use App\Domain\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testEmailMustBeValid(): void
    {
        $isValidEmail = Email::isValid('usuario@organizacao.com');

        $this->assertTrue($isValidEmail);
    }

    public function testEmailMustBeInvalid(): void
    {
        $isValidEmail1 = Email::isValid('usuarioorganizacao.com');
        $isValidEmail2 = Email::isValid('usuario@organizacaocom');

        $this->assertFalse($isValidEmail1);
        $this->assertFalse($isValidEmail2);
    }
}
