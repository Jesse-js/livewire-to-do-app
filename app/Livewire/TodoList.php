<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    #[Validate('required|min:3|max:50')]
    public string $name = '';

    public string $search = '';

    public function create()
    {
        $validated = $this->validateOnly('name');
        
        Todo::create([
            'name' => $validated['name']
        ]);

        $this->reset();

        request()->session()->flash('success', 'The todo was created!');
    }
    public function render()
    {
        $todos = Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5);

        return view('livewire.todo-list', ['todos' => $todos]);
    }
}
