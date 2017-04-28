<?php
/**
 * <b>Delete</b> [ Conn ]
 * Classe responsável por deletar genéricamente informações no banco de dados
 * @package brube/system
 * @author Bruno Moura <contato@brunoiste.com>
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
     * <b>RunDelete:</b> Deletar o armazenamento de informações no banco de dados usando prepared statements
     * @param STRING $Table Tabela para qual a deleção deve ser concluida
     * @param array $Terms Termos das informações a serem deletadas
     */
    public function RunDelete($Table, $Terms, $ParceString) {
        $this->Table = (string) $Table;
        $this->Terms = (string) $Terms;
        parse_str($ParceString, $this->Links);

        $this->MontarString();
        $this->Execute();
    }

    /**
     * Retorna um FALSE ou o ID do último elemento cadastradono banco de dados
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * Retorna quantos dados foram deletados
     * @return INT
     */
    public function getRowCount() {
        return $this->Delete->rowCount();
    }

    /**
     * Reescreve uma parsestring e executa RunDelete()
     * @param type $ParceString
     */
    public function getParce($ParceString) {
        parse_str($ParceString, $this->Links);
        $this->MontarString();
        $this->Execute();
    }

    /*     * **********************************************
     * *************** MÉTODOS PRIVADOS **************
     * *********************************************** */

    private function ConectarPDO() {
        $this->Conn = parent::getConn();
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    private function MontarString() {
        $this->Delete = "DELETE FROM {$this->Table} {$this->Terms}";
    }

    private function Execute() {
        $this->ConectarPDO();
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
