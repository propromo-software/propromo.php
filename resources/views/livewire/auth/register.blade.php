<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

new class extends Component {

    public $account_creation_message;
    public $error_head;

    #[Validate(['name' => 'required'])]
    public $name;

    #[Validate(['email' => 'required|email|unique:users'])]
    public $email;

    #[Validate(['password' => 'required|confirmed|max:20'])]
    public $password;

    #[Validate(['password_confirmation' => 'required'])]
    public $password_confirmation;

    public function save()
    {
        try {
            $this->validate();

            $user = User::create([
                "name" => $this->name,
                "email" => $this->email,
                "auth_type"=> "PROPROMO",
                "password" => $this->password
            ]);

            Auth::login($user);

            redirect('/');
        } catch (Exception $e) {
            $this->account_creation_message = $e->getMessage();
            $this->error_head = "Seems like something went wrong...";
        }
    }

};

?>
<div class="mt-4 flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div
        class="w-full sm:max-w-md mt-6 p-12 bg-white dark:bg-gray-800 border-[1px] border-border-color sm:rounded-lg">

        <div class="flex justify-center">
            <div class="w-full max-w-md"> <!-- Adjust max width as needed -->
                <h1 class="font-koulen text-6xl text-primary-blue mb-9">REGISTER</h1>

                <form wire:submit="save">

                    <sl-input required wire:ignore wire:model="name" placeholder="Your name"></sl-input>
                    <br>
                    <sl-input required wire:ignore wire:model="email" placeholder="Your email" type="email"></sl-input>
                    <br>
                    <sl-input required wire:ignore wire:model="password" placeholder="Your password"
                              type="password"></sl-input>
                    <br>
                    <sl-input required wire:ignore wire:model="password_confirmation"
                              placeholder="Confirm your password"
                              type="password"></sl-input>


                    <div class="flex items-center justify-between mt-5">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                           href="{{ url('login') }}">
                            Already registered?
                        </a>

                        <sl-button wire:ignore type="submit">Register</sl-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($account_creation_message)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br/>
            {{$account_creation_message}}
        </sl-alert>
    @endif
</div>
