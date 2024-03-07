<?php

namespace App\Livewire;

use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Exception;

class TodoList extends Component
{
    use WithPagination;

    #[Validate('required|min:3|max:50')]
    public string $name = '';

    public string $search = '';
    public int $todoId = 0;

    #[Validate('required|min:3|max:50')]
    public string $editName = '';

    public function create(): void
    {
        $validated = $this->validateOnly('name');

        Todo::create([
            'name' => $validated['name']
        ]);

        $this->reset();

        request()->session()->flash('success', 'The todo was created!');

        $this->resetPage();
    }

    public function delete(int $todoId): void
    {
        try {
            Todo::findOrFail($todoId)->delete();
        } catch (Exception $e) {
            request()->session()->flash('error', 'The todo could not be deleted!');
            Log::error('Error on delete Todo:'. $e->getMessage());
            return;
        }
    }

    public function toggle(int $todoId): void
    {
        $todo = Todo::findOrFail($todoId);
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function edit(int $todoId): void 
    {
        $this->todoId = $todoId;
        $this->editName = Todo::find($todoId)->name;   
    }

    public function cancelEdit() 
    {
        $this->reset('todoId', 'editName');
    }

    public function update() 
    {
        $validated = $this->validateOnly('editName');
        Todo::find($this->todoId)->update([
            'name' => $validated['editName']
        ]);

        $this->cancelEdit();
    }

    public function render(): View
    {
        $todos = Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5);

        return view('livewire.todo-list', ['todos' => $todos]);
    }
}
