<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

new class extends Component {

    public $account_login_message;

    public $error_head;

    #[Validate(['email' => 'required|email'])]
    public $email;

    #[Validate(['password' => 'required'])]
    public $password;

    public function submit()
    {
        try{
            $this->validate();

            $credentials = [
                'email' => $this->email,
                'password' => $this->password,
            ];
            if (Auth::attempt($credentials)) {
                return redirect('/monitors');
            } else {
                throw new Exception("Cannot find user!");
            }
        }catch (Exception $e){
            $this->account_login_message = $e->getMessage();
            $this->error_head = "Seems like something went wrong...";
        }
    }
};
?>


<div class="flex flex-col items-center mt-4 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900">
    <div
        class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color sm:rounded-lg">

        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <h1 class="mb-9 text-6xl font-koulen text-primary-blue">LOGIN</h1>

                <form wire:submit="submit">
                    <sl-input wire:ignore wire:model="email" placeholder="Your email" type="email"></sl-input>
                    <br>
                    <sl-input wire:ignore wire:model="password" placeholder="Your password" type="password"></sl-input>

                <div class="flex gap-2 mt-4">
                    <a href="{{route('github.login')}}" target="_blank">
                        <sl-icon wire:ignore name="github" class="mt-0.5 text-4xl"></sl-icon>
                    </a>
                </div>
                    <div class="flex justify-between items-center mt-2">
                        <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                           href="{{ url('register') }}">
                            No Account yet?
                        </a>
                        <sl-button wire:ignore type="submit">Login</sl-button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @if($account_login_message)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br/>
            {{$account_login_message}}
        </sl-alert>
    @endif

</div>
