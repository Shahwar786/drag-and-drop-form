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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body,
        html {
            background-color: #fff;
            margin: 0;
            font-family: Arial, sans-serif;
            padding: 0;
            height: 100%;
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

        .input-div {
            display: grid;
            gap: 10px;
            /* Gap between grid items */
            width: 100%;
        }

        .label {
            border: 1px solid #ccc;
            /* Border for each label */
            padding: 10px;
            display: flex;
            align-items: center;
            margin-bottom: 0;
            /* No spacing between rows */
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="text"] {
            flex: 1;
            /* Take up remaining space in the label */
        }

        .icon {
            position: relative;
            right: 31px;
            top: 25%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        #addMoreBtn {
            margin-top: 10px;
        }

        .copy-icon {
            right: 35px;
            /* Adjust the margin as needed */
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


        {{-- <div id="form-buttons">
            <button onclick="showFormElements()">Add Form Element</button>
            <button onclick="previewForm()">Preview</button>
        </div> --}}
        <div id="form-elements" class="drag-div" style="display: grid;">
            <button onclick="showFormElements()">Add Form Element</button>
            <button onclick="previewForm()">Preview</button>
        </div>

        <div id="form-elements-btn" class="drag-div" style="display: none;">
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Load jQuery UI after jQuery -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function showFormElements() {
            var formElementsDiv = document.getElementById("form-elements-btn");
            var formElementsDivSingle = document.getElementById("form-elements");
            formElementsDiv.style.display = 'flex';
            formElementsDivSingle.style.display = 'none';

        }

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
            <button class="move-up-down" onclick="moveUpDown(this)" data-direction="down"><i class="fa-solid fa-up-down"></i></button>
            <button onclick="copyCard(this)"><i class="far fa-copy"></i></button>
            <button onclick="removeCard(this)"><i class="fas fa-times"></i></button>
        </div>
        <strong>${type}</strong>
        
        ${type === "Radio button" ?
            `<div class="form-group">
                    <div class="input-div" style="gap: 0px !important;" id="optionsContainer">
                        <label class="label">
                            <input type="radio" name="options" value="option1">
                            <input type="text" name="option1Text" placeholder="Type your option 1">
                            <i class="fas fa-copy icon copy-icon" onclick="copyOption(this)"></i>
                            <i class="fas fa-times icon" onclick="deleteOption(this)"></i>
                        </label>
            
                        <label class="label">
                            <input type="radio" name="options" value="option2">
                            <input type="text" name="option2Text" placeholder="Type your option 2">
                            <i class="fas fa-copy icon copy-icon" onclick="copyOption(this)"></i>
                            <i class="fas fa-times icon" onclick="deleteOption(this)"></i>
                        </label>
                    
                        <label class="label">
                            <input type="radio" name="options" value="option3">
                            <input type="text" name="option3Text" placeholder="Type your option 3">
                            <i class="fas fa-copy icon copy-icon" onclick="copyOption(this)"></i>
                            <i class="fas fa-times icon" onclick="deleteOption(this)"></i>
                        </label>
                        <button id="addMoreBtn"  onclick="addMoreOption()">Add More Option +</button>
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
    <div class="custom-control custom-radio">
        <label class="custom-control-label" for="newOption-${optionNumber}">
            <input type="radio" id="newOption-${optionNumber}" name="new-options" class="custom-control-input">
            <input type="text" class="form-control" style="width: 100%" value="Type your option">
            <span class="remove-option" onclick="removeOption(this)"><i class="fas fa-times"></i></span>
        </label> </div>`;

            container.insertBefore(newOption, container.lastElementChild);
        }

        function removeOption(option) {
            option.closest('.custom-radio').remove();
        }

        function removeCard(button) {
            var card = button.parentElement.parentElement;
            card.remove();
        }

        function moveUpDown(button) {
            var card = button.closest('.card');
            var direction = button.dataset.direction;

            if (direction === 'up') {
                var prevCard = card.previousElementSibling;
                if (prevCard) {
                    card.parentNode.insertBefore(card, prevCard);
                }
            } else if (direction === 'down') {
                var nextCard = card.nextElementSibling;
                if (nextCard) {
                    card.parentNode.insertBefore(nextCard, card);
                }
            }
        }

        function moveUpDown(button) {
            var card = button.closest('.card');

            // Toggle the direction between 'up' and 'down'
            var direction = button.dataset.direction === 'up' ? 'down' : 'up';
            button.dataset.direction = direction;

            if (direction === 'up') {
                var prevCard = card.previousElementSibling;
                if (prevCard) {
                    card.parentNode.insertBefore(card, prevCard);
                }
            } else if (direction === 'down') {
                var nextCard = card.nextElementSibling;
                if (nextCard) {
                    card.parentNode.insertBefore(nextCard, card);
                }
            }
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
        const labels = document.querySelectorAll('label');
        labels.forEach(label => {
            label.addEventListener('click', () => {
                const radio = label.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }
            });
        });

        function copyOption(icon) {
            const optionLabel = icon.closest('label');
            const newOptionLabel = optionLabel.cloneNode(true);
            optionLabel.parentElement.insertBefore(newOptionLabel, optionLabel);
        }

        function deleteOption(icon) {
            const optionLabel = icon.closest('label');
            optionLabel.remove();
        }

        function addMoreOption() {
            const optionsContainer = document.getElementById('optionsContainer');
            const newLabel = document.createElement('label');
            newLabel.classList.add('label');
            newLabel.innerHTML = `
            <input type="radio" name="options" value="option">
            <input type="text" name="optionText" placeholder="Type your option">
            <i class="fas fa-copy icon copy-icon" onclick="copyOption(this)"></i>
            <i class="fas fa-times icon" onclick="deleteOption(this)"></i>
        `;

            optionsContainer.insertBefore(newLabel, document.getElementById('addMoreBtn'));
        }
        // Other functions remain the same
    </script>

</body>

</html>
