<?php

namespace Alura\Leilao\TEsts\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use DomainException;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{

    public function testLeilaoNaoDeveReceberLancesRepetidos(){
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor 2 lances consecutivos');

        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));
    }


    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario(){

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor mais de 5 lances por leilão');

        $leilao = new Leilao('Brasília Amarela');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao,1000));
        $leilao->recebeLance(new Lance($maria,1500));
        $leilao->recebeLance(new Lance($joao,2000));
        $leilao->recebeLance(new Lance($maria,2500));
        $leilao->recebeLance(new Lance($joao,3000));
        $leilao->recebeLance(new Lance($maria,3500));
        $leilao->recebeLance(new Lance($joao,4000));
        $leilao->recebeLance(new Lance($maria,4500));
        $leilao->recebeLance(new Lance($joao,5000));
        $leilao->recebeLance(new Lance($maria,5500));

        $leilao->recebeLance(new Lance($joao,6000));
    }

    /**
     *
     * @dataProvider geraLance
     * @param integer $qtdLances
     * @param Leilao $leilao
     * @param array $valores
     * @return void
     */
    public function testLeilaoDeveReceberLances(
        int $qtdLances, 
        Leilao $leilao, 
        array $valores
    ) {
        static::assertCount($qtdLances, $leilao->getLances());
        foreach($valores as $i => $valoresEsperados){
            static::assertEquals($valoresEsperados, $leilao->getLances()[$i]->getValor());
        }

    }

    /**
     *
     * @return Lances[]
     */
    public function geraLance()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilaoCom2Lances = new Leilao('Fiat 147 0KM');
        $leilaoCom2Lances->recebeLance(new Lance($joao, 1000));
        $leilaoCom2Lances->recebeLance(new Lance($maria, 2000));

        $leilaoCom1Lances = new Leilao('Fusca 1972 0KM');
        $leilaoCom1Lances->recebeLance(new Lance($maria, 5000));

        return [
            '2-lances' => [2, $leilaoCom2Lances, [1000, 2000]],
            '1-lances' => [1, $leilaoCom1Lances, [5000]],
        ];

    }
}