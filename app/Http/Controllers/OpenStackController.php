<?php


namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Guzzle\Http\Message\Response;
use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;
use Psy\Util\Json;

class OpenStackController extends Controller
{
    private $ip = '192.168.56.56';

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
    public function makeClientImage()
    {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://' . $this->ip . '/image',
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
                        },
                        "scope": {
                            "project": {
                                "domain": {
                                    "name": "Default"
                                },
                                "name": "' . $data['name'] . '"
                            }
                        }
                    }
                }'
            ]
        );
        if ($response->GetStatusCode() == 201) {
            return $response->getHeaders()['X-Subject-Token'][0];
        }
        return ($response->error_get_last());
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

    public function getImage(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ]);
        $client = $this->makeClientImage();

        $response = $client->request(
            'GET',
            '/image/v2/images',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . ''
                ],
            ]
        );
        $intances = json_decode($response->getBody()->getContents());
        return response()->json($intances);
    }

    public function getFlavors(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ]);
        $client = $this->makeClientNova();
        $response = $client->request(
            'GET',
            'flavors/detail',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . ''
                ],
            ]
        );
        $intances = json_decode($response->getBody()->getContents());
        return response()->json($intances);
    }

    public function getInstances(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ]);
        $client = $this->makeClientNova();

        $response = $client->request(
            'GET',
            'servers/detail',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . ''
                ],
            ]
        );
        $intances = json_decode($response->getBody()->getContents());
        return response()->json($intances);
    }
}
