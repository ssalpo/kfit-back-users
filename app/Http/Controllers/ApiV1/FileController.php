<?php

namespace App\Http\Controllers\ApiV1;

use App\Constants\TempFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\TempFileImageRequest;
use App\Http\Requests\TempFileUploadRequest;
use App\Http\Resources\TempFileResource;
use App\Services\TempFileService;

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
     * @OA\Get(
     *     path="/files/{model}/{folder}/{filename}",
     *     tags={"Files"},
     *     summary="Returns a file to view",
     *     @OA\Parameter(
     *          in="path",
     *         description="Model name",
     *         name="model",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="model", summary="Model name"),
     *     ),
     *     @OA\Parameter(
     *          in="path",
     *         description="File folder name",
     *         name="folder",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="folder", summary="Folder name"),
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         description="File name",
     *         name="filename",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="ccbaef92-9bfa-46b3-8c0a-3b985e46987f.jpg", summary="Folder name"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Exception
     */
    public function file(string $model, string $folder, string $filename)
    {
        return $this->tempFileService->getFileToView($model, $folder, $filename);
    }

    /**
     * Uploads a file to a temporary folder
     *
     * @OA\Post(
     *      path="/files/upload",
     *      tags={"Files"},
     *      summary="Upload file",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="file",
     *                     oneOf={
     *                     	   @OA\Schema(type="file"),
     *                     	   @OA\Schema(type="file[]"),
     *                     }
     *                 )
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *             @OA\Examples(example="id", value={"success": true}, summary="Uploaded file name"),
     *             @OA\Examples(example="user_filename", value={"success": true}, summary="Uploaded file original name"),
     *         )
     *       )
     * )
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
     * @OA\Get (
     *     path="/files/image/{model}/{folder}/{filename}/{width}/{height}",
     *     tags={"Files"},
     *     @OA\Parameter(
     *         in="path",
     *         name="model",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="folder",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="filename",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="width",
     *         required=true,
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="height",
     *         required=true,
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success"
     *     )
     * )
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function image(TempFileImageRequest $request)
    {
        $folder = $request->model . DIRECTORY_SEPARATOR . $request->folder;

        return $this->tempFileService->getResizedImageToView(
            $folder,
            $request->filename,
            $request->width,
            $request->height
        );
    }
}
