<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Chirps {
    private $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => config('services.chirps.url'),
            'timeout'  => 2.0,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]]
        );
    }

    public function register($name, $email, $password, $passwordConfirmation) {
        try {
            $response = $this->client->post('register', [
                'json' => [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'password_confirmation' => $passwordConfirmation,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    public function login($email, $password) {
        try {
            $response = $this->client->post('login', [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    public function logout($token) {
        try {
            $response = $this->client->post('logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    public function getChirps($token) {
        try {
            $response = $this->client->get('chirps', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    public function postChirp($token, $content) {
        try {
            $response = $this->client->post('chirps', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => [
                    'content' => $content,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    public function deleteChirp($token, $id) {
        try {
            $response = $this->client->delete('chirps/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }
}
