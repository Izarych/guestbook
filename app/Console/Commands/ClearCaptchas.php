<?php

namespace App\Console\Commands;

use App\Models\Captcha;
use Illuminate\Console\Command;

class ClearCaptchas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captchas:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очистка капчи из базы';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Captcha::where('created_at', '<', now()->subHour())->delete();

        $this->info('Капчи были удалены из базы');
    }
}
