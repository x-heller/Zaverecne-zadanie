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
                    symbolText = '<mfrac><mrow><mi>a</mi></mrow><mrow><mi>b</mi></mrow></mfrac><mi>+</mi>';
                    break;
                case 'a^2':
                    symbolText = '<mrow><msup><mi>a</mi><mn>2</mn></msup></mrow><mi>+</mi>';
                    break;
                case '\\sqrt{x}':
                    symbolText = '<mrow><msqrt><mi>x</mi></msqrt></mrow><mi>+</mi>';
                    break;
                // Add cases for other symbols as needed
            }

            // Create a temporary MathML container element
            // Create a MathML representation of the expression;

            // Create a temporary container for the MathML
            //var tempContainer = document.createElement('div');
           // tempContainer.innerHTML = symbolText;
            var tempContainer = document.getElementById('mainrow');
            tempContainer.innerHTML = tempContainer.innerHTML+symbolText;
            // Get the MathML element from the container
            var mathMLElement = tempContainer.firstChild;

            // Insert the MathML element at the current cursor position
            var selection = window.getSelection();
            var range = selection.getRangeAt(0);
            range.deleteContents();
            range.insertNode(mathMLElement);

            // Insert a space after the MathML element
            var spaceNode = document.createTextNode(' ');
            range.insertNode(spaceNode);

            // Move the cursor after the inserted space
            range.setStartAfter(spaceNode);
            range.setEndAfter(spaceNode);
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

    <div id="solutionInput" contenteditable="true">
        <math xmlns="http://www.w3.org/1998/Math/MathML"><mrow id="mainrow"></mrow></math>
    </div>
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
