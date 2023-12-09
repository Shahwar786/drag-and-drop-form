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
            height: 100vh;
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
        .drag-div{
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
            display:none;
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
            
            <div class="form-group">
    <label for="${type.toLowerCase()}-question" class="col-form-label"><strong>Type your question here</strong></label>
    <label for="${type.toLowerCase()}-description">Type a description here (optional)</label>

    <input type="text" id="${type.toLowerCase()}-question" class="form-control">
</div>
            
            ${type === "Radio button" ? 
                `<div class="form-group row">
                    <label for="${type.toLowerCase()}-options" class="col-sm-3 col-form-label">Options:</label>
                    <div class="col-sm-9">
                        <textarea id="${type.toLowerCase()}-options" class="form-control" placeholder="Enter options, separated by commas"></textarea>
                    </div>
                </div>` : ''
            }

            <label for="${type.toLowerCase()}-description">Description (optional):</label>
            <textarea id="${type.toLowerCase()}-description" class="form-control" placeholder="Type a description here"></textarea>
            
            <div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="requiredSwitch" checked>
    <label class="custom-control-label" for="requiredSwitch">Required</label>
</div>

<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="maxCharactersSwitch">
    <label class="custom-control-label" for="maxCharactersSwitch">Max characters</label>
</div>

            
        `;
        document.getElementById("drop-div").appendChild(card);

        // Enable Bootstrap Toggle for switches
        $("[data-toggle='toggle']").bootstrapToggle();
    }
    
    // Other functions remain the same
</script>

</body>
</html>