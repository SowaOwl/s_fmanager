<?php

namespace App\Services;

use App\Repositories\CloudinaryRepository;
use App\Repositories\Eloquent\DocumentRepository;
use Cloudinary\Api\Exception\ApiError;

class FileService
{
    private readonly string $cloudinaryUrl;
    public function __construct(
        private readonly CloudinaryRepository $cloudinaryRepository,
        private readonly DocumentRepository $documentRepository,
    )
    {
        $this->cloudinaryUrl = config('services.cloudinary.download_url');
    }

    /**
     * @param $file
     * @param string $folder
     * @return mixed
     * @throws ApiError
     */
    public function storeFile($file,string $folder): mixed
    {
        $user = auth()->user();
        $fileData = $this->cloudinaryRepository->storeFile($file, $folder);

        return $this->documentRepository->setDocument($user, $fileData);
    }

    /**
     * @param string $file
     * @param string $folder
     * @param string $type
     * @return array
     * @throws ApiError
     */
    public function storeFileBase64(string $file, string $folder, string $type): array
    {
        $user = auth()->user();
        $fileData = $this->cloudinaryRepository->storeFileBase64($file, $folder, $type);

        return $this->documentRepository->setDocument($user, $fileData);
    }

    /**
     * @param string $path
     * @return array
     */
    public function getFile(string $path): array
    {
        $downloadUrl = $this->cloudinaryUrl . $path;
        try {
            $file = base64_encode(file_get_contents($downloadUrl));
            return [
                'success' => true,
                'file' => $file,
            ];
        }catch (\ErrorException $exception){
            return [
                'success' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
