function showAlertFromUrl()
{
    // Create hash from window.location.search (?key=value) 
    const urlParams = new URLSearchParams(window.location.search);
    // Get value from key error or msg
    const param = urlParams.get('error') || urlParams.get('msg');
    
    // show alert popup if a value was found and display it
    if (param) {
        alert(param);
        // Based on: https://stackoverflow.com/a/22753103
        // Get the url without the search string and replace it in url-bar and history
        window.history.pushState("", "", window.location.href.split("?")[0]);
    }
}

// Call function onload
window.onload = showAlertFromUrl;
