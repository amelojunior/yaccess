<?php
namespace Evoxlab\Pagbank\Plugin\Model\Api;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface;
use Laminas\Http\ClientFactory;
use Laminas\Http\Request;
use PagBank\PaymentMagento\Gateway\Config\Config as ConfigBase;
use PagBank\PaymentMagento\Model\Api\Credential as Subject;

class CredentialPlugin
{
    private $backendUrl;
    private $configBase;
    private $storeManager;
    private $httpClientFactory;
    private $json;

    public function __construct(
        UrlInterface $backendUrl,
        ConfigBase $configBase,
        StoreManagerInterface $storeManager,
        ClientFactory $httpClientFactory,
        Json $json
    ) {
        $this->backendUrl = $backendUrl;
        $this->configBase = $configBase;
        $this->storeManager = $storeManager;
        $this->httpClientFactory = $httpClientFactory;
        $this->json = $json;
    }

    /**
     * Substitui o comportamento do método getAuthorize apenas para corrigir o redirect_uri.
     */
    public function aroundGetAuthorize(Subject $subject, callable $proceed, $storeId, $code, $codeVerifier)
    {
        $url = $this->configBase->getApiUrl($storeId);
        $headers = $this->configBase->getPubHeader($storeId);
        $apiConfigs = $this->configBase->getApiConfigs();
        $uri = $url . 'oauth2/token';

        // Usa UrlInterface para garantir que a URL não duplique "/admin"
        $redirectUrl = $this->backendUrl->getUrl(
            'pagbank/system_config/oauth',
            [
                'website'       => $storeId,
                'code_verifier' => $codeVerifier,
                '_secure'       => true,
            ]
        );

        // Evita duplicação do storeCode (admin)
        $store = $this->storeManager->getStore('admin');
        $storeCode = '/' . $store->getCode() . '/';
        $search = '/' . preg_quote($storeCode, '/') . '/';
        $redirectUrl = preg_replace($search, '/', $redirectUrl, 0);

        // Monta o corpo da requisição
        $data = [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $redirectUrl,
            'code_verifier' => $codeVerifier,
        ];

        /** @var \Laminas\Http\Client $client */
        $client = $this->httpClientFactory->create();
        $client->setUri($uri);
        $client->setHeaders($headers);
        $client->setMethod(Request::METHOD_POST);
        $client->setOptions($apiConfigs);
        $client->setRawBody($this->json->serialize($data));

        $send = $client->send();
        return $send->getBody();
    }
}
