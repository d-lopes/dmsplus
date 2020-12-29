<?php

namespace App\Models;

abstract class DocumentStatus {
    const CREATED = 'new';
    const PENDING = 'pending';
    const INCOMPLETE = 'incomplete';
    const PUBLISHED = 'published';
}