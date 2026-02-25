<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('demo_credentials')
                    ->label('')
                    ->content(new HtmlString(<<<'HTML'
                        <div class="rounded-lg border border-warning-200 bg-warning-50 p-4 text-sm text-warning-800 dark:border-warning-700 dark:bg-warning-950 dark:text-warning-200">
                            <p class="font-semibold mb-1">🔑 Akun Demo</p>
                            <p><span class="font-medium">Email:</span> admin@example.com</p>
                            <p><span class="font-medium">Password:</span> password</p>
                        </div>
                    HTML)),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }
}
