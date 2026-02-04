# FileController Documentation

## Overview
The `FileController` manages file-based content delivery, specifically handling help documentation and about page content. It serves markdown files as HTML and controls access based on user permissions.

## Namespace
```php
namespace App\Http\Controllers;
```

## Dependencies
- `Illuminate\Http\Request`
- `Illuminate\Support\Facades\File`
- `Illuminate\Support\Str`
- `Illuminate\Support\Facades\Auth`

## Methods

### showHelp(Request $request)
Displays help documentation based on user permissions and language preference.

**Parameters:** 
- `$request` - HTTP request containing language query parameter

**Query Parameters:**
- `lang` - Language preference (defaults to 'en', supports 'ta' for Tamil, 'si' for Sinhala)

**Returns:** `\Illuminate\View\View`

**Route:** GET `/help`

**View:** `help`

### showAbout()
Displays about page content.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/about`

**View:** `about`

### loadDocumentsFrom($directory, &$documents)
Internal helper method to load markdown documents from a directory.

**Parameters:**
- `$directory` - Path to the directory containing markdown files
- `&$documents` - Reference to an array to store loaded documents

**Returns:** void

**Access:** Private method

## Key Features

1. **Multi-Language Support:** Serves content in English, Tamil, and Sinhala
2. **Permission-Based Access:** Shows system documents only to authorized users
3. **Markdown Rendering:** Converts markdown files to HTML for display
4. **Dynamic Content Loading:** Loads documents from filesystem at runtime
5. **Hierarchical Document Organization:** Organizes documents by directory structure
6. **File Extension Filtering:** Only processes markdown (.md) files
7. **User Context Awareness:** Adjusts content based on user's role and permissions
8. **Clean Title Generation:** Converts filenames to readable titles