<?php

namespace App\Http\Livewire\Documents;

use App\Models\DocumentDate;
use App\Models\DocumentStatus;
use Carbon\Carbon;
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

    public static function extractDocumentDates($content): array {
        // extact candidates for dates
        $patterns = [
            "/\d{4}\-\d{2}\-\d{2}/", // english pattern (yyyy-MM-dd)
            "/\d{2}\-\d{2}\-\d{4}/", // english pattern (dd-MM-yyyy)
            "/\d{2}\.\d{2}\.\d{4}/" // german pattern (dd.MM.yyyy)
        ];
        $candidates = [];
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                $candidates = array_merge($candidates, $matches[0]);
            }
        }
        $candidates = array_unique($candidates);

        // remove invalid dates
        $result = [];
        foreach ($candidates as $dateValue) {
            if ($datetime = strtotime($dateValue)) {
                array_push($result, Carbon::createFromTimestamp($datetime));
            }
        }
        
        return $result;
    }

}