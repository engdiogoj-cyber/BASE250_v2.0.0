<?php
/**
 * Configuração de Banco de Dados
 * Conexão segura via PDO
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 */

declare(strict_types=1);

namespace BASE250\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    
    private const HOST = 'localhost';
    private const DB = 'base250_db';
    private const USER = 'base250_user';
    private const PASS = 'ALTERAR_SENHA_AQUI';
    private const CHARSET = 'utf8mb4';
    
    /**
     * Retorna conexão PDO singleton
     * 
     * @return PDO
     * @throws \RuntimeException
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::HOST,
                self::DB,
                self::CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ];
            
            try {
                self::$instance = new PDO($dsn, self::USER, self::PASS, $options);
            } catch (PDOException $e) {
                error_log('Database connection error: ' . $e->getMessage());
                throw new \RuntimeException('Erro ao conectar ao banco de dados');
            }
        }
        
        return self::$instance;
    }
}
