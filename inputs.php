<?php
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form>
        <!-- Inputs    -->

        <!-- Textinput    -->
        <input type="text" name="input1" placeholder="abc">

        <!-- hidden-input    -->
        <input type="hidden" name="hidden1" value="">

        <!-- Selsect   -->
        <label>
            Selectname
            <select name="select1">
                <option value="value1">Option 1</option>
                <option value="value2">Option 2</option>
                <option value="value3">Option 3</option>
            </select>
        </label>

        <!-- Radioinput    -->
        <fieldset>
            <label>
                Radioinputs
                <label for="idradio1">Option 1</label>
                <input  type="radio" id="idradio1" name="radio1" value="value1">
                <label for="idradio1">Option 2</label>
                <input type="radio" id="idradio2" name="radio2" value="value2">
                <label for="idradio1">Option 3</label>
                <input type="radio" id="idradio3" name="radio3" value="value3">
            </label>
        </fieldset>

        <!-- Submitbutton    -->
        <button type="submit" name="button1" id="btn1" value="123">Buttonname</button>

        <!-- Submitbutton    -->
        <input type="button" name="button2" id="btn1" value="123">
    </form>
</body>
</html>