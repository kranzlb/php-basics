<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Eventlistener -->
    <script>
            document.addEventListener("DOMContentLoaded",()=>{
                const click = document.querySelectorAll("idOderName");
				for(let i=0; i<click.length; i++) {
					click[i].addEventListener("click",(ev) => {
                        //Eventausgabe in Konsole
                        console.log(ev);
                        //mit data-"name" festlegen welche daten hinterlegt werden
						let daten= ev.srcElement.attributes["data-name"].nodeValue;
                        //Wertausgabe in Konsole
                        console.log(daten);
                        //hidden input setzen
                        document.querySelector("[name=hiddeninput1]").value = daten;
                        //Formular abschicken um Daten an SV zu senden
                        document.querySelector("#form").submit();
				    });  
                }
            });

        </script>
</head>
<body>
    
</body>
</html>