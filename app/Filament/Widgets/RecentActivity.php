<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivity extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityLog::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User'),
                Tables\Columns\TextColumn::make('action'),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Log Time'),
            ]);
    }
}
