<?php


namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Guzzle\Http\Message\Response;
use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;
use Psy\Util\Json;

class OpenStackController extends Controller
{
    private $ip = '192.168.56.99';
    public function makeClientBlockStorage($projectId)
    {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://' . $this->ip . '/volume/v3/' . $projectId . '/',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);
    }

    public function makeClientLogin()
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
        $client = $this->makeClientLogin();
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
        $token = '';

        if ($response->GetStatusCode() == 201) {
            $token = $response->getHeaders()['X-Subject-Token'][0];
        } else {
            return ($response->error_get_last());
        }

        $response = $client->request(
            'GET',
            'v3/auth/projects',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $token . ''
                ],
            ]
        );
        $projects = json_decode($response->getBody()->getContents());
        return response()->json($projects->projects);
    }

    public function loginWithScope(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'password' => 'required',
            'projectId' => 'required'
        ]);
        $client = $this->makeClientLogin();
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
                                "id": "' . $data['projectId'] . '"
                            }
                        }
                    }
                }'
            ]
        );

        if ($response->GetStatusCode() == 201) {
            return $response->getHeaders()['X-Subject-Token'][0];
        } else {
            return ($response->error_get_last());
        }
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

    public function createFlavor(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'ram' => 'required',
            'disk' => 'required',
            'vcpus' => 'required',
            'token' => 'required',
            'projectId' => 'required'
        ]);
        $newClientLogin = $this->makeClientLogin();
        $response = $newClientLogin->request(
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
                                "token"
                            ],
                            "token": {
                                "id": "'.$data['token'].'"
                            }
                        },
                        "scope": {
                            "system": {
                                "all": true
                            }
                        }
                    }
                }'
            ]
        );
        $token = '';

        if ($response->GetStatusCode() == 201) {
            $token = $response->getHeaders()['X-Subject-Token'][0];
        } else {
            return ($response->error_get_last());
        }
        $client = $this->makeClientNova();
        $response = $client->request(
            'POST',
            'flavors',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $token . '',
                    'Content-Type' => 'application/json'
                ],
                'body' =>
                    '{
                        "flavor": {
                            "name": "'.$data['name'].'",
                            "ram": '.$data['ram'].',
                            "vcpus": '.$data['vcpus'].',
                            "disk": '.$data['disk'].',
                            "rxtx_factor": 2.0
                        }
                    }'
            ]
        );
        $flavorCriado='';
        if ($response->GetStatusCode() == 200) {
            $flavorCriado = json_decode($response->getBody()->getContents());
        } else {
            return ($response->error_get_last());
        }
        // $newClientLogin->request(
        //     'POST',
        //     'v3/auth/tokens',
        //     [
        //         'headers' => [
        //             'Content-Type' => 'application/json'
        //         ],
        //         'body' =>
        //         '{
        //             "auth": {
        //                 "identity": {
        //                     "methods": [
        //                         "token"
        //                     ],
        //                     "token": {
        //                         "id": "'.$data['token'].'"
        //                     }
        //                 },
        //                 "scope": {
        //                     "project": {
        //                         "id": "'.$data['projectId'].'"
        //                     }
        //                 }
        //             }
        //         }'
        //     ]
        // );
        return response()->json($flavorCriado);
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

    public function createVolume(Request $request)
    {

        $data = $request->validate([
            'token' => 'required',
            'projectId' => 'required',
        ]);
        $client = $this->makeClientBlockStorage($data['projectId']);

        $response = $client->request(
            'POST',
            'volumes',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . '',
                    'Content-Type' => 'application/json'
                ],
                'body' =>
                '{
                    "volume": {
                        "size": 10,




                        "name": "conas"



                    }
                }'

            ]
        );
        $intances = json_decode($response->getBody()->getContents());
        dd($intances);
    }
}
