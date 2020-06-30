<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Voeg de script toe -->
    <script src="./js/client-conn.js"></script>
    <title>Document</title>
</head>
<body>
    <script>
        <?php
            $id = 0; // de id is de id van de gebruiker
            $name = "example"; // de naam van de gebruiker
            $chat_id = "u1inu912"; // de id van de chat (waar deze user aanvast zit)
        ?>
        const SELF = new SocketCLT(<?=$chat_id?>, "<?=$name?>", <?=$id?>);
    </script>
    <div id="chat_box">
    <!-- in de div met deze id worden de berichten gezet -->
    </div>
    <!-- 
        Form:
            action: javascript:void(0); is nodig zodat er geen refresh komt (data word door de server in de database gezet)

            id: deze id's worden door de js file gebruikt
     -->
    <form id="chat_form" action="javascript:void(0);" onsubmit="SELF.send(document.getElementById('msg_box').value); ">
        <input type="text" id="msg_box">
        <button type="submit">Submit</button>
    </form>
</body>
</html>