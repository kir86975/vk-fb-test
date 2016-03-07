function addParamToPost(name, value, form)
{
    var input = document.createElement("input");
    input.setAttribute("type", "hidden");
    input.setAttribute("name", name);
    input.setAttribute("value", value);

    form.appendChild(input);
}

function fbLoginEvent()
{
    FB.getLoginStatus(function(response){
        if (response.status === 'connected') {
            // the user is logged in and has authenticated your
            // app, and response.authResponse supplies
            // the user's ID, a valid access token, a signed
            // request, and the time the access token
            // and signed request each expire
//                var uid = response.authResponse.userID;
            var accessToken = response.authResponse.accessToken;

            form = document.createElement("form");
            form.setAttribute("method", 'post');
            form.setAttribute("action", "info.php");
            form.setAttribute("style", "display: none");


            addParamToPost("access_token", accessToken, form);
            addParamToPost("state", "fb", form);

            var input = document.createElement("input");
            input.setAttribute("type", "submit");
            input.setAttribute("value", "Submit");
            input.setAttribute("id", "button");
            form.appendChild(input);
            document.body.appendChild(form);

            document.getElementById("button").click();
//                form.submit();

//            } else if (response.status === 'not_authorized') {
            // the user is logged in to Facebook,
            // but has not authenticated your app
        } else {
            // the user isn't logged in to Facebook.
            document.getElementById('authMessage').innerHTML = 'Ошибка авторизации. Повторите попытку';
        }
    })
}

function handle_storage(e) {
    if (localStorage['login'] != "null") {
        location.href = localStorage['login'];
    }
}

function vkAuth()
{
    location.href = 'https://oauth.vk.com/authorize?client_id=' + appId +
        '&redirect_uri=http://vk-fb-test.ru/info.php&display=page&state=vk';
}

