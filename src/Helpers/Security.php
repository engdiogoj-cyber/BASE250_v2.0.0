<?php
/**
 * Helper de Segurança
 * Sanitização e validação de dados
 * 
 * @package BASE250
 * @version 2.0.0
 * @author Eng. Diogo
 */

declare(strict_types=1);

namespace BASE250\Helpers;

class Security
{
    /**
     * Sanitiza string para saída HTML
     * 
     * @param string $input String para sanitizar
     * @return string
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Sanitiza array de strings
     * 
     * @param array $data Array para sanitizar
     * @return array
     */
    public static function sanitizeArray(array $data): array
    {
        return array_map([self::class, 'sanitize'], $data);
    }
    
    /**
     * Valida email
     * 
     * @param string $email Email para validar
     * @return bool
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Valida CPF
     * 
     * @param string $cpf CPF para validar
     * @return bool
     */
    public static function validateCPF(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica sequências inválidas
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Validação de dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Gera hash seguro de senha
     * 
     * @param string $password Senha para hash
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }
    
    /**
     * Verifica senha contra hash
     * 
     * @param string $password Senha em texto plano
     * @param string $hash Hash armazenado
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
