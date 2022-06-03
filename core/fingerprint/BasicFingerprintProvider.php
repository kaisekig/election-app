<?php
    namespace App\Core\Fingerprint;

    class BasicFingerprintProvider implements FingerprintProvider {
        private array $source;

        public function __construct(array $source)
        {
            $this->source = $source;
        }

        public function fingerprint(): string {
            $ip = filter_var($this->source["REMOTE_ADDR"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $ua = filter_var($this->source["HTTP_USER_AGENT"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $hash = hash("sha512", "{$ip}.{$ua}");
            
            return hash("sha512", $hash);
        }
    }
?>