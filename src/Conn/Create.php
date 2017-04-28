<?php
/**
 * <b>Create</b> [ Conn ]
 * Classe responsável pelo cadastramento genérico de iformações no banco de dados.
 * @package brube/system
 * @author Bruno Moura <contato@brunoiste.com>
 * @copyright (c) 2017, MIT
 * @license http://brunosite.com/package/brube-system
 */

namespace Conn;

class Create extends Conn {
    /* tabela do banco de dados */

    private $Table;

    /* dados a serem inseridos */
    private $Data;

    /* resultado da operação */
    private $Result;

    /** @var PDOstatements */
    private $Create;

    /** @var PDO */
    private $Conn;

    /**
     * <b>RunCreate:</b> Executar o armazenamento de informações no banco de dados usando prepared statements
     * @param STRING $Table Tabela para qual o cadastro será armazenado
     * @param array $Data Array das informações a serem armazenadas
     */
    public function RunCreate($Table, array $Data) {
        $this->Table = (string) $Table;
        $this->Data = $Data;

        $this->MontarString();
        $this->Execute();
    }

    /**
     * Retorna um FALSE ou o ID do último elemento criado no banco de dados
     * @return array
     */
    public function getResult() {
        return $this->Result;
    }

    /*     * **********************************************
     * *************** MÉTODOS PRIVADOS **************
     * *********************************************** */

    private function ConectPDO() {
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($this->Create);
    }

    private function MontarString() {
        $dados = implode(', ', array_keys($this->Data));
        $links = ':' . implode(', :', array_keys($this->Data));
        $this->Create = "INSERT INTO {$this->Table} ({$dados}) VALUES ({$links})";
    }

    private function Execute() {
        $this->ConectPDO();
        try {
            $this->Create->execute($this->Data);
            $this->Result = $this->Conn->lastInsertId();
        } catch (PDOException $e) {
            $this->Result = NULL;
            LPError('<b>Erro ao cadastrar: </b>' . $e->getMessage(), $e->getCode());
        }
    }

}
