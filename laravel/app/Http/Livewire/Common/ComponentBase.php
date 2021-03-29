<?php

namespace App\Http\Livewire\Common;

use Livewire\Component;

abstract class ComponentBase extends Component {

    public function success($message = null)
    {
        $this->setMessage('success', $message);
    }

    public function error($message = null)
    {
        $this->setMessage('danger', $message);
    }

    public function confirm($message = null)
    {
        session()->flash('confirmationMessage', $message);
    }

    public function resetMessages()
    {
        session()->flash('messageType', null);
        session()->flash('message', null);
        session()->flash('confirmationMessage', null);
    }

    private function setMessage($type = 'success', $message = null)
    {
        session()->flash('messageType', $type);
        session()->flash('message', $message ? $message : $this->messages[$type]);
    }

}