<?php

use App\Models\User;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

new class extends Component {

    public $account_edit_error_message;
    public $account_edit_success_message;
    public $error_head;

    #[Validate(['email' => 'required|email|unique:users'])]
    public string $email = "";

    #[Validate(['password' => 'required|confirmed'])]
    public string $password = "";

    #[Validate(['password_confirmation' => 'required'])]
    public string $password_confirmation = "";

    public function save()
    {
        try {
            $this->validate();
            $user = User::whereId(auth()->id());
            $user->update([
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $this->account_edit_success_message = "Successfully updated account.";
        } catch (Exception $e) {
            $this->account_edit_error_message = $e->getMessage();
            $this->error_head = "Seems like something went wrong...";
        }

    }
    public function delte_account()
    {
        try{
            $user = User::whereId(Auth::user()->id)->first();
            $user->monitors()->delete();
            $user->delete();

            Auth::logout();
            return Redirect::to('login');
        }catch (Exception $e){
            $this->account_edit_error_message = $e->getMessage();
            $this->error_head = "Seems like something went wrong...";
        }
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }
}; ?>

<div>
    <form wire:submit="save" class="p-3.5">
        <h1 class="text-primary-blue text-2xl font-koulen text-left">Edit Profile</h1>
        <br>
        <sl-input wire:ignore wire:model="email" type="email" placeholder="Email"></sl-input>
        <br/>
        <sl-input wire:ignore wire:model="password" type="password" placeholder="Password" password-toggle></sl-input>
        <br>
        <sl-input wire:ignore wire:model="password_confirmation" type="password" placeholder="Retype Password"
                  password-toggle></sl-input>
        <br>
        <sl-button wire:ignore type="submit" wire:loading.attr="disabled" wire:ignore>Save</sl-button>
    </form>

    <div class="flex justify-end gap-2 p-3.5">
        <button wire:click="logout" class="text-white text-sm font-bold border-primary-blue bg-primary-blue border-2 rounded-md p-2">LOGOUT</button>
        <button wire:click="delte_account" class="text-white text-sm font-bold border-additional-red bg-additional-red border-2 rounded-md p-2">DELTE</button>
    </div>

    @if($account_edit_error_message)
        <sl-alert variant="danger" open closable>
            <sl-icon wire:ignore slot="icon" name="patch-exclamation"></sl-icon>
            <strong>{{$error_head}}</strong><br/>
            {{$account_edit_error_message}}
        </sl-alert>
    @endif

    @if($account_edit_success_message)
        <sl-alert variant="success" open closable>
            <sl-icon wire:ignore slot="icon" name="check-circle"></sl-icon>
            <strong>Success</strong><br/>
            {{$account_edit_success_message}}
        </sl-alert>
    @endif
</div>
