<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Гостевая книга</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        img {
            margin-top: 10px;
            display: block;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #error {
            color: red;
            margin-top: 10px;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        p {
            margin-bottom: 5px;
            color: #333;
        }

        .guest-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 12px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
<div>
    <h1>Гостевая книга</h1>
    <form id="guestBookForm" action="{{route('guestbook.store')}}" method="post">
        @csrf
        <label for="name">Ваше имя:</label>
        <input type="text" name="name">
        <br>
        <label for="review">Введите отзыв:</label>
        <input type="text" name="review">
        <br>
        <label for="captcha">Введите код с картинки:</label>
        <br>
        <img src="{{$captcha['captcha_image']}}" alt=""/>
        <br>
        <input type="text" name="captcha_answer">
        <input type="hidden" name="captcha_key" value="{{ $captcha['captcha_key'] }}">
        <br>
        <button type="submit">Подтвердить</button>
        <p id="error"></p>
    </form>

    <h2>Записи в гостевой книге</h2>
    @foreach($guests as $guest)
        @if($guest->review)
            <div class="guest-info">
                <p>{{ ($guests->currentPage() - 1) * $guests->perPage() + $loop->index + 1  }}. {{ $guest->name }}</p>
                <p>{{ $guest->review }}</p>
            </div>
        @endif
    @endforeach

<div class="pagination">
    {{$guests->links()}}
</div>

<script>
    // with jquery and ajax will be better, but I think its not bad decision for test task
    document.getElementById('guestBookForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const form = event.target;

        fetch(form.action, {
            method: form.method,
            body: new FormData(form),
        })
            .then(response => response.json())
            .then( data => {
                if (data.message) {
                    let errorEl = document.getElementById('error');
                    errorEl.textContent = '';
                    data.message.forEach(errorMessage => {
                        errorEl.innerHTML += `<p>${errorMessage}</p>`;
                    });
                } else {
                    window.location.href = "{{ $guests->url($guests->lastPage()) }}"
                }
            })
            .catch(error => {
                console.error(error);
            })
    })
</script>
</body>
</html>
