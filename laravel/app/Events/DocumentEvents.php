<?php

namespace App\Events;

abstract class DocumentEvents {

    const CREATED = "document-created";
    const COMPLETED = "document-completed";
    const PUBLISHED = "document-published";
    const DELETED = "document-deleted";

}