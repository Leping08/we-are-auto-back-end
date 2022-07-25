<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read League $leagues
 * @property-read CurrentPick $current_picks
 * @property-read VideoProgress $video_progress
 * @property-read RaceProblem $race_problems
 * @property-read FollowSeries $series_following
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class);
    }

    /**
     * @return BelongsToMany
     */
    public function current_picks(): BelongsToMany
    {
        return $this->belongsToMany(CurrentPick::class);
    }

    /**
     * @return HasMany
     * @method video_progress()
     */
    public function video_progress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }

    /**
     * @return HasMany
     * @method race_problems()
     */
    public function race_problems(): HasMany
    {
        return $this->hasMany(RaceProblem::class);
    }

    /**
     * @return BelongsToMany
     * @method series_following()
     */
    public function series_following(): BelongsToMany
    {
        return $this->belongsToMany(Series::class, 'follow_series')->withTimestamps();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token) {
        // The trick is first to instantiate the notification itself
        $notification = new ResetPassword($token);
        // Then use the createUrlUsing method
        $notification->createUrlUsing(function ($token) {
            return 'https://weareauto.io/password/reset/' . $token; // todo put this in config or env var
        });
        // Then you pass the notification
        $this->notify($notification);
    }
}
