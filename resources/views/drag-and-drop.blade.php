<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop Project</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">


    <style>
    body {
        background-color: #fff;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        /* height: 100vh; */
    }

    #drop-div {
        width: 70%;
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        position: relative;
        background-color: #fff;
        min-height: 350px;
        box-sizing: border-box;
    }

    #form-elements {
        display: flex;
        margin-bottom: 10px;
    }

    #drag-div {
        cursor: move;
        margin: 10px;
        padding: 10px;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 5px;
        display: flex;
        align-items: center;
    }

    .drag-div {
        border: 2px solid #ccc;
        cursor: move;
        margin: 10px;
        padding: 10px;
        /* background-color: #f0f0f0; */
        border-radius: 5px;
        display: flex;
        align-items: center;
        min-height: 150px;
    }

    #form-elements .icon {
        margin-right: 5px;
    }

    #plus-icon {
        position: absolute;
        bottom: 10px;
        right: 10px;
        font-size: 24px;
        cursor: pointer;
        display: none;
    }

    .card {
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }

    .card label {
        display: flex;
        margin-top: 10px;
    }

    .card textarea,
    .card input[type="text"],
    .card input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .card .switch {
        margin-top: 15px;
    }

    .edit-settings,
    .remove-card,
    .copy-card,
    .move-up,
    .move-down {
        margin-top: 15px;
    }
    </style>
</head>

