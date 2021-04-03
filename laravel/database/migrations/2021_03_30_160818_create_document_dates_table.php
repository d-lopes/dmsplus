<?php

use App\Util\DocumentHelper;
use App\Models\DocumentDate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDocumentDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_dates', function (Blueprint $table) {
            $table->id();
            // By convention, in One to Many Relationships Eloquent will take the "snake case" name of the parent model and suffix it with '_id'. 
            // Thus we will stick with 'document_id' as foreign key name here
            //$table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreignId('document_id')->nullable()->index();
            $table->date('date_value');
            $table->timestamps();
        });

        // add document dates for all existing documents (based on their content)
        DB::table('documents')->select(['id', 'content'])->cursor()->each(function ($document) {

            $dateArr = DocumentHelper::extractDocumentDates($document->content);

            foreach ($dateArr as $date) {
                $entry = new DocumentDate();
                $entry->date_value = $date;
                $entry->document_id = $document->id;
                $entry->save();
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
        Schema::dropIfExists('document_dates');
    }
}
