<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';

    /**WebAppsの環境変数から、DB接続文字を取得する。※ソースが怪しいので今はコメントアウト */
    //変数の宣言
    $connectstr_dbhost = ”;
    $connectstr_dbname = ”;
    $connectstr_dbusername = ”;
    $connectstr_dbpassword = ”;

    //foreach ($_SERVER as $key => $value) {
    //    if (strpos($key, “MYSQLCONNSTR_localdb”) !== 0) {
    //        continue;
    //    }

    //    $connectstr_dbhost = preg_replace(“/^.*Data Source=(.+?);.*$/”, “\\1“, $value);
    //    $connectstr_dbname = preg_replace(“/^.*Database=(.+?);.*$/”, “\\1“, $value);
    //    $connectstr_dbusername = preg_replace(“/^.*User Id=(.+?);.*$/”, “\\1“, $value);
    //    $connectstr_dbpassword = preg_replace(“/^.*Password=(.+?)$/”, “\\1“, $value);
    //}
    /** データベース名 */
     //$connectstr_dbname
    /** 接続ユーザー名 */
     //$connectstr_dbusername
    /** 接続パスワード */
     //$connectstr_dbpassword
    /** ホスト・ポート名 */
     //$connectstr_dbhost


    /**WebAppsの環境変数から、DB接続文字を取得する。※信じられそうなDB接続文字列 */ 
     if(!isset($_SERVER["MYSQLCONNSTR_localdb"])) throw new Exception('MYSQLCONNSTR ENV is not defined');

     $azure_mysql_connstr = $_SERVER["MYSQLCONNSTR_localdb"];
     $azure_mysql_connstr_match = preg_match(
       "/".
         "Database=(?<database>.+);".
         "Data Source=(?<datasource>.+):".
         "(?<port>.+);".
         "User Id=(?<userid>.+);".
         "Password=(?<password>.+)".
       "/u",
       $azure_mysql_connstr,
       $_);
     
     if($azure_mysql_connstr_match === false) throw new Exception('Could not parse for MYSQLCONNSTR ENV');
     
     $db_settings = [
         'host'      => $_['datasource'],
         'port'      => $_['port'],
         'database'  => $_['database'],
         'username'  => $_['userid'],
         'password'  => $_['password'],
     ];
     
     var_dump($db_settings);

    $connectstr_dbhost = $_['datasource'].':'.$_['port'];
    $connectstr_dbname = $_['database'];
    $connectstr_dbusername = $_['userid'];
    $connectstr_dbpassword = $_['password'];
    /** ここまで */
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $message = $_POST['message'];

    //DBへのINSERT
    //PDOを使ってDBに接続
    $dbh = new PDO('mysql:host='.$connectstr_dbhost.';dbname='.$connectstr_dbhost, $connectstr_dbusername, $connectstr_dbpassword);
//    $dbh = new PDO('mysql:host='.$connectstr_dbhost.';dbname='.$connectstr_dbhost, $connectstr_dbusername, $connectstr_dbpassword);
    //エラーがある場合に表示させるようにする
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //insert文を実行する準備
    $stmt = $dbh->prepare('insert into contacts(
        name,
        email,
        gender,
        message
    ) values(
        :name,
        :email,
        :gender,
        :message
    )');
    //insert文の各パラメータ（:がついてるパラメータ）にbindParamでconfirm.phpから取得した値を代入する
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':message', $message);
    //insertを実行
    $stmt->execute();

    //メール送信
    $mail = new PHPMailer(true);

    try{

        //server
        $mail->SMTPDebug = 0; //デバッグする場合は2とかにする。
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '7cc629ab4be7f9'; //ここにusername入れる
        $mail->Password = 'a923353f7eddad'; //ここにpassword入れる
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;
        
        //Recipients
        $mail->setFrom('from@example.com', 'Mailer'); 
        $mail->addAddress($email, $name);

        //Content
        $mail->isHTML(true); //HTMLで表示
        $mail->CharSet = 'UTF-8'; //文字化け防止

            //メールの内容を作成
        $mail_body ='<h1>'.$name.'さま</h1></p><p>お問い合わせありがとうございます。</p><p>後日担当者よりご連絡いたします。</p>';
        $mail_body.='<br>';
        $mail_body.="-----------------------------------";
        $mail_body.='<p>お問い合わせ内容：'.$message.'</p>';
        $mail_body.="-----------------------------------";
        $mail_body.='<br>';
        $mail_body.='<p>このメールに心当たりがない場合は破棄してください。</p>';

        $mail->Subject = 'お問い合わせありがとうございます'; //タイトル
        $mail->Body = $mail_body; //mail_bodyを設定

        //送信する
        $mail->send();

    }catch(Exception $e){
        echo "error:".$mail->ErrorInfo;
    }        
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Form</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <nav>
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="">Service</a></li>
                <li><a href="">About</a></li>
                <li><a href="index.html">Contact us</a></li>
            </ul>
        </nav>
        <header>
            <h1 id="main-title">Sample Web Form</h1>
        </header>
        <main>
            <section class="form-section">
                <h2>お問合せを受付ました（3/3）</h2>
                <div class="form-container">
                    <p class="thankyou-message">ありがとうございました。</p>
                    <p>以下の内容を受け取りました。</p>
                    <p><?php print_r($_POST); ?></p>
                </div>
            </section>
        </main>
        <footer>
            &copy 20xx Sample corporation.
        </footer>
    </body>
</html>