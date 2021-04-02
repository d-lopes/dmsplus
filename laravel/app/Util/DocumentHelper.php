<?php

namespace App\Util;

use App\Models\DocumentStatus;
use Carbon\Carbon;
use LaravelViews\Facades\UI;

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
        $candidates = [];
        foreach (array_keys (DateCandidate::PATTERNS) as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                $format = DateCandidate::PATTERNS[$pattern];                
                foreach ($matches[0] as $dateValue) {
                    array_push($candidates, new DateCandidate($dateValue, $format));
                }
            }
        }

        // ensure unique values        
        $candidates = array_unique($candidates);
        foreach ($candidates as $index=>$candidate) {
            foreach($candidates as $sibling){
                if($candidate->isSimilarTo($sibling)){
                    unset($candidates[$index]);
                    continue;
                }
            }
        }
        
        // remove invalid dates
        $result = [];
        foreach ($candidates as $candidate) {
            if ($candidate->isValid()) {
                $date = Carbon::createFromTimestamp($candidate->getTimestamp());
                $yearsFromToday = Carbon::now()->diffInYears($date, true);
                if ($yearsFromToday <= 80) { // make sure the idendified dates are within our lifetime
                    array_push($result, $date);
                } 
            }
        }

        return $result;
    }

    public static function generateHashValue($content): string {
        return md5($content);
    }

}