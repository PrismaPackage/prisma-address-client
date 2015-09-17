<?php

namespace Rentalhost\PrismaAddress;

use PHPUnit_Framework_TestCase;

/**
 * Class CepClientTest
 * @package Rentalhost\PrismaAddress
 */
class CepClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * Testa o método suggestUpdate().
     * @covers Rentalhost\PrismaAddress\CepClient::suggestUpdate
     * @covers Rentalhost\PrismaAddress\CepClient::normalizeCep
     * @covers Rentalhost\PrismaAddress\CepClient::normalizeInput
     * @covers Rentalhost\PrismaAddress\CepClient::getStates
     */
    public function testSuggestUpdate()
    {
        // CEP em banco/inválido.
        static::assertSame(false, CepClient::suggestUpdate('', '', '', '', ''));

        // Informações insuficientes (mínimo de uma informação).
        static::assertSame(false, CepClient::suggestUpdate('26295-096', '', '', '', 'RJ'));

        // Não informado o UF.
        static::assertSame(false, CepClient::suggestUpdate('26295-096', 'Rua Rosa', 'Marapicu', 'Nova Iguaçu', ''));

        // Finalmente envia a sugestão.
        static::assertSame(true, CepClient::suggestUpdate('26295-096', 'Rua Rosa', 'Marapicu', 'Nova Iguaçu', 'RJ'));
    }
}
