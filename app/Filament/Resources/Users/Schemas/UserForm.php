<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('ic_number'),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('address')
                    ->columnSpanFull(),
                DatePicker::make('date_of_birth'),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female']),
                TextInput::make('role')
                    ->required()
                    ->default('staff'),
                TextInput::make('department'),
                TextInput::make('position'),
                DatePicker::make('hire_date'),
                TextInput::make('salary')
                    ->numeric(),
                Select::make('employment_status')
                    ->options([
            'active' => 'Active',
            'inactive' => 'Inactive',
            'blocked' => 'Blocked',
            'terminated' => 'Terminated',
        ])
                    ->default('active')
                    ->required(),
                TextInput::make('emergency_contact_name'),
                TextInput::make('emergency_contact_relationship'),
                TextInput::make('emergency_contact_phone')
                    ->tel(),
                TextInput::make('profile_photo'),
                TimePicker::make('custom_duty_start_time'),
                TimePicker::make('custom_work_start_time'),
                TimePicker::make('custom_work_end_time'),
                TextInput::make('custom_work_hours')
                    ->numeric(),
                TextInput::make('custom_break_minutes')
                    ->numeric(),
                Toggle::make('is_flexible_schedule')
                    ->required(),
                Textarea::make('schedule_notes')
                    ->columnSpanFull(),
                DateTimePicker::make('last_login'),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}
