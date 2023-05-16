<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read the content of the LaTeX document
$latexContent = file_get_contents('./blokovka01pr.tex');

// Extract tasks and solutions
preg_match_all('/\\\\begin{task}(.*?)\\\\end{task}/s', $latexContent, $tasks);
preg_match_all('/\\\\begin{solution}(.*?)\\\\end{solution}/s', $latexContent, $solutions);

// Store tasks and solutions in separate arrays
$taskArray = $tasks[1];
$solutionArray = $solutions[1];

// Select a random index
$randomIndex = array_rand($taskArray);

// Retrieve the randomly selected task and solution
$randomTask = $taskArray[$randomIndex];
$randomSolution = $solutionArray[$randomIndex];

// Replace LaTeX image inclusion with HTML img tag
$randomTask = preg_replace('/\\\\includegraphics\{(.*?)\}/', '<img src="../images/$1" alt="Block Diagram">', $randomTask);

// Decode LaTeX special characters
$randomTask = html_entity_decode($randomTask, ENT_QUOTES);
$randomSolution = html_entity_decode($randomSolution, ENT_QUOTES);

// Replace "\dfrac" with fraction markup
$randomTask = preg_replace('/\$(.*?)\$/s', '<span>\($1\)</span>', $randomTask);

?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
    <script type="text/javascript">
        // Function to handle adding symbols to the solution input
        // Function to handle adding symbols to the solution input
        // Function to handle adding symbols to the solution input
        // Function to handle adding symbols to the solution input
        // Function to handle adding symbols to the solution input
        // Function to handle adding symbols to the solution input
        // Function to handle adding symbols to the solution input
        function addSymbol(symbol, event) {
            var solutionInput = document.getElementById('solutionInput');
            var symbolText = '';

            // Convert symbols to LaTeX format
            switch (symbol) {
                case '+':
                case '-':
                    symbolText = '<mo>' + symbol + '</mo>';
                    break;
                case '\\frac{a}{b}':
                    symbolText = '<mfrac><mi>a</mi><mi>b</mi></mfrac>';
                    break;
                case 'a^2':
                    symbolText = '<msup><mi>a</mi><mn>2</mn></msup>';
                    break;
                case '\\sqrt{x}':
                    symbolText = '<msqrt><mi>x</mi></msqrt>';
                    break;
                // Add cases for other symbols as needed
            }

            // Create a temporary MathML container element
            var tempContainer = document.createElement('div');
            tempContainer.innerHTML = '<math xmlns="http://www.w3.org/1998/Math/MathML">' + symbolText + '</math>';

            // Get the MathML representation from the container element
            var mathMLElement = tempContainer.firstChild;

            // Get the current selection range
            var selection = window.getSelection();
            var range = selection.getRangeAt(0);

            // Check if the selection is inside the solutionInput element
            if (range.commonAncestorContainer === solutionInput || solutionInput.contains(range.commonAncestorContainer)) {
                // Create a new MathML element for the symbol
                var symbolContainer = document.createElement('span');
                symbolContainer.innerHTML = '<math xmlns="http://www.w3.org/1998/Math/MathML">' + symbolText + '</math>';
                var symbolNode = symbolContainer.firstChild;

                // Wrap the symbolNode with a <span> element
                var spanNode = document.createElement('span');
                spanNode.appendChild(symbolNode);

                // Get the start container and offset of the range
                var startContainer = range.startContainer;
                var startOffset = range.startOffset;

                // Check if the start container is a text node
                if (startContainer.nodeType === Node.TEXT_NODE) {
                    // Split the text node at the start offset
                    var textNode = startContainer.splitText(startOffset);

                    // Insert the symbol at the split point
                    textNode.parentNode.insertBefore(spanNode, textNode);
                } else {
                    // Insert the symbol at the start offset
                    startContainer.insertBefore(spanNode, startContainer.childNodes[startOffset]);
                }

                // Set the selection range to the end of the inserted symbol
                range.setStartAfter(spanNode);
                range.setEndAfter(spanNode);
            } else {
                // Append the MathML element to the solution input
                solutionInput.appendChild(mathMLElement);

                // Set the selection range to the end of the inserted symbol
                range.setStartAfter(mathMLElement);
                range.setEndAfter(mathMLElement);
            }

            // Collapse the selection range to the end
            selection.removeAllRanges();
            selection.addRange(range);

            // Trigger MathJax typesetting for the updated content
            MathJax.typesetPromise([solutionInput]).catch(function (err) {
                console.log(err.message);
            });
        }

        function submitSolution() {
            var userSolution = document.getElementById('solutionInput').innerHTML;
            // Perform solution evaluation here
            alert('Submitted Solution: ' + userSolution);
        }
    </script>
</head>
<body>
<h1>Random Task:</h1>
<p><?php echo $randomTask; ?></p>

<h1>Random Solution:</h1>
<p><?php echo $randomSolution; ?></p>

<h1>Write Your Solution:</h1>
<div id="solutionContainer">
    <div id="solutionInput" contenteditable="true"></div>
    <div id="symbolBox">
        <button class="symbolButton" onclick="addSymbol('+')">+</button>
        <button class="symbolButton" onclick="addSymbol('-')">-</button>
        <button class="symbolButton" onclick="addSymbol('\\frac{a}{b}')">\(\frac{a}{b}\)</button>
        <button class="symbolButton" onclick="addSymbol('a^2')">\(a^2\)</button>
        <button class="symbolButton" onclick="addSymbol('\\sqrt{x}')">\(\sqrt{x}\)</button>
        <!-- Add more buttons for other symbols as needed -->
    </div>
</div>

<button id="submitSolution" onclick="submitSolution()">Submit</button>

<style>
    #solutionContainer {
        display: flex;
    }

    #solutionInput {
        border: 1px solid #ccc;
        padding: 5px;
        width: 300px;
    }

    #symbolBox {
        margin-top: 10px;
    }

    .symbolButton {
        width: 30px;
        height: 30px;
        margin-right: 5px;
    }
</style>

<script>
    // Trigger MathJax typesetting after the page has loaded
    window.addEventListener('load', function() {
        MathJax.typeset();
    });
</script>
</body>
</html>
