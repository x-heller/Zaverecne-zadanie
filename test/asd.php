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
                    symbolText = '<mfrac><mrow><mi>a</mi></mrow><mrow><mi>b</mi></mrow></mfrac>';
                    break;
                case 'a^2':
                    symbolText = '<msup><mi>a</mi><mn>2</mn></msup>';
                    break;
                case '\\sqrt{x}':
                    symbolText = '<msqrt><mi>x</mi></msqrt>';
                    break;
                // Add cases for other symbols as needed
            }

            // Create a temporary container for the MathML
            var div = document.createElement('div');

            // Set the symbolText as the content of the <div> element
            div.innerHTML = symbolText;

            // Get the MathML code from the <div> element
            var mathML = div.firstChild.outerHTML;

            // Create the <mrow> element
            var mrowElement = document.createElementNS("http://www.w3.org/1998/Math/MathML", "mrow");

            // Set the MathML code as the content of the <mrow> element
            mrowElement.innerHTML = mathML;

            // Get the current selection and range
            var sel = window.getSelection();
            var range = sel.getRangeAt(0);

            // Insert the <mrow> element into the range
            range.insertNode(mrowElement);
            var brElement = solutionInput.querySelector('br');
            if (brElement) {
                brElement.parentNode.removeChild(brElement);
            }
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
    <div id="solutionInput" style="border: 1px solid #ccc; padding: 5px; width: 300px;" contenteditable="plaintext-only"></div>
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
</script>
</body>
</html>

