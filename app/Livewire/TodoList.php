<?php

namespace App\Livewire;

use Livewire\Component;

class TodoList extends Component
{
    public string $name;
    
    public function render()
    {
        return view('livewire.todo-list');
    }
}
