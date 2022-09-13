<?php
    namespace App\Core\Fingerprint;

    interface FingerprintProvider {
        public function fingerprint(): string;
    }
?>