<?php

namespace App\Util;

use DateTime;

class DateCandidate {

    private const MONTHS = [
        "Januar" =>  "01", 
        "January" => "01",
        "Februar" =>  "02", 
        "February" => "02",
        "März" =>  "03", 
        "March" => "03",
        "April" =>  "04", 
        //"April" => "04",
        "Mai" =>  "05", 
        "May" => "05",
        "Juni" =>  "06", 
        "June" => "06",
        "Juli" =>  "07", 
        "July" => "07",
        "August" =>  "08", 
        //"August" => "08",
        "September" =>  "09", 
        //"September" => "09",
        "Oktober" =>  "10", 
        "October" => "10",
        "November" =>  "11", 
        //"November" => "11",
        "Januar" =>  "12", 
        "Dezember" => "12",
    ];
    
    public const PATTERNS = [
        "/\d{4}\-\d{2}\-\d{2}/" => "Y-m-d", // english pattern (yyyy-MM-dd)
        "/\d{2}\-\d{2}\-\d{4}/" => "d-m-Y", // english pattern (dd-MM-yyyy)
        "/\d{2}\.\d{2}\.\d{4}/" => "d.m.Y", // german pattern (dd.MM.yyyy)
        "/\d{2}\.\d{2}\.\d{2}/" => "d.m.y", // german pattern (dd.MM.yy)
        "/\d{1,2}\. \w{3,9} \d{4}/" => "d-m-Y", // common pattern (dd. MMM yyyy) => is internally rewritten to dd-MM-yyyy
        "/\w{3,9} \d{4}/" => "d-m-Y" // common pattern (MMM yyyy) => is internally rewritten to MM-yyyy
    ];

    private $origValue;
    private $format;
    private $parsedValue;

    function __construct($origValue, $format) {
        $this->origValue = $origValue;
        $this->format = $format;

        // if value contains the name of a month then replace it with its nominal counter part
        $datestring = $this->origValue;
        $pattern = "/(" . implode('|', array_keys(self::MONTHS)) . ")/";
        if (preg_match($pattern, $origValue, $matches)) {
            $match = $matches[0];
            $datestring = str_replace($match, self::MONTHS[$match], $origValue);
            $datestring = str_replace(['.',' '], ['','-'], $datestring);
            // if value has only the shord form we want to fix that 
            if (strlen($datestring) < 10) {
                $datestring = "01-$datestring";
            }
        }

        $this->parsedValue = DateTime::createFromFormat($this->format, $datestring);
    }

    function getValueAsDateTime() {
        return $this->parsedValue;
    }

    function isValid(): bool {
        return $this->parsedValue ? true : false;
    }

    function getTimestamp() {
        return $this->getValueAsDateTime()->getTimestamp();
    }

    function getOriginalValue() {
        return $this->origValue;
    }

    function getFormat() {
        return $this->format;
    }

    function __toString() {
        return $this->getOriginalValue();
    }

    /**
     * Returns TRUE, if the following conditions apply:
     *  - must not be equal to the other value
     *  - must not be equal to the start of the other value
     *  - must not be equal to the end of the other value
     * 
     * Returns FALSE otherwise
     * 
     * For instance:
     * 
     * candidate '24.06.20' is equal to the start of '24.06.2019' => similar  
     * candidate 'Juli 2020' is equal to the end of '05. Juli 2020' => similar 
     * candidate '05. Juli 2020' is equal to the start/end of '05. Juli 2020' but it is also equal to the entire value => NOT similar!
     * 
     * @param DateCandidate $other the other date candidate to compare this instance to
     */
    function isSimilarTo($other) {
        return $this->getOriginalValue() != $other->getOriginalValue() &&
            (
                str_starts_with($other->getOriginalValue(), $this->getOriginalValue()) || 
                str_ends_with($other->getOriginalValue(), $this->getOriginalValue())      
            );
            
    }
}