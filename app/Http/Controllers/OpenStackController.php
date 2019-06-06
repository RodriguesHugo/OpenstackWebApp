<?php


namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Input;
// use Illuminate\Support\Facades\File;
// use Illuminate\Http\UploadedFile;
use GuzzleHttp\Client;
// use Guzzle\Http\Message\Response;
use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;
// use GuzzleHttp\RequestOptions;
// use Psy\Util\Json;

class OpenStackController extends Controller
{
    private $ip = '192.168.56.99';

    public function makeClientNetwork()
    {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://' . $this->ip . ':9696/',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);
    }
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
    public function makeClientCompute()
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
            'base_uri' => 'http://' . $this->ip . '/image/',
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
        $client = $this->makeClientCompute();

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
            'v2/images',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . ''
                ],
            ]
        );
        $intances = json_decode($response->getBody()->getContents());
        return response()->json($intances);
    }
    public function createImage(Request $request)
    {


        return response()->json($image);
    }

    public function getFlavors(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ]);
        $client = $this->makeClientCompute();
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
                                "id": "' . $data['token'] . '"
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
        $client = $this->makeClientCompute();
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
                            "name": "' . $data['name'] . '",
                            "ram": ' . $data['ram'] . ',
                            "vcpus": ' . $data['vcpus'] . ',
                            "disk": ' . $data['disk'] . ',
                            "rxtx_factor": 2.0
                        }
                    }'
            ]
        );
        $flavorCriado = '';
        if ($response->GetStatusCode() == 200) {
            $flavorCriado = json_decode($response->getBody()->getContents());
        } else {
            return ($response->error_get_last());
        }

        return response()->json($flavorCriado);
    }

    public function getInstances(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ]);
        $client = $this->makeClientCompute();

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
            'volumeName' => 'required',
            'volumeSize' => 'required|integer',
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
                        "size": ' . $request->volumeSize . ',




                        "name": "' . $request->volumeName . '"



                    }
                }'

            ]
        );
        $volume = json_decode($response->getBody()->getContents());
        return response()->json($volume);
    }

    public function deleteVolume(Request $request)
    {

        $data = $request->validate([
            'token' => 'required',
            'projectId' => 'required',
            'volumeId' => 'required',
        ]);
        $client = $this->makeClientBlockStorage($data['projectId']);

        $response = $client->request(
            'Delete',
            'volumes/' . $request->volumeId . '',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . '',

                ]
            ]
        );

        return response()->json("Volume Deleted");
    }
    public function postImage(Request $request)
    {


        $data = $request->validate([
            'token' => 'required',
        ]);
        $request->file('file')->storeAs('', $request->imageName . '.iso');
        $client = $this->makeClientImage();
        $response = $client->request(
            'POST',
            'v2/images',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . '',
                    'Content-Type' => 'application/json'
                ],
                'body' =>
                '{

                "container_format": "bare",
                "disk_format": "iso",
                "name": "' . $request->imageName . '",
                "min_disk": 1
                }'
            ]
        );
        $imageCreated = json_decode($response->getBody()->getContents());
        $response = $client->request(
            'PUT',
            'v2/images/' . $imageCreated->id . '/file',
            [
                'timeout' => 10,
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . '',
                    'Content-Type' => 'application/octet-stream'
                ],
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen('/home/vagrant/ProjetoLTI/fase2/storage/app/' . $request->imageName . '.iso', 'r')
                    ]

                ]
            ]
        );
    }

    public function deleteFlavor(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
            'flavorId' => 'required',
        ]);
        $client = $this->makeClientCompute();

        $response = $client->request(
            'Delete',
            'flavors/' . $data['flavorId'] . '',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . '',

                ]
            ]
        );

        return response()->json("Flavor Deleted");
    }

    public function createInstance(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
            'name' => 'required',
            'imageId' => 'required',
            'flavorId' => 'required',
            'networkId' => 'required'
        ]);
        $client = $this->makeClientCompute();

        $response = $client->request(
            'POST',
            'servers',
            [
                'timeout' => 10,
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . '',

                ],
                'body' => '{
                    "server": {
                        "name": "' . $data['name'] . '",
                        "imageRef": "' . $data['imageId'] . '",
                        "flavorRef": "' . $data['flavorId'] . '",
                        "networks": [{
                            "uuid" : "' . $data['networkId'] . '"
                        }]
                    }
                }'
            ]
        );

        if ($response->getStatusCode() === '202') {
            return response()->json("Instace created successfully");
        }
        return response()->json("Error creating instance");
    }

    public function getNetworks(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ]);
        $client = $this->makeClientNetwork();

        $response = $client->request(
            'GET',
            '/v2.0/networks',
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . ''
                ],
            ]
        );
        $networks = json_decode($response->getBody()->getContents());
        return response()->json($networks);
    }
    public function changeProject(Request $request)
    {
        $data = $request->validate([
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
                                "id": "' . $data['token'] . '"
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

    public function deleteInstance(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
            'instanceId' => 'required'
        ]);

        $client = $this->makeClientCompute();
        $response = $client->request(
            'Delete',
            'servers/' . $data['instanceId'],
            [
                'headers' => [
                    'X-Auth-Token' => '' . $data['token'] . ''
                ]
            ]
        );

        if ($response->GetStatusCode() == 204) {
            // return response()->json("Instace deleted successfully");
            return response()->json("Instance created successfully");
        } elseif ($response->GetStatusCode() == 409) {
            return response()->json(["message" => "Cannot delete instance because state is locked"], 500);
        } else {
            return response()->json(["message" => "Error creating instance"], 500);
        }
    }
}
