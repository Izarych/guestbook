<?php

namespace App\Services;

use App\Models\Captcha;
use App\Models\GuestBook;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class GuestBookService
{
    public function generateCaptcha(): array
    {
        $key = Str::random(32);

        $answer = Str::random(5);

        $img = Image::canvas(150, 50, '#ccc');

        $fontColor = $this->generateRandomColor();

        $img->text($answer, 75, 25, function ($font) use ($fontColor) {
            $font->file(public_path('fonts/captcha.ttf'));
            $font->color($fontColor);
            $font->size(45);
            $font->align('center');
            $font->valign('middle');
        });

        $hashKey = md5($key);

        $captchasDir = public_path("captchas");

        if (!file_exists($captchasDir)) {
            mkdir($captchasDir, 0755, true);
        }

        $imgPath = $captchasDir . DIRECTORY_SEPARATOR . $hashKey . ".png";
        $img->save($imgPath);

        $captcha = new Captcha([
            'key' => $key,
            'answer' => $answer
        ]);

        $captcha->save();

        // to get img on localhost
        $captchaImg = url('captchas' . DIRECTORY_SEPARATOR . $hashKey . '.png');
        return [
            'captcha_image' => $captchaImg,
            'captcha_key' => $key
        ];
    }

    public function createGuestBook(array $data): GuestBook|array
    {
        $captchaKey = $data['captchaKey'];
        $name = $data['name'];
        $captchaAnswer = $data['captchaAnswer'];

        $captcha = Captcha::where('key', $captchaKey)->first();

        if (! ($captchaAnswer === $captcha->answer) ) {
            return [
                'message' => 'Неверно введена капча',
                'status' => 400
            ];
        }

        $guestBook = new GuestBook();

        $guestBook->name = $name;

        if (! $guestBook->save()) {
            Log::error('Произошла ошибка при сохранении GuestBook' . $guestBook);

            return [
                'message' => 'Произошла ошибка',
                'status' => 500
            ];
        }

        if (! $captcha->delete()) {
            Log::error('Произошла ошибка при удалении капчи' . $captcha);

            return [
                'message' => 'Произошла ошибка',
                'status' => 500
            ];
        }

        $captchaPath = public_path("captchas\\" . $captchaKey . ".png");

        if (File::exists($captchaPath)) {
            File::delete($captchaPath);
        }

        return $guestBook;
    }

    private function generateRandomColor(): string
    {
        $colors  = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#00FFFF', '#FF00FF', '#000000', '#FFFFFF'];
        return $colors[array_rand($colors)];
    }
}
