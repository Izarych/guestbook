<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGuestBookRequest;
use App\Models\Captcha;
use App\Models\GuestBook;
use App\Services\GuestBookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GuestBookController extends Controller
{

    protected GuestBookService $guestBookService;

    public function __construct(GuestBookService $guestBookService)
    {
        $this->guestBookService = $guestBookService;
    }

    public function index(): View
    {
        $guests = GuestBook::paginate(5);
        $captcha = $this->guestBookService->generateCaptcha();

        return view('guestbook.index', [
            'guests' => $guests,
            'captcha' => $captcha
        ]);
    }

    public function store(CreateGuestBookRequest $request): JsonResponse
    {
        $data = [
            'captchaAnswer' => $request->captcha_answer,
            'name' => $request->name,
            'captchaKey' => $request->captcha_key
        ];

        $guestBook = $this->guestBookService->createGuestBook($data);

        if (is_array($guestBook)) {
            return response()->json([
                'message' => $guestBook['message']
            ], $guestBook['status']);
        }

        return response()->json([
            'data' => $guestBook
        ], 201);
    }
}
