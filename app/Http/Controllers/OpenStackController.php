<?php


namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Guzzle\Http\Message\Response;
use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;
use Psy\Util\Json;

class OpenStackController extends Controller
{
    private $ip = '46.101.65.213';

    public function makeClient()
    {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://' . $this->ip . '/identity/',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);
    }
    public function makeClientNova()
    {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://' . $this->ip . '/compute/v2.1/',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);
        $client = $this->makeClient();
        $response = $client->request(
            'POST',
            'v3/auth/tokens',
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' =>
                '{
                    "auth": {
                        "identity": {
                            "methods": [
                                "password"
                            ],
                            "password": {
                                "user": {
                                    "name":"' . $data['name'] . '",
                                    "domain": {
                                        "name": "Default"
                                    },
                                    "password":"' . $data['password'] . '"
                                }
                            }
                        }
                    }
                }'
            ]
        );
        $token = $response->getHeaders()['X-Subject-Token'][0];
        return $token;
    }


    public function getVolumes(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ]);

        $client = $this->makeClientNova();

        $response = $client->request(
            'GET',
            'os-volumes',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . ''
                ],
            ]
        );
        $volumes = json_decode($response->getBody()->getContents());
        return response()->json($volumes);
    }
}
