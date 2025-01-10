<?php

namespace App\Filament\Exports\Attribute;

use App\Models\Attribute\Attribute;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AttributeExporter extends Exporter
{
    protected static ?string $model = Attribute::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('alterlabels')
                ->listAsJson(),
            ExportColumn::make('alterprefixes')
                ->listAsJson(),
            ExportColumn::make('altersuffixes')
                ->listAsJson(),
            ExportColumn::make('visible')
                ->listAsJson(),
            ExportColumn::make('hidden')
                ->listAsJson(),
            ExportColumn::make('create_layout')
                ->listAsJson(),
            ExportColumn::make('filter_layout')
                ->listAsJson(),
            ExportColumn::make('show_layout')
                ->listAsJson(),
            ExportColumn::make('group_layout')
                ->listAsJson(),
            ExportColumn::make('is_translatable'),
            ExportColumn::make('is_feature'),
            ExportColumn::make('is_required'),
            ExportColumn::make('is_always_required'),
            ExportColumn::make('is_active'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),

            ExportColumn::make('categories.id')
                ->listAsJson(),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attribute export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
