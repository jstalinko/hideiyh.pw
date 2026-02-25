<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlowResource\Pages;
use App\Filament\Resources\FlowResource\RelationManagers;
use App\Models\Flow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Helpers\Helper;

class FlowResource extends Resource
{
    protected static ?string $model = Flow::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrows-right-left';
    protected static ?string $navigationGroup = 'Integration';
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // Custom filtering conditions
            ->when(
                auth()->user()->hasRole('super_admin'),
                fn($query) => $query, // No restrictions for admins
                fn($query) => $query->where('user_id', auth()->id()) // Scope for non-admin users
            )
            // Additional custom filters
            ->orderByDesc('created_at');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Settings')->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Flow name')
                        ->helperText('Nama Untuk Flow ini, misalnya "Flow 1" atau "Flow 2"')
                        ->columnSpan(2),
                    Forms\Components\Select::make('render_white_page')
                        ->options([
                            'header_redirect' => '302 Redirect',
                            'meta_redirect' => 'HTML Redirect',
                            'script_redirect' => 'JS Redirect',
                            'local_file' => 'Local File include',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('white_page_url')
                        ->required()
                        ->label('White Page URL Or File Path'),
                    Forms\Components\Select::make('render_bot_page')
                        ->options([
                            'header_redirect' => '302 Redirect',
                            'meta_redirect' => 'HTML Redirect',
                            'script_redirect' => 'JS Redirect',
                            'local_file' => 'Local File include',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('bot_page_url')
                        ->required()
                        ->label('Bot Page URL Or File Path'),
                    Forms\Components\Select::make('render_offer_page')
                        ->options([
                            'header_redirect' => '302 Redirect',
                            'meta_redirect' => 'HTML Redirect',
                            'script_redirect' => 'JS Redirect',
                            'local_file' => 'Local File include',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('offer_page_url')
                        ->required()
                        ->label('Offer Page URL Or File Path'),
                ])->columns(2),

                Forms\Components\Section::make('Targeting')->schema([
                    Forms\Components\Select::make('allowed_countries')
                        ->options(Helper::countryList())
                        ->default(null)
                        ->multiple()
                        ->helperText('Biarkan kosong untuk mengizinkan di semua negara'),
                    Forms\Components\Select::make('lock_device')
                        ->options([
                            'desktop' => 'Desktop',
                            'mobile' => 'Mobile',
                            'tablet' => 'Tablet',
                        ])
                        ->default(null)
                        ->multiple()
                        ->helperText('Biarkan kosong untuk mengizinkan di semua perangkat'),
                    Forms\Components\Select::make('lock_browser')
                        ->options([
                            'chrome' => 'Chrome',
                            'firefox' => 'Firefox',
                            'safari' => 'Safari',
                            'edge' => 'Edge',
                            'opera' => 'Opera',
                            'FBBrowser' => 'FB Browser',
                        ])
                        ->default(null)
                        ->multiple()
                        ->helperText('Biarkan kosong untuk mengizinkan di semua browser'),
                    Forms\Components\Textarea::make('lock_isp')
                        ->columnSpanFull()
                        ->helperText('Biarkan kosong untuk mengizinkan di semua ISP, isi list ISP line per line.'),
                    Forms\Components\Textarea::make('lock_referers')
                        ->columnSpanFull()
                        ->helperText('Biarkan kosong untuk mengizinkan di semua referer, isi list referer domain atau host line per line.'),

                ])->columns(3),


                Forms\Components\Section::make('Security')->schema([
                    Forms\Components\Toggle::make('block_vpn')
                        ->required()
                        ->default(true)
                        ->helperText('Memblokir request visitor yang terdeteksi menggunakan VPN atau Proxy'),
                    Forms\Components\Toggle::make('block_no_referer')
                        ->required()
                        ->default(false)
                        ->helperText('Memblokir request visitor tanpa referer / direct visit'),
                    Forms\Components\Toggle::make('allowed_params')
                        ->required()
                        ->default(false)
                        ->helperText('Meneruskan URL Parameter ke Offer URL (Target)'),
                    Forms\Components\Toggle::make('acs')
                        ->required()
                        ->label('Ad-Iyh Cloaking Signature')
                        ->helperText('Mengaktifkan fitur hidden signature, cocok jika offer URL adalah menggunakan slug url dari plugin AD-IYH .')
                        ->default(false),
                    Forms\Components\Toggle::make('blocker_bots')
                        ->required()
                        ->default(false)
                        ->helperText('Memblokir request visitor yang terdeteksi BOT/Crawler'),
                    Forms\Components\Toggle::make('active')
                        ->required()
                        ->default(true)
                        ->helperText('Aktifkan flow ini'),
                ])->columns(2),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('render_white_page')
                    ->searchable(),
                Tables\Columns\TextColumn::make('white_page_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('render_bot_page')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bot_page_url')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('render_offer_page')
                    ->searchable(),
                Tables\Columns\TextColumn::make('offer_page_url')
                    ->searchable(),
                Tables\Columns\IconColumn::make('block_vpn')
                    ->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('block_no_referer')
                    ->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('allowed_params')
                    ->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('acs')
                    ->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('blocker_bots')
                    ->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lock_device')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lock_browser')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-s-arrow-down-circle')
                    ->color('success')
                    ->url(fn(Flow $record): string => '/dl-flow/' . $record->uniqid),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFlows::route('/'),
            'create' => Pages\CreateFlow::route('/create'),
            'view' => Pages\ViewFlow::route('/{record}'),
            'edit' => Pages\EditFlow::route('/{record}/edit'),
            'success' => Pages\SuccessFlow::route('/{record}/success'),
        ];
    }
}
