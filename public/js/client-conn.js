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
</div>`
}

class SocketCLT {
    /**
     * Starts a connection with the server
     * @param {*} id
     * @param {string} username
     * @param {*} user_id
     */
    constructor(id, username, user_id) {
        //error checking
        if (typeof id == 'undefined' || typeof username == 'undefined') throw new ReferenceError("Not all parameters are filled");

        //check if host and port are the correct type
        if (typeof HOST != "string" || typeof PORT != "number") throw new TypeError(`Type of host: ${HOST} OR port: '${PORT}' is invalid`);

        this.id = id;
        this.username = username;
        this.user_id = user_id;

        //create the connection
        this.Socket = new WebSocket(`ws://${HOST}:${PORT}/chat`);

        //add the events
        this.Socket.onmessage = msg => {
            let json_msg = JSON.parse(msg.data);
            if (json_msg['id'] == this.id) this.addMessage(json_msg['msg'], json_msg['username']);
        }

        this.Socket.onopen = e => {
            this.send(`${this.username} joined the chat...`)
        }
        this.Socket.onclose = e => {
            this.send(`${this.username} the chat...`)
        }
    }

    /**
     * @param {string} message
     * @param {string} user
     */
    addMessage(message, user = "you") {
        //error checking
        if (typeof message != "string") throw new TypeError(`'${message}' is not of type string`);
        if (typeof user != "string") throw new TypeError(`'${user}' is not of type string`);

        //add the message
        const BOX = document.getElementById('chat_box');
        BOX.innerHTML += CARD(message, user);
    }

    send(msg) {
        if (typeof msg != "string") throw new TypeError(`'${msg}' is not of type string`);

        try {
            this.Socket.send(JSON.stringify({
                'id': this.id,
                'msg': msg,
                'username': this.username,
                'user-id': this.user_id
            }));//send the message to the server
            this.addMessage(`${msg}`);//add the message to the message box
        } catch (err) {
            throw new Error("Something went wrong when sending message to the server\n" + err);
        }
    }
}
