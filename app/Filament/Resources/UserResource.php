<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class UserResource extends Resource
{
    use HasPanelShield;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'User & Roles';

    protected static $PIWAPI_SECRET = "e981b0f06eb2d4934f17cf40a221191aab07076a";
    protected static $PIWAPI_ACCOUNT_ID = "1747398028019d385eb67632a7e958e23f24bd07d768272d8c0b04e";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel(),
                Forms\Components\TextInput::make('domain_quota')->numeric()->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(),
                Forms\Components\Select::make('roles')->relationship('roles', 'name')->multiple()->preload()->searchable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\BadgeColumn::make('roles.name'),
                Tables\Columns\TextColumn::make('domain_quota')
                    ->sortable()
                    ->formatStateUsing(fn($record) => $record->domain_quota . ' Domain'),
                Tables\Columns\TextColumn::make('invoice_code'),
                Tables\Columns\ToggleColumn::make('gold_member'),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\BulkAction::make('add_domain_quota')->label('Add Domain Quota')
                    ->form([
                        Forms\Components\TextInput::make('domain_quota')
                            ->label('+ Domain Quota')
                            ->required()
                            ->numeric(),
                    ])->action(function ($data, $records) {
                        $quota = intval($data['domain_quota']);
                        $notify = '';
                        foreach ($records as $record) {
                            $record->update([
                                'domain_quota' => intval($record->domain_quota + $quota)
                            ]);
                            $notify .= $record->name . ' Domain Quota Updated to ' . $record->domain_quota . ' Domain | ';
                        }
                        Notification::make('success')->title('Successfully')->body($notify)->success()->send();
                    }),

                Tables\Actions\BulkAction::make('send_whatsapp')
                    ->label('Send WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->form([
                        Forms\Components\Textarea::make('list_numbers')
                            ->label('List of Numbers')
                            ->required()
                            ->rows(10)
                            ->helperText('Each number on a new line'),

                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(5)
                            ->helperText('Message to send via WhatsApp '),
                    ])
                    ->mountUsing(function (Forms\ComponentContainer $form, \Illuminate\Support\Collection $records) {
                        // collect and join phones with newline
                        $phones = $records
                            ->pluck('phone')
                            ->filter()
                            ->implode("\n");
                        $phones = array_map(function ($phone) {
                            // remove any non-numeric characters
                            return preg_replace('/^08/', '+628', preg_replace('/\D/', '', $phone));
                        }, explode("\n", $phones));
                        $phones = implode("\n", $phones);

                        // set default value directly
                        $form->fill([
                            'list_numbers' => $phones,
                        ]);
                    })
                    ->action(function (array $data, \Illuminate\Support\Collection $records) {


                        $list_numbers = explode("\n", $data['list_numbers']);
                        $message = $data['message'];

                        $piwapi_secret = self::$PIWAPI_ACCOUNT_ID;
                        $piwapi_apikey = self::$PIWAPI_SECRET;
                        $url = "https://piwapi.com/api/send/whatsapp.bulk";
                        //  dd(implode(",", $list_numbers), $message, $piwapi_secret, $piwapi_apikey);
                        $data = [
                            "secret" => $piwapi_apikey,
                            "account" => $piwapi_secret,
                            "campaign" => "ad-iyh-bulk",
                            "recipients" => implode(',', $list_numbers),
                            "type" => "text",
                            "message" => $message
                        ];

                        // Initialize cURL session
                        $ch = curl_init();
                        // Set cURL options
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        // Execute the request
                        $response = curl_exec($ch);
                        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $json_response = json_decode($response, true);
                        // Handle the response
                        if ($http_code === 200) {
                            Notification::make('success')
                                ->title($json_response['message'])
                                ->body('Messages sent successfully to ' . count($list_numbers) . ' recipients.')
                                ->success()
                                ->send();
                        } else {
                            Notification::make('error')
                                ->title('Error')
                                ->body('Failed to send messages. HTTP Code: ' . $http_code)
                                ->error()
                                ->send();
                        }

                        // Close cURL session
                        curl_close($ch);
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
