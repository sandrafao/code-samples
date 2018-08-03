<?php
/**
 * File contains Class AuthenticationOptions.php
 *
 * @since  22.08.16
 * @author Alexandra Fedotova <alexandra.fedotova@veeam.com>
 */

namespace Samples\HttpClient\Authentication;

use Zend\Stdlib\AbstractOptions;

class AuthenticationOptions extends AbstractOptions implements AuthenticationOptionsInterface
{
    /**
     * @var string
     */
    private $issuer;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var int
     */
    private $tokenTtl;

    /**
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * @param string $issuer
     *
     * @return $this
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     *
     * @return $this
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @return int
     */
    public function getTokenTtl()
    {
        return $this->tokenTtl;
    }

    /**
     * @param int $tokenTtl
     *
     * @return $this
     */
    public function setTokenTtl($tokenTtl)
    {
        $this->tokenTtl = $tokenTtl;
        return $this;
    }

}