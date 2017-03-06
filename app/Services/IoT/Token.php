<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 01/03/2017
 * Time: 13:41
 */

namespace App\Services\IoT;


use App\Repositories\TokenRepository;

trait Token
{
    protected $client;

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * get client bearer token
     * @param bool $force
     * @return mixed
     */
    public function getToken($force = false)
    {
        $tokenRepository = new TokenRepository();

        // if force then repository needs resetting so that save can complete
        if ($force === false) {
            $tokenRepository->setCacheLifetime(0);
        }

        // find token item
        $tokenItem = $tokenRepository->findBy('client', $this->getClient());
        if ($tokenItem) {
            if (($force !== true) && ($tokenItem->expiry > new \DateTime())) {
                return $tokenItem->access_token;
            }
        } else {
            $tokenItem = $tokenRepository->createModel();
            $tokenItem->client = $this->getClient();
        }

        $token = $this->requestToken();
        $tokenItem->access_token = $token->access_token;
        $tokenItem->expiry = (new \DateTime())->setTimestamp($token->expires_in);
        $tokenItem->save();

        return $tokenItem->access_token;
    }

    /**
     * request token
     * @return mixed
     */
    public function requestToken()
    {
        $authService = app()->make('Lightwave\AuthenticateService');
        return $authService->requestAccessToken();
    }

    /**
     * renew token (not supported by lightwave)
     * @return string
     */
    public function renewToken()
    {
        return $this->getToken();
    }
}