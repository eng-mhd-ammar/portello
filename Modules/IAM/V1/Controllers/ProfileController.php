<?php

namespace Modules\IAM\V1\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Modules\Core\Controllers\BaseController;
use Modules\IAM\V1\DTO\Profile\UpdateProfileDTO;
use Modules\IAM\V1\Requests\Profile\UpdateProfileRequest;
use Modules\IAM\V1\Services\ProfileService;

class ProfileController extends BaseController
{
    public function __construct(protected ProfileService $profileService) {}

    public function show(): Response
    {
        $user = request()->user();

        return $this->inertia('IAM/Profile/Index', [
            'profile' => $this->profileService->profilePayload($user),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $result = $this->profileService->update(
            $request->user(),
            UpdateProfileDTO::fromRequest($request),
        );

        if ($result['email_changed']) {
            Auth::logout();
            $request->session()->put('pending_verification_user_id', $result['user']->id);

            return redirect()
                ->route('verification.notice')
                ->with('success', 'Profile updated. Verify your new email address to continue.');
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
