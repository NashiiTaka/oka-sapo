<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>事前アンケート</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>

  <h1>事前アンケート</h1>

  <p>あなたのパーソナルカラーは?あああああああああああ</p>

  <button id='btn1'>イエベ春</button>
  <button id='btn2'>回答２</button>
  <button id='btn3'>回答３</button>
  <button id='btn4'>回答４</button>

  <p>選択中の回答:<span id="selectAnswer"></span></p>


  <button id="next">next</button>

  <script>
    function updateSelectAnswer() {

      let answer = localStorage.getItem("answer");

      if (answer) {
        $("#selectAnswer").text(answer);
      } else {
        $("#selectAnswer").text("選択されていません");
      }
    };

    $(document).ready(function() {
      updateSelectAnswer();

      $("#btn1").on("click", function() {
        localStorage.setItem("answer", "イエベ春");
        updateSelectAnswer();
        console.log("回答１が保存されました");
      });

      $("#btn2").on("click", function() {
        localStorage.setItem("answer", "回答２");
        updateSelectAnswer();
      });

      $("#btn3").on("click", function() {
        localStorage.setItem("answer", "回答３");
        updateSelectAnswer();
      });

      $("#btn4").on("click", function() {
        localStorage.setItem("answer", "回答４");
        updateSelectAnswer();
      });

    });
  </script>

</body>

</html>
