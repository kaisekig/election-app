<?php
    namespace App\Core\Fingerprint;

    class FactoryFingerprintProvider {
        public function __construct() {}
        
        public static function factory(array $source) {
            if ($source === $_SERVER) {
                return new BasicFingerprintProvider($source);
            }
        }
    }
?>