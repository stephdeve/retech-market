<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'currency' => ['required', 'string', 'max:3', Rule::in(['XOF', 'EUR', 'USD'])],
            'phone' => ['nullable', 'string', 'max:32'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov', 'max:15360'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'url', 'max:255'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // Upload vidéo si présente
        if (isset($input['video'])) {
            // Supprimer l'ancienne vidéo si existe
            if ($user->video_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->video_path);
            }
            $user->video_path = $input['video']->store('users/videos', 'public');
            $user->video_url = null; // Reset l'URL si on upload un fichier
        }

        // Mise à jour de l'URL vidéo si fournie
        // Mise à jour de l'URL vidéo (si vide, on l'efface)
        if (array_key_exists('video_url', $input)) {
            $user->video_url = $input['video_url'];
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'currency' => $input['currency'],
                'phone' => $input['phone'] ?? null,
                'social_links' => $input['social_links'] ?? [],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'currency' => $input['currency'],
            'phone' => $input['phone'] ?? null,
            'social_links' => $input['social_links'] ?? [],
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
