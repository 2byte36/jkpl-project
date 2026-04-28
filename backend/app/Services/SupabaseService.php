<?php

namespace App\Services;

use GuzzleHttp\Client;

class SupabaseService
{
    protected $client;
    protected $url;
    protected $key;
    protected $bucket;

    public function __construct()
    {
        $this->url = env('SUPABASE_URL');
        $this->key = env('SUPABASE_KEY');
        $this->bucket = env('SUPABASE_BUCKET');

        $this->client = new Client([
            'base_uri' => $this->url . '/storage/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->key,
                'apikey' => $this->key,
            ],
        ]);
    }

    public function upload($file, $path)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $path . '/' . $fileName;

        $response = $this->client->request('POST', "object/$this->bucket/$filePath", [
            'headers' => [
                'Content-Type' => $file->getMimeType(),
            ],
            'body' => file_get_contents($file->getRealPath()),
        ]);

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            throw new \Exception('Upload failed');
        }

        return $this->url . "/storage/v1/object/public/$this->bucket/$filePath";
    }
}
