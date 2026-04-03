<!DOCTYPE html>
<html>
<head>
    <title>Дашборд</title>
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать!</h1>
        <p>Вы успешно авторизовались.</p>
        <a href="/api/fetch-data">Загрузить данные с API</a>
        <form action="/logout" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Выйти</button>
        </form>
    </div>
</body>
</html>
