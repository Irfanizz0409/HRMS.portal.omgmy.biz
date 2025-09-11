<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_id')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('ic_number')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                TextColumn::make('gender'),
                TextColumn::make('role')
                    ->searchable(),
                TextColumn::make('department')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('hire_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('salary')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employment_status'),
                TextColumn::make('emergency_contact_name')
                    ->searchable(),
                TextColumn::make('emergency_contact_relationship')
                    ->searchable(),
                TextColumn::make('emergency_contact_phone')
                    ->searchable(),
                TextColumn::make('profile_photo')
                    ->searchable(),
                TextColumn::make('custom_duty_start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('custom_work_start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('custom_work_end_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('custom_work_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('custom_break_minutes')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_flexible_schedule')
                    ->boolean(),
                TextColumn::make('last_login')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
