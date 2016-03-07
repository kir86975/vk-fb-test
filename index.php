<?php
if (isset($_GET['auth']) && $_GET['auth'] == 0)
    $authMessageText = 'Ошибка авторизации. Повторите попытку';
else
    $authMessageText = 'Здравствуйте! Для входа необходима авторизация через одну из социальных сетей';

session_start();
if(isset($_GET['close_session']) && $_GET['close_session'] == 1){
    unset($_SESSION['vk_access_token']);
    unset($_SESSION['fb_access_token']);
}

if(isset($_SESSION['vk_access_token']))
    header('Location: info.php?state=vk');

if(isset($_SESSION['fb_access_token']))
    header('Location: info.php?state=fb');
?>

<?php include("header.html") ?>

<body>
    <script src="fb_sdk.js" type="text/javascript"></script>
    <script src="vk_sdk.js" type="text/javascript"></script>

    <table align="center" border="1" cellpadding="5" width="40%">
        <tr>
            <td colspan="2" align="center" id="authMessage">
                <?php echo $authMessageText ?>
            </td>
        </tr>
            <td align="center" width=50%>
               Вконтакте </br>
                <div id="login_button" onclick="vkAuth()"></div>
            </td>
            <td align="center">
                Facebook </br>
                <div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false"
                     data-auto-logout-link="false" onlogin="fbLoginEvent()" scope="public_profile, user_hometown"
                     align="center">
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

<script src="index.js" type="text/javascript"></script>

<script language="javascript">

    VK.UI.button('login_button');

    if(supports_html5_storage() === true)
    {
        localStorage['login'] = "null";

        if (window.addEventListener) {
            window.addEventListener("storage", handle_storage, false);
        } else {
            document.attachEvent("onstorage", handle_storage);
        };
    }
</script>
