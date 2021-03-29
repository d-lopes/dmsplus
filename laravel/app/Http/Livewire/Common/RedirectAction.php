<?php

namespace App\Http\Livewire\Common;

use LaravelViews\Actions\RedirectAction as LaravelViewsRedirectAction;

class RedirectAction extends LaravelViewsRedirectAction
{
    public $params;

    public function __construct(string $to, array $params, string $title, string $icon)
    {
        parent::__construct($to, $title, $icon);

        $this->params = $params;

        // Overrides the original id to create different ids for each redirect action
        $this->id = $this->id . '-' . $this->to . '-' . join('.', $this->params);
    }

    public function handle($item)
    {
        return redirect()->route($this->to, array_merge($this->params, ['id' => $item]));
    }
}
