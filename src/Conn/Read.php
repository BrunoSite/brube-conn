<?php
/**
 * <b>Read</b> [ Conn ]
 * Classe responsável pela leitura de informaões no banco de dados.
 * @package brube/system
 * @author Bruno Moura <contato@brunoiste.com>
 * @copyright (c) 2017, MIT
 * @license http://brunosite.com/package/brube-system
 */

namespace Conn;

use PDO;

class Read extends Conn {

    private $Select;
    private $Links;
    private $Result;

    /** @var PDOstatements */
    private $Read;

    /** @var PDO */
    private $Conn;

    /**
     * <b>RunLer:</b> Ler informações do banco de dados usando prepared statements
     * @param STRING $Tabela Tabela para qual a leitura seraá realizada
     * @param array $Termos Termo das informações a serem lidas
     * @param string $ParceString | null Termo das informações a serem lidas
     */
    public function RunRead($Tabela, $Termos = null, $ParceString = null) {
        if (!empty($ParceString)):
            $this->Links = $ParceString;
            parse_str($ParceString, $this->Links);
        endif;

        $this->Select = "SELECT * FROM {$Tabela} {$Termos}";
        $this->Execute();
    }

    /**
     * Retorna um FALSE ou um array dos dados resultantes
     * @return type
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * Retorna o número de resultados obtidos dentro da tabela
     * @return INT
     */
    public function getRowCount() {
        return $this->Read->rowCount();
    }

    /**
     * Define uma query mais complexa, podendo assim manipular os resultados
     * @param type $Query - A query a ser intrepretada
     * @param type $ParceString - a parsestring se necessário "elemnto1=2&elemento2=1"
     */
    public function fullQuery($Query, $ParceString = null) {
        $this->Select = $Query;
        if (!empty($ParceString)):
            $this->Links = $ParceString;
            parse_str($ParceString, $this->Links);
        endif;
        $this->Execute();
    }

    /**
     * Reescrever a parsestring e obter os resultados da getResult()
     * @param type $ParceString
     */
    public function getParce($ParceString) {
        parse_str($ParceString, $this->Links);
        $this->Execute();
    }

    /*     * **********************************************
     * *************** MÉTODOS PRIVADOS **************
     * *********************************************** */

    private function ConectarPDO() {
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($this->Select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
    }

    private function MontarString() {
        if ($this->Links):
            foreach ($this->Links as $link => $value):
                if ($link == 'limit' || $link == 'offset'):
                    $value = (int) $value;
                endif;
                $this->Read->bindValue(":{$link}", $value, (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
            endforeach;
        endif;
    }

    /**
     *
     * @return json
     */
    private function getJson() {
        return json_encode($this->Result);
    }

    private function Execute() {
        $this->ConectarPDO();
        try {
            $this->MontarString();
            $this->Read->execute();
            $this->Result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->Result = NULL;
            LPError('<b>Erro ao Ler conteúdo: </b>' . $e->getMessage(), E_USER_ERROR);
            die;
        }
    }

}
