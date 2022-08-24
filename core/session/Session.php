<?php
    namespace App\Core\Session;

    use App\Core\Fingerprint\FingerprintProvider;

    final class Session {
        private $sessionId;
        private $sessionData;
        private $sessionLifetime;
        private $sessionStorage;
        private $fingerprintProvider;

        public function __construct(
            SessionStorage $sessionStorage,
            int $sessionLifetime = 1800
        )
        {
            $this->sessionId       = filter_input(INPUT_COOKIE, "SESSION_COOKIE", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($this->sessionId === null) {
                $this->sessionId = "";
            }
            $this->sessionId       = preg_replace("|[^A-Za-z0-9]|", "", $this->sessionId);

            $this->sessionData         = (object) [];
            $this->sessionStorage      = $sessionStorage;
            $this->sessionLifetime     = $sessionLifetime;
            $this->fingerprintProvider = (object) null; 

            if (strlen($this->sessionId) !== 32) {
                $this->sessionId = $this->generateSessionId();
                setcookie("SESSION_COOKIE", $this->sessionId, time() + $this->sessionLifetime, "/");
            }
        }

        public function set(string $key, $value) {
            $this->sessionData->$key = $value;
        }

        public function get(string $key, $default = 0) {
            return $this->sessionData->$key ?? $default;
        }

        public function exist(string $key): bool {
            return (isset($this->sessionData->$key));
        }

        public function has(string $key): bool {
            if (!$this->exist($key)) {
                return false;
            }

            return true;
        }
        
        public function delete(string $key) {
            if ($this->exist($key)) {
                unset($this->sessionData->$key);
            }
        }

        public function clear() {
            $this->sessionData = (object) [];
        }

        public function save() {
            $fingerprint = $this->fingerprintProvider->fingerprint();
            $this->sessionData->fingerprint = $fingerprint;

            $jsonData = json_encode($this->sessionData);
            $this->sessionStorage->save($this->sessionId, $jsonData);

            setcookie("SESSION_COOKIE", $this->sessionId, time() + $this->sessionLifetime, "/");
        }

        public function reload() {
            $jsonData = $this->sessionStorage->load($this->sessionId);
            $restoredData = json_decode($jsonData);

            if (!$restoredData) {
                $this->sessionData = (object) [];
                return;
            }

            $this->sessionData = $restoredData;

            if ($this->fingerprintProvider === null) {
                return;
            }

            $savedFingerprint = $this->sessionData->fingerprint ?? null;
            if ($savedFingerprint === null) {
                return;
            }

            $currentFingerprint = $this->fingerprintProvider->fingerprint();

            if ($currentFingerprint !== $savedFingerprint) {
                $this->clear();
                $this->sessionStorage->delete($this->sessionId);
                $this->sessionId = $this->generateSessionId();
                $this->save();
                setcookie("SESSION_COOKIE", $this->sessionId, time() + $this->sessionLifetime, "/");
            }
        }

        public function regenerate() {
            $this->reload();
            $this->sessionStorage->delete($this->sessionId);
            $this->sessionId = $this->generateSessionId();
            $this->save();
            setcookie("SESSION_COOKIE", $this->sessionId, time() + $this->sessionLifetime, "/");
        }

        private function generateSessionId(): string {
            $supported = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            $id = "";

            for ($i = 0; $i<32; $i++) {
                $id .= $supported[rand(0, strlen($supported)-1)];
            }

            return $id;
        }

        public function setFingerprintProvider(FingerprintProvider $fingerprint) {
            $this->fingerprintProvider = $fingerprint;
        }
    }
?>