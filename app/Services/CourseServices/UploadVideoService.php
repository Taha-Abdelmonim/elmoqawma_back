<?php

namespace App\Services\CourseServices;

use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;


class UploadVideoService
{
    protected function receiveFile($request)
    {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        return $receiver->receive(); // receive file
    }


    protected function updateFiles($fileReceived,$id)
    {
        if ($fileReceived->isFinished()) {
            // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); //file name without extenstion
            $filenameShow = $fileName;
            $fileName .= "_" . md5(time()) . "_.mp3"; // a unique file name
            $disk = Storage::disk("videos");
            $path = $disk->putFileAs("$id", $file, $fileName);

            unlink($file->getPathname());
            return [
                "path" => "/storage/videos/" . $path,
                "filename" => $filenameShow,
            ];
        }

        // otherwise return percentage information
        return  $fileReceived->handler();

    }

    public function upload($request,$id)
    {
        $fileReceived = $this->receiveFile($request);
        $handler = $this->updateFiles($fileReceived,$id);
        return [
            "done" => $handler->getPercentageDone(),
            "status" => true,
        ];
    }
}
