<?php

namespace App\Http\Livewire\Documents;

use App\Events\DocumentEvents;
use App\Models\Document;
use App\Models\DocumentStatus;
use Livewire\Component;

class Stats extends Component
{

    protected $listeners = [DocumentEvents::CREATED => "render"];

    public function getStats() {
        $states = DocumentStatus::all();

        $array = Document::selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->orderBy('count', 'desc')
                    ->orderBy('status', 'asc')
                    ->get();
        $stats = [];
        foreach ($array as $item) {
            $count = $item->count;
            $kpi = (object) ['type' => $item->status];
            $kpi->value = $count;
            array_push($stats, $kpi);

            # remove state from all states array
            $pos = array_search($item->status, $states);
            unset($states[$pos]);
        }

        # add missing states
        foreach ($states as $item) {
            $kpi = (object) ['type' => $item];
            $kpi->value = 0;
            array_push($stats, $kpi);
        }
        
        return $stats;
    }

    public function render()
    {
        $stats = $this->getStats();

        # count overall number of documents
        $total = 0;
        foreach ($stats as $item) {
            $total += $item->value;
        }

        return view('livewire.documents.stats', [ 
                        'stats' => $stats, 
                        'total' => $total 
                    ]);
    }
}