<body>

    <div class="container">
        <div id="drop-div" ondrop="drop(event)" ondragover="allowDrop(event)">
            <p>Place form elements in this field to ask the applicant any questions, related to the application for your
                open call</p>
            <div id="plus-icon">+</div>
        </div>
        <div id="form-elements" class="drag-div">
            <div id="drag-div" draggable="true" ondragstart="drag(event)">
                <span class="icon">📝</span> Short Text
            </div>
            <div id="drag-div" draggable="true" ondragstart="drag(event)">
                <span class="icon">🔘</span> Radio button
            </div>
        </div>
    </div>

    <form id="form" action="{{ url('/save-form') }}" method="post">
        @csrf
        <!-- Add more form elements as needed -->
    </form>

    <!-- Bootstrap and Font Awesome JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
    function allowDrop(event) {
        event.preventDefault();
    }

    function drag(event) {
        var data = event.target.innerText;

        if (data.includes("Short Text")) {
            createCard("Short Text");
        } else if (data.includes("Radio button")) {
            createCard("Radio button");
        } else {
            event.dataTransfer.setData("text", data);
        }
    }

    function createCard(type) {
    var card = document.createElement("div");
    card.className = "card";
    card.innerHTML = `
        <div class="icons" style="position: absolute; top: 10px; right: 10px;">
            <button onclick="removeCard(this)"><i class="fas fa-times"></i></button>
            <button onclick="copyCard(this)"><i class="far fa-copy"></i></button>
            <button onclick="moveUp(this)"><i class="fas fa-arrow-up"></i></button>
            <button onclick="moveDown(this)"><i class="fas fa-arrow-down"></i></button>
        </div>
        <strong>${type}</strong>
        
        ${type === "Radio button" ?
            `  <div class="form-group">
            <label for="${type.toLowerCase()}-options" class="col-sm-3 col-form-label">Options:</label>
            <div class="col-sm-9" id="${type.toLowerCase()}-options-container">
                <div class="custom-control custom-radio">
                    <input type="radio" id="${type.toLowerCase()}-option1" name="${type.toLowerCase()}-options" class="custom-control-input" checked>
                    <label class="custom-control-label" for="${type.toLowerCase()}-option1">
                        <input type="text" id="${type.toLowerCase()}-option1-text" class="form-control" style="width: 100%" value="Type your option 1">
                        <span class="remove-option" onclick="removeOption(this)"><i class="fas fa-times"></i></span>
                    </label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="${type.toLowerCase()}-option2" name="${type.toLowerCase()}-options" class="custom-control-input">
                    <label class="custom-control-label" for="${type.toLowerCase()}-option2">
                        <input type="text" id="${type.toLowerCase()}-option2-text" class="form-control" style="width: 100%" value="Type your option 2">
                        <span class="remove-option" onclick="removeOption(this)"><i class="fas fa-times"></i></span>
                    </label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="${type.toLowerCase()}-option3" name="${type.toLowerCase()}-options" class="custom-control-input">
                    <label class="custom-control-label" for="${type.toLowerCase()}-option3">
                        <input type="text" id="${type.toLowerCase()}-option3-text" class="form-control" style="width: 100%" value="Type your option 3">
                        <span class="remove-option" onclick="removeOption(this)"><i class="fas fa-times"></i></span>
                    </label>
                </div>
                <div class="custom-control custom-radio" id="${type.toLowerCase()}-new-option">
                    <label class="custom-control-label" for="newOption">
                        <input type="radio" id="newOption" name="${type.toLowerCase()}-options" class="custom-control-input">
                        <input type="text" class="form-control" style="width: 90%" value="Add your option">
                        <span class="add-option" onclick="addYourOption('${type.toLowerCase()}-options-container')"><i class="fas fa-plus"></i></span>
                    </label>
                </div>
            </div>
        </div>
        <label>Edit Settings</label>

        
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="${type.toLowerCase()}-requiredSwitch">
                <label class="custom-control-label" for="${type.toLowerCase()}-requiredSwitch">Required</label>
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="${type.toLowerCase()}-sortOptionsSwitch">
                <label class="custom-control-label" for="${type.toLowerCase()}-sortOptionsSwitch">Sort options in alphabetic order</label>
            </div>
            ` :
            `<div class="form-group">
                <label for="${type.toLowerCase()}-question" class="col-form-label"><strong>Type your question here</strong></label>
                <label for="${type.toLowerCase()}-description">Type a description here (optional)</label>
                <input type="text" id="${type.toLowerCase()}-question" class="form-control">
            </div>
            <label>Edit Settings</label>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="${type.toLowerCase()}-requiredSwitch" checked>
                <label class="custom-control-label" for="${type.toLowerCase()}-requiredSwitch">Required</label>
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="${type.toLowerCase()}-maxCharactersSwitch">
                <label class="custom-control-label" for="${type.toLowerCase()}-maxCharactersSwitch">Max characters</label>
            </div>
            <input type="number" class="form-control" placeholder="50" style="width: 50%;">`
        }
    `;
    document.getElementById("drop-div").appendChild(card);

                // Enable Bootstrap Toggle for switches
                $("[data-toggle='toggle']").bootstrapToggle();
    }

    function addYourOption(containerId) {
    var container = document.getElementById(containerId);
    var optionNumber = container.getElementsByClassName('custom-radio').length + 1;

    var newOption = document.createElement("div");
    newOption.className = "custom-control custom-radio";
    newOption.innerHTML = `
        <span class="remove-option" onclick="removeOption(this)"><i class="fas fa-times"></i></span>
        <label class="custom-control-label" for="newOption-${optionNumber}">
            <input type="radio" id="newOption-${optionNumber}" name="new-options" class="custom-control-input">
            <input type="text" class="form-control" style="width: 100%" value="Add your option">
        </label>`;

    container.insertBefore(newOption, container.lastElementChild);
}

    function removeOption(option) {
    option.closest('.custom-radio').remove();
}
    function removeCard(button) {
        var card = button.parentElement.parentElement;
        card.remove();
    }

    function copyCard(button) {
        var card = button.parentElement.parentElement;
        var newCard = card.cloneNode(true);
        document.getElementById("drop-div").appendChild(newCard);
        
        // Enable Bootstrap Toggle for switches in the new card
        $(newCard).find("[data-toggle='toggle']").bootstrapToggle();
    }

    function moveUp(button) {
        var card = button.parentElement.parentElement;
        var prevCard = card.previousElementSibling;
        if (prevCard) {
            document.getElementById("drop-div").insertBefore(card, prevCard);
        }
    }

    function moveDown(button) {
        var card = button.parentElement.parentElement;
        var nextCard = card.nextElementSibling;
        if (nextCard) {
            document.getElementById("drop-div").insertBefore(nextCard, card);
        }
    }
    // Other functions remain the same
    </script>

</body>

</html>