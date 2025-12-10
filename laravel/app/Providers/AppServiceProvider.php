<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Dispatcher $events): void
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Add a static dashboard link for all authenticated users
            $event->menu->add([
                'text' => 'Dashboard',
                'route'  => 'dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ]);

            // Get the authenticated user's role
            $role = Auth::user() ? Auth::user()->role : null;

            // Add menu items based on the user's role
            if ($role === 'admin') {
                $event->menu->add(
                    ['header' => 'ADMIN MANAGEMENT'],
                    [
                        'text' => 'Users',
                        'route'  => 'admin.users.index',
                        'icon' => 'fas fa-fw fa-users',
                    ],
                    [
                        'text' => 'Doctors',
                        'route'  => 'admin.doctors.index',
                        'icon' => 'fas fa-fw fa-user-md',
                    ],
                    [
                        'text' => 'Patients',
                        'route'  => 'admin.patients.index',
                        'icon' => 'fas fa-fw fa-user-injured',
                    ],
                    [
                        'text' => 'Appointments',
                        'route'  => 'admin.appointments.index',
                        'icon' => 'fas fa-fw fa-calendar-check',
                    ],
                    [
                        'text' => 'Billing',
                        'route'  => 'admin.billing.index',
                        'icon' => 'fas fa-fw fa-file-invoice-dollar',
                    ]
                );
            } elseif ($role === 'doctor') {
                $event->menu->add(
                    ['header' => 'DOCTOR PORTAL'],
                    [
                        'text' => 'Appointments',
                        'route'  => 'doctor.appointments',
                        'icon' => 'fas fa-fw fa-calendar-alt',
                    ],
                    [
                        'text' => 'Prescriptions',
                        'route'  => 'doctor.prescriptions',
                        'icon' => 'fas fa-fw fa-prescription',
                    ]
                );
            } elseif ($role === 'patient') {
                $event->menu->add(
                    ['header' => 'PATIENT PORTAL'],
                    [
                        'text' => 'Book Appointment',
                        'route'  => 'patient.book-appointment',
                        'icon' => 'fas fa-fw fa-calendar-plus',
                    ],
                    [
                        'text' => 'Medical History',
                        'route'  => 'patient.medical-history',
                        'icon' => 'fas fa-fw fa-file-medical',
                    ],
                    [
                        'text' => 'Prescriptions',
                        'route'  => 'patient.prescriptions',
                        'icon' => 'fas fa-fw fa-pills',
                    ],
                    [
                        'text' => 'Billing',
                        'route'  => 'patient.billing',
                        'icon' => 'fas fa-fw fa-file-invoice',
                    ],
                    [
                        'text' => 'Symptom Checker',
                        'route'  => 'patient.symptom-checker',
                        'icon' => 'fas fa-fw fa-stethoscope',
                    ]
                );
            } elseif ($role === 'lab') {
                $event->menu->add(
                    ['header' => 'LABORATORY'],
                    [
                        'text' => 'Lab Tests',
                        'route'  => 'lab.tests',
                        'icon' => 'fas fa-fw fa-flask',
                    ]
                );
            } elseif ($role === 'pharmacy') {
                $event->menu->add(
                    ['header' => 'PHARMACY'],
                    [
                        'text' => 'Prescriptions',
                        'route'  => 'pharmacy.prescriptions',
                        'icon' => 'fas fa-fw fa-pills',
                    ]
                );
            } elseif ($role === 'reception') {
                $event->menu->add(
                    ['header' => 'RECEPTION DESK'],
                    [
                        'text' => 'Appointments',
                        'route'  => 'reception.appointments',
                        'icon' => 'fas fa-fw fa-calendar-alt',
                    ],
                    [
                        'text' => 'Patients',
                        'route'  => 'reception.patients',
                        'icon' => 'fas fa-fw fa-users',
                    ]
                );
            }
        });
    }
}
