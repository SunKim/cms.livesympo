<?php

/**
 * EncryptHelper.php
 * 
 * 프로젝트 ID 암호화/복호화 헬퍼
 * 
 * @package    App
 * @subpackage Helpers
 * @author     SUN
 * @copyright  Livesympo
 */

namespace App\Helpers;

class EncryptHelper {
    
    private static $method = 'AES-128-CBC';
    private static $key = null;
    private static $iv = null;
    
    /**
     * 초기화 - 환경변수에서 키와 IV 설정
     */
    private static function init() {
        if (self::$key === null) {
            // 환경변수에서 키 가져오기 (없으면 기본값 사용)
            $envKey = $_ENV['app.encryptionKey'] ?? 'livesympo2024key';
            self::$key = substr(hash('sha256', $envKey), 0, 16); // AES-128용 16바이트
            self::$iv = substr(hash('md5', $envKey), 0, 16); // 16바이트 IV
        }
    }
    
    /**
     * 프로젝트 시퀀스 암호화
     * 
     * @param int $prjSeq 프로젝트 시퀀스
     * @return string 암호화된 문자열 (URL safe)
     */
    public static function encryptPrjSeq($prjSeq) {
        self::init();
        
        // 숫자를 문자열로 변환하고 패딩 추가
        $data = str_pad($prjSeq, 10, '0', STR_PAD_LEFT);
        
        // 암호화
        $encrypted = openssl_encrypt($data, self::$method, self::$key, 0, self::$iv);
        
        // URL safe base64 인코딩
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }
    
    /**
     * 프로젝트 시퀀스 복호화
     * 
     * @param string $encryptedData 암호화된 문자열
     * @return int|false 복호화된 프로젝트 시퀀스 또는 false
     */
    public static function decryptPrjSeq($encryptedData) {
        self::init();
        
        try {
            // URL safe base64 디코딩
            $padding = 4 - (strlen($encryptedData) % 4);
            if ($padding < 4) {
                $encryptedData .= str_repeat('=', $padding);
            }
            $encrypted = base64_decode(strtr($encryptedData, '-_', '+/'));
            
            if ($encrypted === false) {
                return false;
            }
            
            // 복호화
            $decrypted = openssl_decrypt($encrypted, self::$method, self::$key, 0, self::$iv);
            
            if ($decrypted === false) {
                return false;
            }
            
            // 패딩 제거하고 정수로 변환
            $prjSeq = (int) ltrim($decrypted, '0');
            
            // 유효한 양수인지 확인
            return ($prjSeq > 0) ? $prjSeq : false;
            
        } catch (Exception $e) {
            log_message('error', 'EncryptHelper::decryptPrjSeq - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 암호화된 데이터가 유효한지 검증
     * 
     * @param string $encryptedData 암호화된 문자열
     * @return bool 유효성 여부
     */
    public static function isValidEncryptedData($encryptedData) {
        return self::decryptPrjSeq($encryptedData) !== false;
    }
}
