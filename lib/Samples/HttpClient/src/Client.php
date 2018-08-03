<?php
/**
 * File contains Class Client.php
 *
 * @since  18.08.16
 * @author Alexandra Fedotova <alexandra.fedotova@veeam.com>
 */

namespace Samples\HttpClient;

use Samples\HttpClient\Exception\SampleClientBadResponseException;
use Samples\HttpClient\Exception\SampleClientConnectException;
use Samples\HttpClient\Exception\SampleClientException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Uri;
use Zend\Json\Json;

class Client
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var Uri
     */
    protected $uri;

    /**
     * Determine do we need return Json::decoded response or plain
     *
     * @var bool
     */
    protected $plainResponse = false;

    /**
     * Client constructor.
     *
     * @param HttpClient $client
     * @param string     $version
     */
    public function __construct(HttpClient $client, $version)
    {
        $this->client  = $client;
        $this->version = $version;
    }

    /**
     * @return bool
     */
    public function isPlainResponse()
    {
        return $this->plainResponse;
    }

    /**
     * @param bool $plainResponse
     *
     * @return $this
     */
    public function setPlainResponse($plainResponse)
    {
        $this->plainResponse = (bool)$plainResponse;
        return $this;
    }

    /**
     * @return Uri
     */
    public function getUri()
    {
        if (!isset($this->uri)) {
            $this->uri = new Uri((string)$this->client->getConfig()['base_uri']);
        }
        return $this->uri;
    }

    /**
     * @param string $endpoint
     * @param array  $params
     * @param array  $headers
     *
     * @param array  $requestOptions
     *
     * @return array|\stdClass
     */
    public function get($endpoint, array $params = [], array $headers = [], array $requestOptions = [])
    {
        return $this->doRequest('GET', $endpoint, array_merge(['query' => $params], $requestOptions), $headers);
    }

    /**
     * @param string       $endpoint
     * @param array|string $params
     * @param array        $headers
     *
     * @return \stdClass
     */
    public function post($endpoint, $params, array $headers = [])
    {
        return $this->doRequest('POST', $endpoint, ['body' => $this->prepareRequestBody($params)], $headers);
    }

    /**
     * @param string       $endpoint
     * @param array|string $params
     * @param array        $headers
     *
     * @return \stdClass
     */
    public function put($endpoint, $params, array $headers = [])
    {
        return $this->doRequest('PUT', $endpoint, ['body' => $this->prepareRequestBody($params)], $headers);
    }

    /**
     * @param string       $endpoint
     * @param array|string $params
     * @param array        $headers
     *
     * @return \stdClass
     */
    public function patch($endpoint, $params, array $headers = [])
    {
        return $this->doRequest('PATCH', $endpoint, ['body' => $this->prepareRequestBody($params)], $headers);
    }

    /**
     * @param string $endpoint
     * @param array  $params
     * @param array  $headers
     *
     * @return \stdClass
     */
    public function delete($endpoint, array $params = [], array $headers = [])
    {
        return $this->doRequest('DELETE', $endpoint, ['query' => $params], $headers);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $options
     * @param array  $headers
     *
     * @return array|\stdClass|string
     * @throws SampleClientBadResponseException
     * @throws SampleClientConnectException
     * @throws SampleClientException
     */
    protected function doRequest($method, $endpoint, array $options = [], array $headers)
    {
        $options['headers'] = $headers;

        try {
            $response = $this->client->request($method, $this->prepareEndpoint($endpoint), $options);

            return true === $this->plainResponse ? (string)$response->getBody() : Json::decode($response->getBody());
        } catch (BadResponseException $exception) {
            throw new SampleClientBadResponseException('Bad response received from API', 0, $exception);
        } catch (ConnectException $exception) {
            throw new SampleClientConnectException('Error occurred connecting to remote', 0, $exception);
        } catch (\Exception $exception) {
            throw new SampleClientException('Error occurred sending request', 0, $exception);
        }
    }

    /**
     * @param string $endpoint
     *
     * @return string
     */
    private function prepareEndpoint($endpoint)
    {
        return sprintf('%s%s', $this->version, $endpoint);
    }

    /**
     * @param array|string $params
     *
     * @return string
     */
    private function prepareRequestBody($params)
    {
        return is_array($params) ? json_encode($params) : $params;
    }
}
