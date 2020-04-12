<?php

namespace App\Console\Commands;

use App\Document;
use Illuminate\Console\Command;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;

class ImportDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports documents from database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tnt = new TNTIndexer;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $documents = Document::all();

        foreach ($documents as $document) {
            $this->insertDocument($document);
        }
        
    }

    public function insertDocument(Document $doc)
    {
        //$doc->n_grams    = $this->createNGrams($doc->content);
        $doc->save();
    } 

}
