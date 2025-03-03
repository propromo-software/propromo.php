<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;

new class extends Component {

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
            $validated = $this->validate();
            $user = User::create([
                "name" => $this->name,
                "email" => $this->email,
                "auth_type"=> "PROPROMO",
                "password" => $this->password
            ]);

            Auth::login($user);
            return redirect('/');
        } catch (ValidationException $e) {
            $message = implode(", ", $e->validator->errors()->all());
            logger()->error('Registration Error', ['errors' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Registration Error',
                'message' => $message
            ]);
        } catch (Exception $e) {
            $message = $e->getMessage();
            logger()->error('Registration Error', ['message' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Registration Error',
                'message' => 'Something unexpected happened!'
            ]);
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
                <sl-input wire:model="name" size="medium" required placeholder="Your name"></sl-input>
                <sl-input wire:model="email" size="medium" required placeholder="Your email" type="email"></sl-input>
                <sl-input wire:model="password" size="medium" required placeholder="Your password" type="password"></sl-input>
                <sl-input wire:model="password_confirmation" size="medium" required placeholder="Confirm your password" type="password"></sl-input>

                <div class="flex justify-between items-end mt-5">
                    <a class="text-sm no-underline text-primary-blue hover:underline"
                        href="{{ url('login') }}">
                        Already registered?
                    </a>

                    <sl-button type="submit" wire:loading.attr="disabled" wire:ignore size="medium">Register</sl-button>
                </div>
            </form>
        </div>
    </div>
</div>
