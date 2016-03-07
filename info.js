function logout(){
    if(supports_html5_storage() === true)
        localStorage['logout'] = true;

    location.href = "index.php?close_session=1";
}

function handle_storage(e) {
    if(localStorage['logout'] === "true") {
        location.href = 'index.php';
    }
}