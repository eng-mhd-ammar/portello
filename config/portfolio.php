<?php

$appUrl = (string) env('APP_URL', 'http://localhost');

return [

    /**
     * Base domain for portfolio subdomains (slug.BASE_DOMAIN).
     */
    'base_domain' => env('PORTFOLIO_BASE_DOMAIN', parse_url($appUrl, PHP_URL_HOST) ?: 'portfolio.test'),

    /**
     * Hosts that serve the central SaaS app (no tenant).
     */
    'central_hosts' => array_values(array_unique(array_filter(array_map(
        static fn (string $host): string => strtolower(trim($host)),
        explode(',', (string) env(
            'PORTFOLIO_CENTRAL_HOSTS',
            'localhost,127.0.0.1,'.(parse_url($appUrl, PHP_URL_HOST) ?: '')
        ))
    )))),

    'reserved_subdomains' => ['www', 'app', 'api', 'admin', 'mail', 'ftp'],

];
