<?php

namespace App\Repositories;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;

class CloudinaryRepository
{
    private readonly UploadApi $uploadApi;

    public function __construct()
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
                'url'        => [
                    'secure' => true]]]);

        $this->uploadApi = $cloudinary->uploadApi();
    }

    /**
     * @param $file
     * @param string $folder
     * @return array
     * @throws ApiError
     */
    public function storeFile($file, string $folder): array
    {
        $type = $file->getMimeType();
        $file = base64_encode(file_get_contents($file->getRealPath()));

        $response = $this->uploadApi->upload('data:' . $type . ';base64,' . $file, [
            'folder' => $folder
        ]);

        return $response->getArrayCopy();
    }

    /**
     * @param $file
     * @param string $folder
     * @param string $type
     * @return array
     * @throws ApiError
     */
    public function storeFileBase64($file, string $folder, string $type): array
    {
        $response = $this->uploadApi->upload('data:' . $type . ';base64,' . $file, [
            'folder' => $folder
        ]);

        return $response->getArrayCopy();
    }
}
