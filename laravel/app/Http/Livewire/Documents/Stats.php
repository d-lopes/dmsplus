<?php

namespace App\Http\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;

class Stats extends Component
{

    public function getStats() {
        $states = Document::allStates();

        $array = Document::selectRaw('status, count(*) as count')->groupBy('status')->get();
        $total = 0;
        $stats = [];
        foreach ($array as $item) {
            $count = $item->count;
            $kpi = (object) ['type' => $item->status];
            $kpi->value = $count;
            array_push($stats, $kpi);

            # remove state from all states array
            $pos = array_search($item->status, $states);
            unset($states[$pos]);
            
            # count overall number of documents
            $total += $count;
        }

        # add missing states
        foreach ($states as $item) {
            $kpi = (object) ['type' => $item];
            $kpi->value = 0;
            array_push($stats, $kpi);
        }

        $kpi = (object) ['type' => 'total'];
        $kpi->value = $total;
        array_push($stats, $kpi);

        return $stats;
    }

    public function render()
    {
        $stats = $this->getStats();

        return view('livewire.documents.stats', [ 'stats' => $stats ]);
    }
}
