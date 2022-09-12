<?php

namespace App\Services;

use App\Models\TemporaryFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\FileNotFoundException;

class TempFileService
{
    const TMP_FOLDER_NAME = 'tmp';

    /**
     * Загружает файлы
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

        return $this->uploadFile($file);
    }

    /**
     * Загружает файла во временную папку
     *
     * @param UploadedFile $file
     * @return mixed
     * @throws \Exception
     */
    public function saveAsTmp(UploadedFile $file)
    {
        $fileName = $file->storeAs(
            self::TMP_FOLDER_NAME,
            self::uniqFileName($file)
        );

        if (!$fileName) throw new \Exception('Не удалось загрузить файл!');

        return TemporaryFile::create([
            'id' => $fileName,
            'user_filename' => $file->getClientOriginalName()
        ]);
    }

    /**
     * Возвращает файл для просмотра
     *
     * @param string $folder
     * @param string $filename
     * @return string
     * @throws \Exception
     */
    public function getFileToView(string $folder, string $filename): string
    {
        $filePath = self::filePathFromFolder($folder, $filename);

        if (!in_array($folder, TemporaryFile::FOLDERS)) {
            throw new \Exception('Некорректная дирректория файла передана!');
        }

        if (!Storage::path($filePath)) {
            throw new FileNotFoundException('Файл не найден!');
        }

        return response()->file($filePath, ['Content-Type' => Storage::mimeType($filePath)]);
    }

    /**
     * Перемещает аватарку из временной директории
     *
     * @param string $filename
     * @return bool
     */
    public function moveAvatarFromTmpFolder(string $filename): bool
    {
        $filePath = TempFileService::filePathFromFolder(
            TemporaryFile::FOLDER_AVATARS, $filename
        );

        return Storage::move(TempFileService::TMP_FOLDER_NAME, $filePath);
    }

    public static function filePathFromFolder(string $folder, string $filename): string
    {
        return implode('/', ['/files', $folder, $filename]);
    }

    /**
     * Генерирует уникальное название файла
     *
     * @param UploadedFile $file
     * @return string
     */
    public static function uniqFileName(UploadedFile $file): string
    {
        return implode('.', [Str::uuid() . '.' . $file->getClientOriginalExtension()]);
    }
}
