<?php
namespace App\Http\Controllers;


use App\Models\Document;
use Illuminate\Support\Facades\Storage;


class DownloadController extends Controller
{
public function __invoke(Document $document)
{
$user = request()->user();
if ($user->isVendor() && $user->vendor_id !== $document->vendor_id) abort(403);
// Admin & Officer boleh mengunduh semua
return Storage::disk('private')->download($document->path, $document->original_name);
}
}