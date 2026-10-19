
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/login.css?v=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque&family=Cabin:wght@500&family=Josefin+Sans&family=Lato&family=Montserrat&family=Odibee+Sans&family=Pixelify+Sans&family=Tilt+Neon&display=swap"
        rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <section class="container">
        <div class="login-container">
            <div class="login-background">
                <video autoplay loop muted>
                    <source type="video/mp4" src="../assets/video_background.mp4">
                </video>
            </div>
            <div class="login-form">
                <div class="login-title">Tính Tiền Điện</div>
                <form action="" method="post">
                    <h4>Chào mừng đến với website tính tiền điện</h4>
                    <div class="login-side">
                        <div class="login-input">
                            <label for="username">Tên đăng nhập:</label>
                            <input type="text" name="username" placeholder="Tên đăng nhập" required>
                        </div>
                        <div class="login-input password-show_hide">
                            <label for="username" class="label">Mật khẩu:</label>
                            <input type="password" name="password" id="password" placeholder="Mật khẩu" required
                                class="pass">
                            <span class="show-btn"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <button type="submit" name="dangnhap" class="submit">Đăng nhập</button>

                </form>
            </div>
        </div>
    </section>
</body>

</html>
<script>
    const passField = document.getElementById("password");
    const showBtn = document.querySelector("span i");
    showBtn.onclick = (() => {
        if (passField.type === "password") {
            passField.type = "text";
            showBtn.classList.add("hide-btn");
        } else {
            passField.type = "password";
            showBtn.classList.remove("hide-btn");
        }
    });
</script>