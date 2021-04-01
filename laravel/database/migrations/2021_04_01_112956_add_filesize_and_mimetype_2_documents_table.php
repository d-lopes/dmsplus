<?php

use App\Http\Livewire\Documents\DocumentHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AddFilesizeAndMimetype2DocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->integer('size')->nullable();
            $table->string('mime_type', 100)->nullable(); // set to 100 since there is this 72 char long .docx (Microsoft Word OpenXML) mime type
            $table->string('md5_hash', 32)->nullable(); // deliberately not made unique because of potential collisions
        });

        // add file size, mime type and md5 hash for all existing documents
        DB::table('documents')->select(['path', 'content'])->cursor()->each(function ($document) {
            if (Storage::disk('documents')->exists($document->path)) {
                $document->size = Storage::disk('documents')->size($document->path);
                $document->mime_type = "application/pdf"; // currently we are only able to add PDF files anyways
            }
            if (isset($document->content)) {
                $document->md5_hash = DocumentHelper::generateHashValue($document->content);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('size');
            $table->dropColumn('mime_type');
            $table->dropColumn('md5_hash');
        });
    }
}