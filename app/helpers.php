<?php
if (!function_exists('formatCnpj')) {
    function formatCnpj($cnpj)
    {
        return preg_replace(
            "/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/",
            "\$1.\$2.\$3/\$4-\$5",
            $cnpj
        );
    }
}