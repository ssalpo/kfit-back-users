<?php

namespace App\Services;

use App\Constants\TempFile;
use App\Models\TemporaryFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\FileNotFoundException;
use mysql_xdevapi\Exception;

class TempFileService
{
    const TMP_FOLDER_NAME = 'tmp';

    /**
     * Upload one/multiple files
     *
     * @param UploadedFile $file
     * @return array
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file)
    {
        if (is_array($file)) {
            $result = [];

            foreach ($file as $item) {
                $result[] = $this->saveAsTmp($item);
            }

            return $result;
        }

        return $this->saveAsTmp($file);
    }

    /**
     * Uploads a file to a temporary folder
     *
     * @param UploadedFile $file
     * @return mixed
     * @throws \Exception
     */
    public function saveAsTmp(UploadedFile $file)
    {
        $fileName = self::uniqFileName($file);

        $isStored = $file->storeAs(
            self::TMP_FOLDER_NAME,
            $fileName
        );

        if (!$isStored) throw new \Exception('Не удалось загрузить файл!');

        return TemporaryFile::create([
            'id' => $fileName,
            'user_filename' => $file->getClientOriginalName()
        ]);
    }

    /**
     * Returns a file to view
     *
     * @param string $folder
     * @param string $filename
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Exception
     */
    public function getFileToView(string $folder, string $filename)
    {
        $path = self::moveFolderPath($folder, $filename);
        $storage = Storage::disk('local');

        if (!in_array($folder, TempFile::FOLDERS)) {
            throw new \Exception('Некорректная дирректория файла передана!');
        }

        if (!$storage->exists($path)) {
            throw new FileNotFoundException('Файл не найден!');
        }

        return response($storage->get($path))->header('Content-Type', $storage->mimeType($path));
    }

    /**
     * Move files into folders from temp
     *
     * @param string $folder
     * @param string $filename
     * @return bool
     */
    public function moveFromTmpFolder(string $folder, string $filename): bool
    {
        $filePath = TempFileService::moveFolderPath($folder, $filename);

        return Storage::move(
            TempFileService::TMP_FOLDER_NAME . DIRECTORY_SEPARATOR . $filename,
            $filePath
        );
    }

    /**
     * Get the path to files move
     *
     * @param string $folder
     * @param string|null $filename
     * @return string
     */
    public static function moveFolderPath(string $folder, ?string $filename): string
    {
        $path = ['/files', $folder];

        if ($filename) $path[] = $filename;

        return implode('/', $path);
    }

    /**
     * Generate the unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    public static function uniqFileName(UploadedFile $file): string
    {
        return implode('.', [Str::uuid(), $file->getClientOriginalExtension()]);
    }
}
