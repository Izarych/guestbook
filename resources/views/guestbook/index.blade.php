<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Гостевая книга</title>
</head>
<body>
<h1>Гостевая книга</h1>
<form id="guestBookForm" action="{{route('guestbook.store')}}" method="post">
    @csrf
    <label for="name">Ваше имя:</label>
    <input type="text" name="name" required>
    <br>
    <label for="captcha">Введите код с картинки:</label>
    <br>
    <img src="{{$captcha['captcha_image']}}" alt=""/>
    <br>
    <input type="text" name="captcha_answer" required>
    <input type="hidden" name="captcha_key" value="{{ $captcha['captcha_key'] }}">
    <br>
    <button type="submit">Подтвердить</button>
    <p id="error" style="color:red"></p>
</form>

<h2>Записи в гостевой книге</h2>
@foreach($guests as $guest)
    <p>{{ ($guests->currentPage() - 1) * $guests->perPage() + $loop->index + 1  }}. {{ $guest->name }}</p>
@endforeach

{{$guests->links()}}

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
                    errorEl.textContent = data.message;
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
