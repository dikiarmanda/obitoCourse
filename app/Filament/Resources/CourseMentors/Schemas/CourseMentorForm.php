<?php

namespace App\Filament\Resources\CourseMentors\Schemas;

use App\Models\User;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class CourseMentorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->label('Mentor')
                    // ambil user dengan role mentor data nama dan id saja
                    ->options(fn() => User::role('mentor')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('about')
                    ->required(),
                Select::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->required(),
            ]);
    }
}
