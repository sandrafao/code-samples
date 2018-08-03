<?php
/**
 * File contains Interface AuthenticationOptionsInterface.php
 */

namespace Samples\HttpClient\Authentication;

interface AuthenticationOptionsInterface
{
    /**
     * @return string
     */
    public function getIssuer();

    /**
     * @return string
     */
    public function getSecretKey();

    /**
     * @return int
     */
    public function getTokenTtl();
}