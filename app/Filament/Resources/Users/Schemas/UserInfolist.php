<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee_id'),
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('ic_number'),
                TextEntry::make('phone'),
                TextEntry::make('date_of_birth')
                    ->date(),
                TextEntry::make('gender'),
                TextEntry::make('role'),
                TextEntry::make('department'),
                TextEntry::make('position'),
                TextEntry::make('hire_date')
                    ->date(),
                TextEntry::make('salary')
                    ->numeric(),
                TextEntry::make('employment_status'),
                TextEntry::make('emergency_contact_name'),
                TextEntry::make('emergency_contact_relationship'),
                TextEntry::make('emergency_contact_phone'),
                TextEntry::make('profile_photo'),
                TextEntry::make('custom_duty_start_time')
                    ->time(),
                TextEntry::make('custom_work_start_time')
                    ->time(),
                TextEntry::make('custom_work_end_time')
                    ->time(),
                TextEntry::make('custom_work_hours')
                    ->numeric(),
                TextEntry::make('custom_break_minutes')
                    ->numeric(),
                IconEntry::make('is_flexible_schedule')
                    ->boolean(),
                TextEntry::make('last_login')
                    ->dateTime(),
                TextEntry::make('email_verified_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
