<?php

namespace Rentalhost\PrismaAddress;

use GuzzleHttp\Client;

/**
 * Class CepClient
 * @package Rentalhost\PrismaAddress
 */
class CepClient
{
    /**
     * Endereço de sugestão.
     * @var string
     */
    private static $WS_SUGGESTION = 'http://address.webservice.rentalhost.net/v1/cep/suggests/%u';

    /**
     * Envia um pedido de atualização.
     *
     * @param string $addressCep      Número do CEP.
     * @param string $addressStreet   Logradouro.
     * @param string $addressDistrict Bairro.
     * @param string $addressCity     Cidade.
     * @param string $addressUf       Estado.
     *
     * @return bool
     */
    public static function suggestUpdate($addressCep, $addressStreet, $addressDistrict, $addressCity, $addressUf)
    {
        $addressCepNormalized = self::normalizeCep($addressCep);
        $addressStreet = self::normalizeInput($addressStreet);
        $addressDistrict = self::normalizeInput($addressDistrict);
        $addressCity = self::normalizeInput($addressCity);
        $addressUf = strtoupper(self::normalizeInput($addressUf));

        // Ignora se for um CEP inválido.
        if ($addressCepNormalized === false) {
            return false;
        }

        // Ignora se houver poucas informações.
        if (!$addressStreet &&
            !$addressDistrict &&
            !$addressCity
        ) {
            return false;
        }

        // Ignora se o UF não estiver na lista.
        if (!in_array($addressUf, self::getStates(), true)) {
            return false;
        }

        // Finalmente envia a sugestão.
        $client = new Client();
        $client->post(sprintf(self::$WS_SUGGESTION, $addressCepNormalized), [
            'synchronous' => false,
            'form_params' => [
                'street' => $addressStreet,
                'district' => $addressDistrict,
                'city' => $addressCity,
                'uf' => $addressUf,
            ],
        ]);

        return true;
    }

    /**
     * Normaliza o CEP.
     *
     * @param string $input CEP a ser normalizado.
     *
     * @return string|false
     */
    private static function normalizeCep($input)
    {
        $input = preg_replace('/\D/', '', $input);

        if (strlen($input) !== 8) {
            return false;
        }

        return $input;
    }

    /**
     * Normaliza os dados de entrada.
     *
     * @param string $input Informação que será normalizada.
     *
     * @return string
     */
    private static function normalizeInput($input)
    {
        return trim(preg_replace('/\s\s+/', '', $input));
    }

    /**
     * Lista de estados brasileiros.
     * @return string[]
     */
    private static function getStates()
    {
        return [
            'AC',
            'AL',
            'AP',
            'AM',
            'BA',
            'CE',
            'DF',
            'ES',
            'GO',
            'MA',
            'MT',
            'MS',
            'MG',
            'PA',
            'PB',
            'PR',
            'PE',
            'PI',
            'RJ',
            'RN',
            'RS',
            'RO',
            'RR',
            'SC',
            'SP',
            'SE',
            'TO',
        ];
    }
}
