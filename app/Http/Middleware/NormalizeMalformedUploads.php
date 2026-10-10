<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class NormalizeMalformedUploads
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->containsInvalidFile($request->files->all())) {
            $message = 'فایل ارسالی قابل پردازش نیست. لطفاً فایل را دوباره انتخاب و ارسال کنید.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => ['file' => [$message]],
                ], 422);
            }

            return redirect()->back()->withErrors(['image' => $message]);
        }

        return $next($request);
    }

    private function containsInvalidFile(array $files): bool
    {
        foreach ($files as $file) {
            if ($file === null || $file instanceof UploadedFile) {
                continue;
            }

            if (!is_array($file) || $this->containsInvalidFile($file)) {
                return true;
            }
        }

        return false;
    }
}
