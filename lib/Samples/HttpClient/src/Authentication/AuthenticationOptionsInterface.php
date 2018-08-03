<?php
/**
 * File contains Interface AuthenticationOptionsInterface.php
 *
 * @since  22.08.16
 * @author Alexandra Fedotova <alexandra.fedotova@veeam.com>
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