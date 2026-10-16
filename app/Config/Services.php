<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function authService($getShared = true) { return $getShared ? static::getSharedInstance('authService') : new \App\Services\AuthService(); }
    public static function auditLogger($getShared = true) { return $getShared ? static::getSharedInstance('auditLogger') : new \App\Services\AuditLogger(); }
    public static function securityRateLimiter($getShared = true) { return $getShared ? static::getSharedInstance('securityRateLimiter') : new \App\Services\SecurityRateLimiter(); }
    public static function accessPolicy($getShared = true) { return $getShared ? static::getSharedInstance('accessPolicy') : new \App\Services\AccessPolicy(); }
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */
}
