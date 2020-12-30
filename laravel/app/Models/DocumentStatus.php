<?php

namespace App\Models;

abstract class DocumentStatus {

    const CREATED = 'new';
    const PENDING = 'pending';
    const INCOMPLETE = 'incomplete';
    const PUBLISHED = 'published';

    /**
     * Returns an array of all available document status
     *
     * @return Array array with the values
     */
    public static function all() {
        return [
            DocumentStatus::CREATED, 
            DocumentStatus::PENDING, 
            DocumentStatus::INCOMPLETE, 
            DocumentStatus::PUBLISHED
        ];
    }

    /**
     * Returns a human-readable label for a document status
     *
     * @param String status 
     * 
     * @return String the translated value
     */
    public static function asLabel($status) {
        switch ($status) {
            case DocumentStatus::CREATED:
                return __('Created');
                break;
            case DocumentStatus::PENDING:
                return __('Pending');
                break;
            case DocumentStatus::INCOMPLETE:
                return __('Incomplete');
                break;
            case DocumentStatus::PUBLISHED:
                return __('Published');
                break;
            default:
                return __('Unknown');
                break;
        }
    }

    /**
     * Returns an array of all available document status including their human-readable labels
     *
     * @return Array associative array with the title and values
     */
    public static function asSelectOptions() {
        $result = [];
        foreach (DocumentStatus::all() as $status) {
            $label = DocumentStatus::asLabel($status);
            $result[$label] = $status;
        }

        return $result;
    }

}