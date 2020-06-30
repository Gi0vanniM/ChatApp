const HOST = "localhost";
const PORT = 8080;
class SocketCLT{
    /**
     * Lets the user chat
     */
    constructor(id, username, user_id){
        this.id = id;
        this.username = username;
        this.user_id = user_id;

        this.Socket = new WebSocket(`ws://${HOST}:${PORT}/chat`);

        //add the events
        this.Socket.onmessage = msg => {
            let json_msg = JSON.parse(msg.data);
            if(json_msg['id'] == this.id) this.addMessage(json_msg['msg']);
        }

        this.Socket.onopen = e => { this.send(`${this.username} joined the chat...`, true) }
        this.Socket.onclose = e => { this.send(`${this.username} the chat...`, true) }
    }
    addMessage(message, add_class = ''){
        const BOX = document.getElementById('chat_box');
        BOX.innerHTML += `<div class="msg ${add_class}"> ${message} </div>`;
    }
    send(msg, sys_msg = false) {
        if(typeof msg == "string") {
            this.Socket.send(JSON.stringify({'id': this.id, 'msg': `${this.username}: ${msg}`, 'user-id': this.user_id}));//send the message to the server
            if(sys_msg) this.addMessage(`${msg}`);
            else this.addMessage(`You: ${msg}`);//add the message to the message box
        }
    }
}