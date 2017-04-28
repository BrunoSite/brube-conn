<?php
/**
 * Conn [ CONEXÃO ]
 * Classe abstrata de conexão usando padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * @package brube/system
 * @author Bruno Moura <contato@brunoiste.com>
 * @copyright (c) 2017, MIT
 * @license http://brunosite.com/package/brube-system
 */

namespace Conn;

use PDO;
use PDOException;

class Conn {

    private static $Host = _HOST_;
    private static $User = _USER_;
    private static $Pass = _PASS_;
    private static $Dbsa = _DBSA_;

    /** @var PDO */
    private static $Connect = NULL;

    /**
     * Realiza a conexão com a base de dados usando o modelo PDO SingleTon Pattern
     * Retorna um objeto PDO singleTon Pattern
     */
    private static function Conectar() {
        try {
            if (self::$Connect == NULL):
                $dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbsa;
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
            endif;
        } catch (PDOException $e) {
            PHPError($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            die;
        }

        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }

    /**
     * Retorna um objeto PDO SingleTon Pattern
     */
    public static function getConn() {
        return self::Conectar();
    }

}
