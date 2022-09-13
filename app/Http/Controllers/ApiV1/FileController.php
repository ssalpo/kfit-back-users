<?php

namespace App\Http\Controllers\ApiV1;

use App\Constants\TempFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\TempFileImageRequest;
use App\Http\Requests\TempFileUploadRequest;
use App\Http\Resources\TempFileResource;
use App\Services\TempFileService;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    /**
     * @var TempFileService
     */
    private $tempFileService;

    public function __construct(TempFileService $tempFileService)
    {
        $this->tempFileService = $tempFileService;

        $this->middleware('role:admin')->only(['upload']);
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

    /**
     * Resizes and cache image
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function image(TempFileImageRequest $request)
    {
        return $this->tempFileService->getResizedImageToView(
            TempFile::FOLDER_AVATAR,
            $request->filename,
            $request->width,
            $request->height
        );
    }
}
