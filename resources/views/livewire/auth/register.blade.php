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

<div class="flex flex-col items-center w-full h-full bg-gray-100">
    <div class="flex flex-col justify-center mt-10">
        <div class="flex gap-1 items-center mt-16 mb-2 sm:mt-10">
            <div class="w-[30px] h-[30px] rounded-full bg-primary-blue"></div>
            <div class="w-2 h-1 bg-primary-blue"></div>
            <div class="w-[30px] h-[30px] bg-other-grey rounded-full"></div>
        </div>

        <div class="px-10 pt-8 pb-8 mx-auto w-96 max-w-full bg-white rounded-lg border border-border-color">
            <h1 class="mb-8 text-6xl uppercase font-koulen text-primary-blue">Register</h1>

            <form wire:submit="save" class="flex flex-col gap-2">
                <sl-input size="medium" required wire:ignore wire:model="name" placeholder="Your name"></sl-input>
                <sl-input size="medium" required wire:ignore wire:model="email" placeholder="Your email" type="email"></sl-input>
                <sl-input size="medium" required wire:ignore wire:model="password" placeholder="Your password" type="password"></sl-input>
                <sl-input size="medium" required wire:ignore wire:model="password_confirmation" placeholder="Confirm your password" type="password"></sl-input>

                <div class="flex justify-between items-end mt-5">
                    <a class="text-sm no-underline text-primary-blue hover:underline"
                        href="{{ url('login') }}">
                        Already registered?
                    </a>

                    <sl-button size="medium" wire:ignore type="submit">Register</sl-button>
                </div>
            </form>
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
