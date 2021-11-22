<?php

namespace LibreTranslateLaravel\LibreTranslateAPI;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class LibreTranslate
{
    protected static $client;
    protected $server;
    protected $api_key;
    protected $source_lang;
    protected $target_lang;
    protected $mode;
    protected $logger;
    protected $promises;

    public function __construct($server, $api_key, $source_lang, $target_lang, $mode)
    {
        $this->server = $server;
        $this->api_key = $api_key;
        $this->source_lang = $source_lang;
        $this->target_lang = $target_lang;
        $this->mode = $mode;
        $this->promises = [];
    }

    protected function getClient()
    {
        if (!self::$client) {
            self::$client = new Client(['base_uri' => $this->server, 'timeout' => 10.0]);
        }
        return self::$client;
    }

    protected function getRequest($word, $isAsync = false, $callback = null)
    {
        return [
            'headers' => [
                'accept' => 'application/json',
            ],
            'form_params' => [
                'q' => $word,
                'source' => $this->source_lang,
                'target' => $this->target_lang,
                'format' => 'text',
                'api_key' => $this->api_key,
            ],
            'on_stats' => function (TransferStats $stats) use ($isAsync, $callback) {
                if ($isAsync) {
                    if ($stats->hasResponse()) {
                        if ($callback && is_callable($callback)) {
                            $translated = $this->processResponse($stats->getResponse());
                            $callback($translated);
                        }
                    }
                }
            }
        ];
    }

    protected function getResponse($word, $isAsync, $callback)
    {
        if ($isAsync) {
            return $this->getClient()->requestAsync('POST', '/translate', $this->getRequest($word, $isAsync, $callback));
        } else {
            return $this->getClient()->request('POST', '/translate', $this->getRequest($word, $isAsync, $callback));
        }
    }

    protected function processResponse($response)
    {
        if ($response->getStatusCode() == 200) {
            $json = json_decode($response->getBody());
            return $json->translatedText ?? null;
        } else {
            throw new LibreTranslateAPIException('Error making request: ' . $response->getReasonPhrase());
        }
    }

    public function translate($word)
    {
        try {
            return $this->processResponse($this->getResponse($word, false, null));
        } catch (Exception $ex) {
            if ($logger = $this->logger) {
                if (is_callable($logger)) {
                    $logger($ex->getMessage());
                }
            }

            return null;

            if ($this->mode !== 'silent') {
                throw $ex;
            }
        }
    }

    // public function translateAsync($word, $callback)
    // {
    //     try {
    //         $this->getResponse($word, true, $callback);
    //     } catch (Exception $ex) {
    //         if ($logger = $this->logger) {
    //             if (is_callable($logger)) {
    //                 $logger($ex->getMessage());
    //             }
    //         }

    //         return null;

    //         if ($this->mode !== 'silent') {
    //             throw $ex;
    //         }
    //     }
    // }

    public function setLogger(Closure $logFunction)
    {
        $this->logger = $logFunction;
    }
}
