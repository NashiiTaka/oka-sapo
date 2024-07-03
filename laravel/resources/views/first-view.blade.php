<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/img/title.png">
    <title>コスメ★ピシャットピシャット</title>
    <style>
        body {
            text-align: center;
            background: #F8A1BB;
        }

        .center-image {
            display: flex;
            justify-content: center;
            opacity: 0;
            animation: fadeIn 3s forwards;
        }

        .center-image img {
            width: 80%;
            height: auto;
        }

        .introduction {
            width: 85%;
            margin: 0 auto;
            text-align: center;
            color: #625672;
        }

        .introduction1 {
            opacity: 0;
            animation: fadeIn 6s forwards;
        }

        .introduction2 {
            opacity: 0;
            animation: fadeIn 6s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #8b378b; /* 紫色 */
            color: white;
            border: none;
            border-radius: 15px; /* 角を丸く */
            cursor: pointer;
            font-size: 12px;
            width: 60px;
        }
    </style>
</head>
<body>
<div class="center-image">
<img src="/img/title.png" alt="" >
</div>
<div class="introduction">
    <div class="introduction1">
        <p>「コスメ・ピシャット」は、忙しい女性のための口紅選びをサポートするサービスです。</p>
    </div>
    <div class="introduction2">
        <p>パーソナルカラーや好みを分析し、<br>あなたにぴったりの口紅を見つけ出す<br>お手伝いをします。</p>
    </div>
</div>

<a href="/chat/index" class="button">
    HOME
</a>
</body>
</html>