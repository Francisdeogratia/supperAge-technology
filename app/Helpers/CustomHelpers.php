<?php

if (!function_exists('generateSpecialcode')) {
    function generateSpecialcode() {
        return strtoupper(bin2hex(random_bytes(4))); // 8-character secure code
    }
}
