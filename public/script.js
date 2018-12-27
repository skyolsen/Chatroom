
//localStorage['username'] = 'somestring';
if (localStorage.getItem("username") == null)
{
    document.getElementById("username").value = "Username";
}
else if(localStorage.getItem("username") != null){

    document.getElementById("username").value = localStorage.getItem("username");
    //
}

function store_user(){

    var userField = document.getElementById("username").value;
    localStorage.setItem("username", userField);

}
