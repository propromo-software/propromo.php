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

    public function save()
    {
        try {
            $validated = $this->validate();

            $credentials = [
                'email' => $this->email,
                'password' => $this->password,
            ];

            if (Auth::attempt($credentials)) {
                return redirect('/monitors');
            } else {
                $this->dispatch('show-error-alert', [
                    'head' => 'Login Error',
                    'message' => 'Something unexpected happened!'
                ]);
            }
        } catch (ValidationException $e){
            $message = implode(", ", $e->validator->errors()->all());
            logger()->error('Login Error', ['errors' => $message]);

            $this->dispatch('show-error-alert', [
                'head' => 'Login Error',
                'message' => $message
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
            <h1 class="mb-8 text-6xl uppercase font-koulen text-primary-blue">Login</h1>

            <form wire:submit="save" class="flex flex-col gap-2">
                <sl-input size="medium" required wire:ignore wire:model="name" placeholder="Your name"></sl-input>
                <sl-input size="medium" required wire:ignore wire:model="email" placeholder="Your email" type="email"></sl-input>
                <sl-input size="medium" required wire:ignore wire:model="password" placeholder="Your password" type="password"></sl-input>

                <div class="flex items-center pt-2 pb-1">
                    <div class="flex-grow border-t border-border-color"></div>
                    <span class="flex-shrink mx-4 text-secondary-grey">or via OAuth</span>
                    <div class="flex-grow border-t border-border-color"></div>
                </div>

                <a class="block mx-auto" href="{{route('github.login')}}" target="_blank">
                    <sl-icon wire:ignore name="github" class="mt-0.5 text-4xl"></sl-icon>
                </a>

                <div class="flex justify-between items-end mt-5">
                    <a class="text-sm no-underline text-primary-blue hover:underline"
                        href="{{ url('register') }}">
                        No Account yet?
                    </a>

                    <sl-button size="medium" wire:ignore type="submit">Login</sl-button>
                </div>
            </form>
        </div>
    </div>
</div>
