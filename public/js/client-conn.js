const HOST = "localhost";
const PORT = 8080;
function CARD(message, user = 'you') {
    return `<div class="card bg-dark shadow-lg mt-1">
<div class="card-body p-1">
<div class="dropdown mr-auto position-relative float-right">
<button class="btn btn-secondary dropdown-toggle"
      type="button" id="dropdownMenu1" data-toggle="dropdown"
      aria-haspopup="true" aria-expanded="false">
...
</button>
<!-- begin an if statement here to check whether the owner of the message is person viewing it, if that's the case then show this menu -->
<div class="dropdown-menu dropdown-menu-right bg-dark w-25" aria-labelledby="dropdownMenu1">
<a class="dropdown-item text-white" href="#!">Edit</a>
<a class="dropdown-item text-white" href="#!">Delete</a>
</div>
</div>
<!-- end said if statement here -->
    <h6 class="card-text text-white">${user}: </h6>
    <h6 class="card-text text-white">${message}</h6>
    <small class='text-secondary'>At [time and date]</small>
</div>
</div> `}
class SocketCLT {
    /**
     * Lets the user chat
     */
    constructor(id, username, user_id) {
        this.id = id;
        this.username = username;
        this.user_id = user_id;

        this.Socket = new WebSocket(`ws://${HOST}:${PORT}/chat`);

        //add the events
        this.Socket.onmessage = msg => {
            let json_msg = JSON.parse(msg.data);
            if (json_msg['id'] == this.id) this.addMessage(json_msg['msg'], json_msg['username']);
        }

        this.Socket.onopen = e => { this.send(`${this.username} joined the chat...`) }
        this.Socket.onclose = e => { this.send(`${this.username} the chat...`) }
    }
    addMessage(message, user = "you") {
        const BOX = document.getElementById('chat_box');
        BOX.innerHTML += CARD(message, user);
    }
    send(msg) {
        if (typeof msg == "string") {
            this.Socket.send(JSON.stringify({ 'id': this.id, 'msg': msg, 'username': this.username, 'user-id': this.user_id }));//send the message to the server
            this.addMessage(`${msg}`);//add the message to the message box
        }
    }
}