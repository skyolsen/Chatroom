//Set username variable to stored username
document.getElementById("user").innerHTML = localStorage.getItem("username");
//set last post time to null to. This will cause feed to update on page
localStorage.setItem("lastPostTime", null);


window.onload = function ()
{
    setInterval(updateChat, 2500);
}

var messages;
$("#messageid").submit(function(e) {

    var form = $(this);
    var url = form.attr('action');
    var values = {
        'Username': localStorage.getItem("username"),
        'Message': document.getElementById("chatbox").value
    }
    $.ajax({
        type: "POST",
        url: url,
        //async: false,
        //data: form.serialize(), // serializes the form's elements.
        data: values,
        success: function(response) // post message, then retrieve. Create new P element to display message
        {
            messages = response.data;
           // alert(response);
            var generateHere = document.getElementById("chatwindow");
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key, value){
                var newElement = document.createElement('p');
                newElement.class = "text-white bg-dark";
                var tn = document.createTextNode(value.Username.toUpperCase() + ":   " + value.Message + "  <"+ value.PostTime + ">  (" + value.IP + ")");
                newElement.appendChild(tn);
                generateHere.appendChild(newElement);
                localStorage.setItem("lastPostTime", value.PostTime);
            });
            scrollFeed();
        }
    });
    e.preventDefault(); // avoid to execute the actual submit of the form.
    document.getElementById("chatbox").value = "";

});

function updateChat() {
    var recordLastPostTIme = localStorage.getItem("lastPostTime");
    var values = {
        'lastPostTime': localStorage.getItem("lastPostTime")//"Hi";
    }
    $.ajax({
        //async: false,
        type: "GET",
        url:"/message",
        data: values,
        success: function(response) // Retrieve feed if new page or new message. Create P element to display messages
        {

            var generateHere = document.getElementById("chatwindow");
            var obj = jQuery.parseJSON(response);
            $.each(obj, function(key, value){
                var newElement = document.createElement('p');
                var newElementMsg = document.createElement('p');
                if(value.Username == localStorage.getItem("username")){
                    newElement.className = ("text-white bg-dark text-right small text-secondary");
                    newElementMsg.className = ("text-white bg-dark text-right");
                    var tn = document.createTextNode(value.Username + "     <"+ value.PostTime + ">  (" + value.IP + ")");
                    var tnMsg = document.createTextNode( value.Message );
                    newElement.appendChild(tn);
                    newElementMsg.appendChild(tnMsg);
                    newElementMsg.appendChild(newElement);
                    generateHere.appendChild(newElementMsg);
                    //generateHere.appendChild(newElementMsg);

                }
                else {
                    newElement.className = ("small text-secondary");
                    var tn = document.createTextNode(value.Username + "     <"+ value.PostTime + ">  (" + value.IP + ")");
                    var tnMsg = document.createTextNode(value.Message);
                    newElement.appendChild(tn);
                    newElementMsg.appendChild(tnMsg);
                    newElementMsg.appendChild(newElement);
                    generateHere.appendChild(newElementMsg);
                    //generateHere.appendChild(newElementMsg);
                }
                localStorage.setItem("lastPostTime", value.PostTime);
            });
            if(recordLastPostTIme != localStorage.getItem("lastPostTime")){
                scrollFeed();
            }

        }

    });
}

//function to scroll to end of feed
function scrollFeed (){
    var objDiv = document.getElementById("chatwindowFrame");
    objDiv.scrollTop = objDiv.scrollHeight;

}
//-----------------------------------------------------------------------------------------Code Eval
//var = <?php echo $_GET['username']; ?>;
//document.getElementById("userLogin").value = <?php echo $_GET['username']; ?>;

