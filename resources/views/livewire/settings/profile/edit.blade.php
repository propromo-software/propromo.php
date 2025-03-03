<?php

use App\Models\User;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

new class extends Component {

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

            $this->dispatch('show-success-alert', [
                'head' => 'Saved Account Information',
                'message' => 'Successfully saved account information!'
            ]);
        } catch (Exception $e) {
            $message = $e->getMessage();
            logger()->error('Save account information Error', ['message' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Save Account Error',
                'message' => 'Something unexpected happened!'
            ]);
        }
    }

    public function delete_account()
    {
        try{
            $user = User::whereId(Auth::user()->id)->first();
            $user->monitors()->delete();
            $user->delete();

            Auth::logout();
            return Redirect::to('login');
        }catch (Exception $e){
            $message = $e->getMessage();
            logger()->error('Delete account Error', ['message' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Delete Account Error',
                'message' => 'Something unexpected happened!'
            ]);
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
        <h1 class="mb-6 text-3xl text-left text-primary-blue font-koulen">Edit Profile</h1>
        
        <div class="flex flex-col gap-4">
            <sl-input wire:ignore wire:model="email" type="email" placeholder="Email"></sl-input>
            <sl-input wire:ignore wire:model="password" type="password" placeholder="Password" password-toggle></sl-input>
            <sl-input wire:ignore wire:model="password_confirmation" type="password" placeholder="Retype Password" password-toggle></sl-input>
        
            <div class="[&_sl-button::part(base)]:w-full">
                <sl-button wire:ignore type="submit" variant="default" wire:loading.attr="disabled">
                    <sl-icon slot="prefix" name="check-lg" class="text-base"></sl-icon>
                    Save Changes
                </sl-button>
            </div>
        </div>
    </form>

    <div class="flex gap-3 justify-end p-3.5">
        <sl-button wire:click="logout" variant="default" size="small" class="[&_sl-icon]:-mt-0.5">
            <sl-icon slot="prefix" name="box-arrow-right" class="text-base"></sl-icon>
            Logout
        </sl-button>
        <sl-button wire:click="delete_account" variant="danger" size="small" class="[&_sl-icon]:-mt-0.5 [&_sl-button]:bg-additional-red">
            <sl-icon slot="prefix" name="trash" class="text-base"></sl-icon>
            Delete Account
        </sl-button>
    </div>
</div>
