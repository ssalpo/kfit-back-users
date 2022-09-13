<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TempFileUploadRequest;
use App\Http\Resources\TempFileResource;
use App\Services\TempFileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * @var TempFileService
     */
    private $tempFileService;

    public function __construct(TempFileService $tempFileService)
    {
        $this->tempFileService = $tempFileService;
    }

    /**
     * Returns a file to view
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Exception
     */
    public function file(string $folder, string $filename)
    {
        return $this->tempFileService->getFileToView($folder, $filename);
    }

    /**
     * Uploads a file to a temporary folder
     *
     * @param TempFileUploadRequest $request
     * @return TempFileResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Exception
     */
    public function upload(TempFileUploadRequest $request)
    {
        $result = $this->tempFileService->uploadFile($request->file('file'));

        if (is_array($result)) return TempFileResource::collection($result);

        return new TempFileResource($result);
    }
}
