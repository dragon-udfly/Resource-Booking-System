<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function showHelp(Request $request)
    {
        $documents = [];
        $lang = $request->query('lang', 'en');

        $directory = public_path('docs/help');
        if ($lang === 'ta') {
            $directory = public_path('docs/help/tamil');
        } elseif ($lang === 'si') {
            $directory = public_path('docs/help/sinhala');
        }

        // Load public help documents
        $this->loadDocumentsFrom($directory, $documents);

        // Load system documents for Admin or Approval Officers
        if (Auth::check()) {
            $user = Auth::user();
            $hasApprovalAccess = $user->hasPermissionTo('administrative_officer_approval') ||
                $user->hasPermissionTo('additional_government_agent_approval') ||
                $user->hasPermissionTo('government_agent_approval');

            if ($user->role === 'admin' || $hasApprovalAccess) {
                $this->loadDocumentsFrom(public_path('docs/system'), $documents);
            }
        }

        return view('help', ['documents' => $documents, 'currentLang' => $lang]);
    }

    public function showAbout()
    {
        $documents = [];
        $this->loadDocumentsFrom(public_path('docs/details'), $documents);

        return view('about', ['documents' => $documents]);
    }

    private function loadDocumentsFrom($directory, &$documents)
    {
        if (File::exists($directory)) {
            $files = File::files($directory);

            foreach ($files as $file) {
                if ($file->getExtension() === 'md') {
                    $content = File::get($file);

                    // Convert Markdown to HTML
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
    }
}
