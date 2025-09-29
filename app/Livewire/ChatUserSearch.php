<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatUserSearch extends Component
{
    public $search = ''; // ✅ This is the missing part

    public function render()
    {
        return view('livewire.chat-user-search', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                ->where('id', '!=', Auth::id())
                ->get()
        ]);
    }
}
