<?php

namespace App\Http\Livewire\Documents;

use App\Models\DocumentStatus;
use Illuminate\Support\Facades\Storage;
use LaravelViews\Facades\UI;
use RuntimeException;

abstract class DocumentHelper {

    public static function getStatusBadge($status): string {
        $type = '';
        switch ($status) {
            case DocumentStatus::CREATED:
                $type = 'info';
                break;
            case DocumentStatus::PENDING:
                $type = 'warning';
                break;
            case DocumentStatus::INCOMPLETE:
                $type = 'danger';
                break;
            case DocumentStatus::PUBLISHED:
                $type = 'success';
                break;
            default:
                $type = 'default';
                break;
        }

        return UI::badge(DocumentStatus::asLabel($status), $type);
    }

    public static function handleDeleteAction($document) {
        // delete file from storage, if it exists
        $exists = Storage::disk('documents')->exists($document->path);
        if ($exists) {
            Storage::disk('documents')->delete($document->path);
        }

        // make sure the file is really gone
        $exists = Storage::disk('documents')->exists($document->path);
        if ($exists) {
            throw new RuntimeException('File at ' . $document->path . ' could not be deleted!');
        } else {
            $document->delete();
        }
    }
}