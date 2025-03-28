<?php

namespace App\Filament\Resources\ExamCreationResource\Pages;

use App\Filament\Resources\ExamCreationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Tables;
use Filament\Tables\Actions\Action;

class ListExamCreations extends ListRecords
{
    protected static string $resource = ExamCreationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
                ->columns([
                    TextColumn::make('name')
                        ->label('Exam'),
                    TextColumn::make('description')
                        ->label('Exam Description')
                        ->limit(20),
                    TextColumn::make('questions')
                        ->formatStateUsing(function($record){
                            return DB::table('questions')->where('exam_id', $record?->id)->count();
                        })
                        ->label('Questions Count'),
                    TextColumn::make('is_prepare_exam')
                        ->label('Can Exam')
                        ->formatStateUsing(function($state){
                            return $state ? 'true' : 'false';
                        })
                        ->badge()
                        ->icon(function($state){
                            return $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';
                        })
                        ->color(function($state){
                            return $state ? 'info' : 'danger';
                        }),
                    TextColumn::make('date')
                        ->label('Exam Date')
                        ->date(),
                    // TextColumn::make('created_at')
                    //     ->label('Exam Created')
                    //     ->date(),
                    // TextColumn::make('updated_at')
                    //     ->label('Exam Updated')
                    //     ->date(),

                ])
                ->actions([
                    Action::make('is_prepare_for_exam')
                    ->label('Complete To Exam')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Do you want to complete to exam ?') // Confirmation modal title
                    ->action(function($record){
                        if($record){
                            if(!$record?->is_prepare_exam){
                                $record->is_prepare_exam = true;
                                $record->save();

                                Notification::make()
                                    ->title('Successfully !')
                                    ->success()
                                    ->send();
                            }
                        }
                    })
                    ->hidden(function($record){
                        if($record?->is_prepare_exam){
                            return true;
                        }
                        return false;
                    })
                    ->disabled(function($record){
                        if($record?->is_prepare_exam){
                            return true;
                        }
                        return false;
                    }),
                    Tables\Actions\EditAction::make()
                        ->disabled(function($record){
                            if($record?->is_prepare_exam){
                                return true;
                            }
                            return false;
                        })
                        ->hidden(function($record){
                            if($record?->is_prepare_exam){
                                return true;
                            }
                            return false;
                        }),
                ])
                ->recordUrl(function($record){
                    if($record?->is_prepare_exam){
                        return null;
                    }
                    return route('filament.admin.resources.exam-creations.edit', [
                            'record' => $record?->id, // Use the record's ID or unique identifier
                    ]);
                });
    }
}
