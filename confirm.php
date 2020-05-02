<?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $message = $_POST['message'];
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
                <li><a href="">Contact us</a></li>
            </ul>
        </nav>
        <header>
            <h1 id="main-title">Sample Web Form</h1>
        </header>
        <main>
            <section class="form-section">
                <h2>お問合せ内容の確認（2/3）</h2>
                <div class="form-container">
                    <form id="form">
                        <div class="form-group">
                            <label>氏名</label>
                            <span class="confirm-item"><?php echo $name; ?></span>
                            <input type="hidden" name="name" value="<?php echo $name; ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <span class="confirm-item"><?php echo $email; ?></span>
                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                        </div>
                        <div class="form-group">
                            <label>性別</label>
                            <span class="confirm-item"><?php echo $gender; ?></span>
                            <input type="hidden" name="gender" value="<?php echo $gender; ?>">
                        </div>
                        <div class="form-group">
                            <label>お問合せ内容</label>
                            <span class="confirm-item"><?php echo $message; ?></span>
                            <input type="hidden" name="message" value="<?php echo $message; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" id="next">上記内容で問合せる</button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
        <footer>
            &copy 20xx Sample corporation.
        </footer>
        <script>
            var button = document.getElementById("next");
            button.addEventListener("click",function(e){
                e.preventDefault();
                form.method = "post";
                form.action = "thankyou.php"
                form.submit();
            })
        </script>
    </body>
</html>