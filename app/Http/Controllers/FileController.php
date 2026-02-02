<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function showHelp()
    {
        $directory = public_path('docs/help');
        $documents = [];

        if (File::exists($directory)) {
            $files = File::files($directory);

            foreach ($files as $file) {
                if ($file->getExtension() === 'md') {
                    $content = File::get($file);

                    // Convert Markdown to HTML
                    // Assuming standard Laravel installation has Str::markdown (uses league/commonmark)
                    $htmlContent = Str::markdown($content);

                    // Create a readable title from the filename
                    $filename = $file->getFilenameWithoutExtension();
                    $title = ucwords(str_replace(['_', '-'], ' ', $filename));

                    $documents[] = [
                        'title' => $title,
                        'content' => $htmlContent
                    ];
                }
            }
        }

        return view('help', ['documents' => $documents]);
    }
}
