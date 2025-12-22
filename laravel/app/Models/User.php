<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the patient record associated with the user.
     * Relies on the 'user_id' foreign key in the Patient table.
     */
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'id');
    }

    /**
     * Get the doctor record associated with the user.
     * Doctors are linked via Email in the new schema (or user_id if you added it, but standard schema used Email).
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'Email', 'email');
    }

    /**
     * Get the lab technician record associated with the user.
     */
    public function labTechnician()
    {
        // Assuming Lab_Technician table uses 'Email' column
        return $this->hasOne(LabTechnician::class, 'Email', 'email');
    }

    /**
     * Get the pharmacist record associated with the user.
     */
    public function pharmacist()
    {
        // Assuming Pharmacist table uses 'Email' column
        return $this->hasOne(Pharmacist::class, 'Email', 'email');
    }

    /**
     * Get the receptionist record associated with the user.
     */
    public function receptionist()
    {
        // Assuming Receptionist table uses 'Email' column
        return $this->hasOne(Receptionist::class, 'Email', 'email');
    }

    /**
     * Dynamic Accessor: Get the profile associated with the user.
     */
    public function getProfileAttribute()
    {
        return match ($this->role) {
            'doctor' => $this->doctor,
            'patient' => $this->patient,
            'lab' => $this->labTechnician,
            'pharmacy' => $this->pharmacist,
            'reception' => $this->receptionist,
            default => null,
        };
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }
}
