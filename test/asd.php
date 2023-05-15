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
    <script>
        window.MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']],
                processEscapes: true,
                processEnvironments: true,
                packages: ['base', 'ams']
            },
            options: {
                skipHtmlTags: ['script', 'noscript', 'style', 'textarea', 'pre'],
                ignoreHtmlClass: 'tex2jax_ignore',
                processHtmlClass: 'tex2jax_process'
            }
        };

        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
    </script>
</head>
<body>
<h1>Random Task:</h1>
<p><?php echo $randomTask; ?></p>

<h1>Random Solution:</h1>
<p><?php echo $randomSolution; ?></p>
</body>
</html>
