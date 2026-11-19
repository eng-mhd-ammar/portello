<?php

namespace Modules\IAM\V1\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\IAM\Exceptions\UnverifiedEmailException;
use Modules\IAM\Models\User;
use Modules\IAM\V1\DTO\Authentication\LoginDTO;
use Modules\IAM\V1\DTO\Authentication\RegisterDTO;
use Modules\IAM\V1\Requests\Authentication\LoginRequest;
use Modules\IAM\V1\Requests\Authentication\RegisterRequest;
use Modules\IAM\V1\Requests\Authentication\VerifyEmailRequest;
use Modules\IAM\V1\Services\AuthService;
use Modules\IAM\V1\Services\VerificationCodeService;

class AuthController extends BaseController
{
    public function __construct(
        protected AuthService $authService,
        protected VerificationCodeService $verificationCodeService,
    ) {}

    public function showLogin(): Response
    {
        return $this->inertia('IAM/Auth/Login');
    }

    public function showRegister(): Response
    {
        return $this->inertia('IAM/Auth/Register');
    }

    public function showVerifyEmail(Request $request): Response|RedirectResponse
    {
        $user = $this->pendingVerificationUser($request);

        if ($user === null) {
            return redirect()->route('register');
        }

        if ($user->email_verified_at !== null) {
            $request->session()->forget('pending_verification_user_id');

            return redirect()->route('login');
        }

        return $this->inertia('IAM/Auth/VerifyEmail', [
            'email' => $user->email,
        ]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $this->authService->login(LoginDTO::fromRequest($request));

            return redirect()->intended(route('dashboard'));
        } catch (UnverifiedEmailException $exception) {
            $request->session()->put('pending_verification_user_id', $exception->user->id);

            return redirect()
                ->route('verification.notice')
                ->with('success', 'We sent a verification code to your email. Enter it below to continue.');
        }
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->register(RegisterDTO::fromRequest($request));

        $request->session()->put('pending_verification_user_id', $user->id);

        return redirect()
            ->route('verification.notice')
            ->with('success', 'Account created. Enter the verification code we sent to your email.');
    }

    public function verifyEmail(VerifyEmailRequest $request): RedirectResponse
    {
        $user = $this->pendingVerificationUser($request);

        if ($user === null) {
            return redirect()->route('register');
        }

        $this->verificationCodeService->verifyEmailCode($user, $request->string('code')->toString());

        Auth::login($user);

        $request->session()->forget('pending_verification_user_id');
        $request->session()->regenerate();

        return $this->redirectWithSuccess('dashboard', 'Email verified successfully. Welcome to Portello.');
    }

    public function resendVerificationEmail(Request $request): RedirectResponse
    {
        $user = $this->pendingVerificationUser($request);

        if ($user === null) {
            return redirect()->route('register');
        }

        if ($user->email_verified_at !== null) {
            $request->session()->forget('pending_verification_user_id');

            return redirect()->route('login');
        }

        $this->verificationCodeService->resendForPendingUser($user);

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('home');
    }

    protected function pendingVerificationUser(Request $request): ?User
    {
        $userId = $request->session()->get('pending_verification_user_id');

        if (! $userId) {
            return null;
        }

        return User::query()->find($userId);
    }
}
