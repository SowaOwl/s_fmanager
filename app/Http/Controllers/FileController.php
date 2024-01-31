<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetFileRequest;
use App\Http\Requests\StoreFileBase64Request;
use App\Http\Requests\StoreFileRequest;
use App\Services\FileService;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(
        private readonly FileService $fileService,
    )
    {
    }

    /**
     * @param StoreFileRequest $request
     * @return JsonResponse
     * @throws ApiError
     */
    public function storeFile(StoreFileRequest $request): JsonResponse
    {
        $response = $this->fileService->storeFile($request->file('file'), $request->folder);

        if($response['success']){
            return $this->SendSuccessResponse(trans('file.store.success'), $response['document']);
        }else{
            return $this->SendErrorResponse(trans('file.store.error'), $response['error']);
        }
    }

    /**
     * @param StoreFileBase64Request $request
     * @return JsonResponse
     * @throws ApiError
     */
    public function storeFileBase64(StoreFileBase64Request $request): JsonResponse
    {
        $response = $this->fileService->storeFileBase64($request->file, $request->folder, $request->type);

        if($response['success']){
            return $this->SendSuccessResponse(trans('file.store.success'), $response['document']);
        }else{
            return $this->SendErrorResponse(trans('file.store.error'), $response['error']);
        }
    }

    /**
     * @param GetFileRequest $request
     * @return JsonResponse
     */
    public function getFile(GetFileRequest $request): JsonResponse
    {
        $response = $this->fileService->getFile($request->path);

        if($response['success']){
            return $this->SendSuccessResponse(trans('file.get.success'), $response['file']);
        }else{
            return $this->SendErrorResponse(trans('file.get.not_found'), $response['error']);
        }
    }
}
