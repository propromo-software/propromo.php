<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

new class extends Component {
    #[Validate(['email' => 'required|email'])]
    public $email;

    #[Validate(['password' => 'required'])]
    public $password;

    public function sumbit()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect('/projects');
        } else {
            $this->addError('email', 'Invalid email or password.');
        }
    }
};
?>


<div class="mt-4 flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div>
        <a href="/">
            HOME
        </a>
    </div>

    <div
        class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color overflow-hidden sm:rounded-lg">

        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <h1 class="font-koulen text-6xl text-primary-blue mb-9">LOGIN</h1>

                <form wire:submit="sumbit">

                    <sl-input wire:ignore wire:model="email" placeholder="Your email" type="email"></sl-input>
                    <br>
                    <sl-input wire:ignore wire:model="password" placeholder="Your password" type="password"></sl-input>

                    <div class="flex items-center justify-between mt-5">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                           href="{{ url('register') }}">
                            No Account yet?
                        </a>

                        <sl-button wire:ignore type="submit">Login</sl-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
