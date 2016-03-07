<?php
    function getData($queryURI)
    {
        $json = file_get_contents($queryURI);
        $data = json_decode($json, TRUE);
        return $data;
    }

    function makeFBQuery($fb, $query, $access_token)
    {
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get($query, $access_token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
//            echo 'Graph returned an error: ' . $e->getMessage();
//            exit;
            header('Location: index.php?close_session=1');
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
//            echo 'Facebook SDK returned an error: ' . $e->getMessage();
//            exit;
            header('Location: index.php?close_session=1');
        }
        return $response;
    }

    $state = isset($_GET['state'])?$_GET['state']:$_POST['state'];
    if(isset($state))
        if($state == 'vk')
        {
            session_start();

    //        https://api.vk.com/method/'''METHOD_NAME'''?'''PARAMETERS'''&access_token='''ACCESS_TOKEN'''
            if(isset($_SESSION['vk_access_token']))
            {
                $access_token = $_SESSION['vk_access_token'];
                $queryURI = 'https://api.vk.com/method/users.get?fields=photo_200,home_town&access_token='.$access_token;
                $data = getData($queryURI);
                $data = $data['response'][0];

                $first_name = $data['first_name'];
                $last_name = $data['last_name'];
                $photo = $data['photo_200'];
                $city = $data['home_town'];
            }
            elseif(isset($_GET['code']))
            {
                $code = $_GET['code'];

                $queryURI = 'https://oauth.vk.com/access_token?client_id=5331619&client_secret=xMzzrJVUq9FAHDbvu0xp&'.
                   'redirect_uri=http://vk-fb-test.ru/info.php&code='.$code;

                $data = getData($queryURI);
                $access_token = $data['access_token'];
                $_SESSION['vk_access_token'] = $access_token;
                header('Location: info.php?state=vk');
            }
            elseif(isset($_GET['error']) || !isset($_SESSION['vk_access_token'])) {
                header('Location: index.php?auth=0');
            }
        }
        elseif($state == 'fb')
        {
            session_start();

            if(isset($_POST['access_token'])) {
                $_SESSION['fb_access_token'] = $_POST['access_token'];
                header('Location: info.php?state=fb');
            }

            if(isset($_SESSION['fb_access_token']))
            {
                $access_token = $_SESSION['fb_access_token'];

                $fb_app_id = '1691589231089618';
                $fb_app_secret = 'af1ec1389b24e4316fe4bc885982d174';

                require_once '\vendor\facebook\php-sdk-v4\src\Facebook\autoload.php';

                $fb = new Facebook\Facebook([
                    'app_id' => $fb_app_id,
                    'app_secret' => $fb_app_secret//,
                   //'default_graph_version' => 'v2.2',
                ]);


                $response = makeFBQuery($fb, '/me?fields=first_name,last_name,hometown', $access_token);

                $first_name = $response->getGraphUser()->getFirstName();
                $last_name = $response->getGraphUser()->getLastName();
                $city = $response->getGraphUser()->getHometown()['name'];

                $response = makeFBQuery($fb, '/me/picture?type=large', $access_token);

                $graphNode = $response->getGraphNode();
                /* handle the result */

                $photo = $response->getHeaders()['Location'];
            }
            else
            {
                header('Location: index.php?auth=0');
            }
        }
        else
        {
            header('Location: index.php?auth=0');
        }
    else
    {
        header('Location: index.php?auth=0');
    }
?>

<?php include("header.html"); ?>

<body>
    <table align="center" border="1" cellpadding="5" width="30%">
        <tr>
            <td rowspan="3" align="center" id="info" width="30%">
                <img src="<?php echo $photo ?>"/>
            </td>
            <td align="center" id="name">
                <?php echo $first_name.' '.$last_name ?>
            </td>
        </tr>
        <tr>
            <td align="center" id="city">
                <?php echo 'Город: '.$city ?>
            </td>
        </tr>
        <tr>
            <td align="center" id="exit">
                <input type="button" name="" onclick="logout();" value="Выйти"/>
            </td>
        </tr>
    </table>
</body>
</html>

<script src="info.js" type="text/javascript"></script>

<script language="javascript">
    if(supports_html5_storage() === true)
    {
        localStorage['login'] = 'info.php?state=<?php echo $state ?>';

        if (window.addEventListener) {
            window.addEventListener("storage", handle_storage, false);
        } else {
            document.attachEvent("onstorage", handle_storage);
        };
    }
</script>