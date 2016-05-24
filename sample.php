<?php

$url = $_GET["url"];

$url = urldecode($url);

// エラーのチェック
$error = "";

if($url != ""){

  // エントリー情報を取得するURL
  $entry = "http://cloud.feedly.com/v3/search/feeds?query=" . urlencode($url);

  // JSONデータを取得してオブジェクトに変換
  $json = file_get_contents($entry);

  // 取得したJSONをオブジェクトに変換する
  $obj = json_decode($json, true);

  // エラー判定
  if($obj === NULL){
    $error = "JSONデータがありませんでした…。";
  }elseif(!isset($obj['results'][0]['title']) || !isset($obj['results'][0]['subscribers']) || !isset($obj['results'][0]['website'])){
    $error = "エントリー情報を取得できませんでした…。";

  }else{
    // HTML
    $html = "";

    // タイトル
    $title = $obj['results'][0]['title'];

    // タグ
    $tag = $obj['results'][0]['deliciousTags'];
    array($tag);
    if(count($tag) > 0){
      for($i = 0; $i < count($tag); $i++){
        $tags .= "<span class=\"label label-primary\">" . $tag[$i] . "</span> ";
      }
      $tags = "<p><i class=\"fa fa-tags\" aria-hidden=\"true\"></i> " . trim($tags) . "</p>";
    }

    // URL
    $url = $obj['results'][0]['website'];
    if($url != ""){
      $exlink = "<p><a class=\"btn btn-default\" href=\"" . $url . "\" target=\"_blank\"><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i> " . $url . "</a></p>";
    }

    // 更新日
    $lastUpdated = $obj['results'][0]['lastUpdated'];

    // feedId
    $feedid = $obj['results'][0]['feedId'];
    $feedid = urlencode($feedid);

    // language
    $language = $obj['results'][0]['language'];
    if($language != ""){
      $language = "<p><i class=\"fa fa-language\" aria-hidden=\"true\"></i> " . $language . "</p>";
    }

    // スクリーンショット画像のURL
    $image = $obj['results'][0]['visualUrl'];
    if($image != ""){
      $image = "<img src=\"" . $image . "\" class=\"img-responsive img-responsive-overwrite img-thumbnail\"><br>";
    }

    // 詳細
    $description = $obj['results'][0]['description'];

    // 購読者数
    $count = $obj['results'][0]['subscribers'];

  }

}

// エラー
if($error != ""){

  $msg = <<< EOM
     <div class="bs-component">
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Oh snap!</strong> <span class="alert-link">{$error}</span> Try submitting again.
        </div>
      </div>
EOM;

}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Feedlyの購読者数を調べるツールです。This tool is to check the number of subscribers of a feed in Feedly.">
  <meta name="keywords" content="feedly,tool,subscriber,count">
  <title>FeedlyのRSS登録者数を調べるツールです - Feedlyお役立ちツール</title>
  <link rel="shortcut icon" href="./favicon.ico">
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/odometer/0.4.7/themes/odometer-theme-minimal.css">
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>
  <style type="text/css">
  body { padding-top: 80px; }
  @media ( min-width: 768px ) {
    #banner {
      min-height: 300px;
      border-bottom: none;
    }
    .bs-docs-section {
      margin-top: 8em;
    }
    .bs-component {
      position: relative;
    }
    .bs-component .modal {
      position: relative;
      top: auto;
      right: auto;
      left: auto;
      bottom: auto;
      z-index: 1;
      display: block;
    }
    .bs-component .modal-dialog {
      width: 90%;
    }
    .bs-component .popover {
      position: relative;
      display: inline-block;
      width: 220px;
      margin: 20px;
    }
    .nav-tabs {
      margin-bottom: 15px;
    }
    .stylish-input-group .input-group-addon{
      background: white !important; 
    }
    .stylish-input-group .form-control{
      border-right:0; 
      box-shadow:0 0 0; 
      border-color:#ccc;
    }
    .stylish-input-group button{
      border:0;
      background:transparent;
    }
    .img-responsive-overwrite{
      margin: 50px auto;
    }
    .odometer {
      font-size: 100px;
    }
  }
  /* ======= Footer ======= */
  .footer {
    padding: 15px 0;
    background: #f3d4df;
    color: #000000;
  }
  .footer .copyright {
    -webkit-opacity: 0.8;
    -moz-opacity: 0.8;
    opacity: 0.8;
  }
  .footer .fa-heart {
    color: #fb866a;
  }
  li.facebook-like {
    margin-top: 0;
    position: relative;
    top: -5px;
  }
  </style>

  <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body>

<div class="container">

  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-lg-12">

    <h1 class="text-center">
    <a href="./sample.php"><i class="fa fa-rss-square" style="color:#ff8c00;"></i> feedly subscribers count</a>
    </h1>

    <br><br>

    <p class="text-center">Please <a href="https://github.com/s0323861/feedly_subscribers_count">report bugs and send feedback</a> on GitHub.</p>

    </div>

  </div>

  <div class="row">

    <div class="col-sm-8 col-sm-offset-2 text-center">

      <form action="./sample.php" method="get" class="form-horizontal" id="myForm">

      <div class="input-group stylish-input-group">
        <input type="text" class="form-control input-lg" placeholder="URLを入力してください。" name="url" value="<?php echo $url; ?>">
        <span class="input-group-addon">
          <button type="submit">
            <span class="glyphicon glyphicon-search"></span>
          </button>
        </span>
      </div>
      <span class="help-block pull-left">(例) <a href="./feedly_subscribers_count.php?url=http://www.hungryrunnergirl.com/">http://www.hungryrunnergirl.com/</a>、<a href="./feedly_subscribers_count.php?url=http://ibaya.hatenablog.com/">http://ibaya.hatenablog.com/</a></span>
      </form>

    </div>

  </div>

  <div class="row">

    <div class="col-sm-8 col-sm-offset-2 text-center">

<?php
if($url != "" and $error == ""){
echo <<< EOM
      <h1>{$title}</h1>
      {$image}
      <div id="odometer" class="odometer">0</div>

      <hr>

      <p>{$description}</p>
      {$exlink}
      {$language}
      {$tags}

      <p><a href='http://cloud.feedly.com/#subscription{$feedid}'  target='blank'><img id='feedlyFollow' src='http://s3.feedly.com/img/follows/feedly-follow-rectangle-volume-medium_2x.png' alt='follow us in feedly' width='71' height='28'></a></p>

    </div>

  </div>

  <div class="row">

EOM;

}elseif($error != ""){

echo $msg;

echo <<< EOM

    </div>

EOM;

}else{
echo <<< EOM

    </div>

EOM;
}
?>

  </div>

  <div class="row">

    <div class="col-sm-8 col-sm-offset-2 text-center">

    <hr>

    </div>

  </div>


</div>

<footer class="footer">
  <div class="container text-center">
  <small class="copyright">Developed with <i class="fa fa-heart"></i> by <a href="http://tsukuba42195.top/">Akira Mukai</a></small>
  </div>
</footer>
<!-- /footer -->



<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/odometer/0.4.7/odometer.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>

<script type="text/javascript">
  $('[data-toggle="tooltip"]').tooltip();

<?php
if($url != "" and $error == ""){
echo <<< EOM
  setTimeout(function(){
      odometer.innerHTML = {$count};
  }, 1000);
EOM;
}
?>

</script>

</body>
</html>
