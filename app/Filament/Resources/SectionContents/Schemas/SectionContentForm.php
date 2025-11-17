<?php

namespace App\Filament\Resources\SectionContents\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SectionContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_section_id')
                    ->label('Course Section')
                    ->options(function () {
                        return \App\Models\CourseSection::with('course')
                            ->get()
                            ->mapWithKeys(function ($section) {
                                return [
                                    $section->id => $section->course ?
                                        "{$section->course->name} - {$section->name}" :
                                        "{$section->name}",
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('name')
                    ->maxLength(255)
                    ->required(),

                RichEditor::make('content')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
