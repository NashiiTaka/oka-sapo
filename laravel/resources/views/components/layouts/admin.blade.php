<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel=”icon” href="./favicon.ico">
    <title><?= $title ?? 'おかサポ' ?></title>
    @vite('resources/css/app.css')
</head>
{{ $slot }}

</html>
