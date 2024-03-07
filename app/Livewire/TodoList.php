<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TodoList extends Component
{
    #[Validate('required|min:3|max:150')]
    public string $name;

    public function create()
    {
        $validated = $this->validate();
        Todo::create([
            'name' => $validated['name']
        ]);

        $this->reset();

        request()->session()->flash('success', 'The todo was created!');
    }
    public function render()
    {
        return view('livewire.todo-list');
    }
}
