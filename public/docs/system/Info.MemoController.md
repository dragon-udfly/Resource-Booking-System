# MemoController Documentation

## Overview
The `MemoController` manages internal memo communications between users in the system. It handles sending, receiving, approving, and organizing memos with encryption for sensitive content.

## Namespace
```php
namespace App\Http\Controllers;
```

## Dependencies
- `App\Models\Memo`
- `App\Models\User`
- `Illuminate\Http\Request`
- `Illuminate\Support\Facades\Auth`
- `Illuminate\Support\Facades\Validator`
- `Carbon\Carbon`

## Methods

### index()
Displays the memo interface showing both inbox and outbox.

**Returns:** `\Illuminate\View\View`

**Route:** GET `/memo`

**View:** `internalmemo`

### fetchInbox()
Fetches the latest inbox memos via AJAX.

**Returns:** Rendered HTML for inbox rows

**Route:** GET `/memo/inbox`

### fetchOutbox()
Fetches the latest sent memos via AJAX.

**Returns:** Rendered HTML for sent rows

**Route:** GET `/memo/outbox`

### store(Request $request)
Stores a newly created memo in storage.

**Parameters:** 
- `$request` - HTTP request containing memo details

**Validation rules:**
- `receiver_id` - required, must exist in user table
- `subject` - required, string, max:255
- `body` - required, string

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/memo`

### show($id)
Shows a specific memo by ID.

**Parameters:**
- `$id` - The memo ID to retrieve

**Authorization:** Only sender or receiver can view the memo

**Returns:** JSON response with memo details

**Route:** GET `/memo/{id}`

### updateStatus(Request $request, $id)
Updates the status of a memo (approve/reject).

**Parameters:**
- `$request` - HTTP request containing status
- `$id` - The memo ID to update

**Validation rules:**
- `status` - required, must be 1 (approved) or 0 (rejected)

**Authorization:** Only receiver can update status

**Returns:** JSON response with success status

**Route:** PUT `/memo/{id}/status`

### clearRead()
Clears read/resolved memos from the inbox.

**Returns:** JSON response with success status

**Route:** POST `/memo/clear-read`

### clearSent()
Clears sent memos from the outbox.

**Returns:** JSON response with success status

**Route:** POST `/memo/clear-sent`

### clearRespondedMemos()
Clears fully resolved memos from history (both sender and receiver cleared).

**Returns:** `\Illuminate\Http\RedirectResponse`

**Route:** POST `/memo/clear-history`

## Key Features

1. **Encryption:** Memo subjects and bodies are encrypted for security
2. **Two-Way Communication:** Supports both sending and receiving memos
3. **Status Tracking:** Memos can be pending, approved, or rejected
4. **Role-Based Views:** Different layouts based on user role (admin vs regular user)
5. **Auto-Cleanup:** Allows clearing of resolved memos from inbox/outbox
6. **Permission System:** Ensures only authorized users can view or respond to memos
7. **AJAX Support:** Dynamic loading of memo lists without page refresh
8. **User Filtering:** Provides dropdown of eligible recipients