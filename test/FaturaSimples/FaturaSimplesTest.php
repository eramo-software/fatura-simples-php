<?php

require_once __DIR__.'/../../lib/FaturaSimples.php';

class FaturaSimplesTest extends PHPUnit_Framework_TestCase
{
    /**
     * Reseta o objeto antes de cada teste.
     */
    public function setUp()
    {
        FaturaSimples::reset();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage informe uma chave de api
     */
    public function testChaveApiInvalida()
    {
        FaturaSimples::configure('dominio', '');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage informe uma chave de api
     */
    public function testChaveApiInvalida2()
    {
        FaturaSimples::configure('dominio', 'abddee');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage domínio
     */
    public function testDominioInvalido()
    {
        FaturaSimples::configure('', '2y6v8qnX3SUHZb0sanKWrQUnTAY');
    }

    /**
     * Teste de configuração válida.
     */
    public function testValido()
    {
        $this->assertTrue(FaturaSimples::configure('dominio', '2y6v8qnX3SUHZb0sanKWrQUnTAY'));
    }
}
