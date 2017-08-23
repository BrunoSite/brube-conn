<?php

/**
 * <b>Update</b> [ Conn ]
 * Classe responsável pela atualização de informações no banco de dados
 * @package brube/system
 * @author Bruno Moura <contato@brunosite.com>
 * @copyright (c) 2017, MIT
 * @license http://brunosite.com/package/brube-system
 */

namespace Conn;

class Update extends Conn {

    private $Table;
    private $Data;
    private $Terms;
    private $Links;
    private $Result;

    /** @var PDOstatements */
    private $Update;

    /** @var PDO */
    private $Conn;

    /**
     * <b>run:</b> Atualizar informações no banco de dados usando prepared statements
     * @param STRING $Table Tabela para qual a atualização será feita
     * @param array $Data Array das informações a serem atulizadas
     */
    public function run($Table, array $Data, $Terms, $ParceString = null) {
        $this->Table = (string) $Table;
        $this->Data = $Data;
        $this->Terms = (string) $Terms;
        parse_str($ParceString, $this->Links);

        $this->buildPDO();
        $this->execute();
    }

    /**
     * Retorna um FALSE ou o ID do último elemento atualizado banco de dados
     * @return BOOLEAN
     */
    public function result() {
        return $this->Result;
    }

    /**
     * Retorna FALSE ou TRUE caso algum dado tenha sido atualizado
     * @return BOOLEAN
     */
    public function count() {
        return $this->Update->rowCount();
    }

    /**
     * Reescreve uma parsesitring e retorna os resultados obtidos do banco de dados 
     * @param type $ParceString
     */
    public function parce($ParceString) {
        parse_str($ParceString, $this->Links);
        $this->buildPDO();
        $this->execute();
    }

    /*     * **********************************************
     * *************** MÉTODOS PRIVADOS **************
     * *********************************************** */

    private function connPDO() {
        $this->Conn = parent::getConn();
        $this->Update = $this->Conn->prepare($this->Update);
    }

    private function buildPDO() {
        foreach ($this->Data as $key => $value):
            $dados [] = $key . ' = :' . $key;
        endforeach;
        $dados = implode(', ', $dados);
        $this->Update = "UPDATE {$this->Table} SET {$dados} {$this->Terms}";
    }

    private function execute() {
        $this->connPDO();
        try {
            $this->Update->execute(array_merge($this->Data, $this->Links));
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = NULL;
            echo '<b>Erro ao Atualizar conteúdo: </b>' . $e->getMessage() . '-' . $e->getCode();
            die;
        }
    }

}
