# Integration with Chirp API

Install Guzzle:

```bash
composer require guzzlehttp/guzzle
```

Setup `config/services.php` for Chirps API:

```php
'chirps' => [
    'url' => env('CHRIP_URL'),
]
```

Then in `.env`, add `CHIRP_URL`:

```plaintext
CHRIP_URL="http://127.0.0.1:8000/api/"
```

Then in `app/` directory create `Services` directory and add `app/Services/Chirps.php` file:

```php
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
```

Now, to simulate the usage, create a `DevChirpsSeeder`:

```bash
php artisan make:seeder DevChirpSeeder
```

Then update the seeder:

```php
<?php

namespace Database\Seeders;

use App\Services\Chirps;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevChirpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(app()->isProduction()) {
            return;
        }
        $apiClient = new Chirps();

        for ($i=0; $i < rand(5, 10); $i++) {
            $registerResponse = $apiClient->register(fake()->name, fake()->unique()->safeEmail(), 'password', 'password');
            print_r($registerResponse);
        }
    }
}
```

Then in terminal, run:

```bash
php artisan db:seed --class=DevChirpSeeder
```

You should have the following output in your terminal:

```bash
Array
(
    [access_token] => 33|VKlpRUQB7Ke1RqjS4Oe2lr1oUzgKH9yhE4cL3kFB8790d2f5
    [token_type] => Bearer
)
Array
(
    [access_token] => 34|9npaEp7DizUglDtvvFLu7g0stKqs4oxZfVOY8fBu5c6f47b1
    [token_type] => Bearer
)
Array
(
    [access_token] => 35|c3ZWx7D0Pqv3dCpJts98NxMBCVJx4iY57FQNLqRC9687c12b
    [token_type] => Bearer
)
Array
(
    [access_token] => 36|PULiLVTH8Fgxugzhc9nOMa5kK4eIk2twhIj5xmgPaa262934
    [token_type] => Bearer
)
```
