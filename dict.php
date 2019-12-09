<?PHP

include('functions.php');

$min = 0;
$max = 0;
$rand = 0;
$num = 5;
$ids = [];

$pdo = connectToDb();

//レコード数を取得し、ランダムに１レコードのidを取得する
$sql = 'SELECT COUNT(*) AS CNT FROM `800BasicEnglishJapanese`';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
  showSqlErrorMsg($stmt);
} else {
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // var_dump($result['CNT']);
    $max = $result['CNT'];

    //レコード数から指定の数のレコードidを取得
    $random = range($min, $max);
    shuffle($random);
    for ($i = 0; $i <= $num - 1; $i++) {
      $ids[$i] = $random[$i];
    }
    // var_dump($ids);
    // exit();
  }
}

//ランダムに取り出した単語のレコードを取得する
$pdo = connectToDb();

$str = '';
$count = json_encode(count($ids));
// $description = '';
for ($i = 0; $i < count($ids); $i++) {
  $sql = 'SELECT * FROM `800BasicEnglishJapanese` WHERE id=' . $ids[$i];
  $stmt = $pdo->prepare($sql);
  $status = $stmt->execute();

  if ($status == false) {
    showSqlErrorMsg($stmt);
  } else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $str .= '<li class="list-group-item">';
      $str .= '<h3 id=word-' . $i . '>' . $result['word'] . '</h3>';
      $str .= '<div class="description" id=description-' . $i . '>' . $result['description'] . '</div>';
      $str .= '<div class="btn btn1" id=japanese-' . $i . '><i class="fas fa-language"></i></div><div class="btn btn2" id=Pronunciation-' . $i . '><i class="fas fa-microphone"></i></div>';
      $str .= '</li>';
    }
  }
  // var_dump($view);

}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>単語リスト</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/main.css">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }

    .description {
      display: none;
    }
  </style>
</head>

<body>

  <div>
    <ul class="list-group">
      <?= $str ?>
    </ul>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    //翻訳ボタン処理の関数
    function japanese(id) {
      // console.log('#description-' + id);
      $('#description-' + id).css("display", "block");
    }

    //音声ボタン処理の関数
    function pronunciation(id, word) {
      SpeechRecognition = webkitSpeechRecognition || SpeechRecognition;
      const recognition = new SpeechRecognition();
      recognition.lang = 'en-US';

      recognition.onresult = (event) => {
        // console.log(event);
        console.log(event.results[0][0].transcript);

        if (event.results[0][0].transcript == word) {
          console.log("correct");
        } else {
          console.log("wrong");
        }
      }
      recognition.start();
    }

    //英単語取得
    let word = [];
    let id = [];
    const count = JSON.parse('<?php echo $count; ?>');
    // console.log(count);

    for (let i = 0; i < count; i++) {
      // console.log($('#word-1').text());
      word.push($('#word-' + i).text());
      id.push(i);
    }
    console.log(word);

    //日本語表示
    $('#japanese-0').on('click', function() {
      // alert('ok');
      // $('.description').css("display", "block");
      japanese(0);
    });
    $('#japanese-1').on('click', function() {
      // alert('ok');
      // $('.description').css("display", "block");
      japanese(1);
    });
    $('#japanese-2').on('click', function() {
      // alert('ok');
      // $('.description').css("display", "block");
      japanese(2);
    });
    $('#japanese-3').on('click', function() {
      // alert('ok');
      // $('.description').css("display", "block");
      japanese(3);
    });
    $('#japanese-4').on('click', function() {
      // alert('ok');
      // $('.description').css("display", "block");
      japanese(4);
    });

    //発音チェック
    $('#Pronunciation-0').on('click', function() {
      pronunciation(0, word[0])
    });
    $('#Pronunciation-1').on('click', function() {
      pronunciation(1, word[1])
    });
    $('#Pronunciation-2').on('click', function() {
      pronunciation(2, word[2])
    });
    $('#Pronunciation-3').on('click', function() {
      pronunciation(3, word[3])
    });
    $('#Pronunciation-4').on('click', function() {
      pronunciation(4, word[4])
    });
  </script>
</body>

</html>