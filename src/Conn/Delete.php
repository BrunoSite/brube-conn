<?php
/**
 * <b>Delete</b> [ Conn ]
 * Classe responsável por deletar genéricamente informações no banco de dados
 * @package brube/system
 * @author Bruno Moura <contato@brunosite.com>
 * @copyright (c) 2017, MIT
 * @license http://brunosite.com/package/brube-system
 */

namespace Conn;

class Delete extends Conn {

    private $Table;
    private $Terms;
    private $Links;
    private $Result;

    /** @var PDOstatements */
    private $Delete;

    /** @var PDO */
    private $Conn;

    /**
     * <b>run:</b> Deletar o armazenamento de informações no banco de dados usando prepared statements
     * @param STRING $Table Tabela para qual a deleção deve ser concluida
     * @param array $Terms Termos das informações a serem deletadas
     */
    public function run($Table, $Terms, $ParceString) {
        $this->Table = (string) $Table;
        $this->Terms = (string) $Terms;
        parse_str($ParceString, $this->Links);

        $this->buildString();
        $this->execute();
    }

    /**
     * Retorna um FALSE ou o ID do último elemento cadastradono banco de dados
     */
    public function result() {
        return $this->Result;
    }

    /**
     * Retorna quantos dados foram deletados
     * @return INT
     */
    public function countDeleted() {
        return $this->Delete->rowCount();
    }

    /**
     * Reescreve uma parsestring e executa run()
     * @param type $ParceString
     */
    public function parce($ParceString) {
        parse_str($ParceString, $this->Links);
        $this->buildString();
        $this->execute();
    }

    /*     * **********************************************
     * *************** MÉTODOS PRIVADOS **************
     * *********************************************** */

    private function connPDO() {
        $this->Conn = parent::getConn();
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    private function buildString() {
        $this->Delete = "DELETE FROM {$this->Table} {$this->Terms}";
    }

    private function execute() {
        $this->connPDO();
        try {
            $this->Delete->execute($this->Links);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = NULL;
            LPError('<b>Erro ao Deletar conteúdo: </b>' . $e->getMessage(), $e->getCode());
            die;
        }
    }

}
