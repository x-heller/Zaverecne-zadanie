<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Read the content of the LaTeX document
$filename = "../assignments/" . $_GET['filename'];
$latexContent = file_get_contents($filename);
// Extract tasks and solutions
preg_match_all('/\\\\begin{task}(.*?)\\\\end{task}/s', $latexContent, $tasks);
preg_match_all('/\\\\begin{solution}(.*?)\\\\end{solution}/s', $latexContent, $solutions);


$taskArray = $tasks[1];
$solutionArray = $solutions[1];

// Select a random index
$randomIndex = array_rand($taskArray);

// Retrieve the randomly selected task and solution
$randomTask = $taskArray[$randomIndex];
$randomSolution = $solutionArray[$randomIndex];

// Replace LaTeX image inclusion with HTML img tag
$randomTask = preg_replace('/\\\\includegraphics\{(.*?)\}/', '<img src="../../$1" alt="Block Diagram">', $randomTask);

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/9.4.4/math.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/9.4.4/math.js"></script>
    <script src="//unpkg.com/@cortex-js/compute-engine"></script>
    <script>
        let correct = false;
        const ce = new ComputeEngine.ComputeEngine();
        let randomSolution = <?php echo json_encode(preg_replace('/\\\\begin{equation\*}(.*?)\\\\end{equation\*}/s', '$1', $randomSolution)); ?>;

        function submitSolution() {
            let filename = <?php echo json_encode($_GET['filename']) ?>;
            let isOdozva = filename.includes("odozva");
            if (isOdozva) {
                const sides = randomSolution.split('=');
                const leftSide = sides[0].trim();  // y(t)
                let rightSide = sides[1].trim(); // 0.0833 - 1.5 * e^(-t) + 0.1666 * e^(-3*t) + 0.25 * e^(-4*t)
                let thirdSide = sides[2].trim(); // 0.0833 - 1.5 * e^(-t) + 0.1666 * e^(-3*t) + 0.25 * e^(-4*t)
                let solution = document.getElementById("mf").getValue();
                console.log(solution);


                rightSide = rightSide.trim();
                //in randomsolution replace \dfrac with /frac
                rightSide = rightSide.replace(/\\dfrac/g, '\\frac');
                console.log(rightSide);
                console.log(ce.parse(rightSide).N().latex);
                console.log(thirdSide)
                console.log(ce.parse(thirdSide).N().latex);
                console.log(ce.parse(solution).N().latex);

                if((ce.parse(rightSide).N().latex === ce.parse(solution).N().latex) || (ce.parse(thirdSide).N().latex === ce.parse(solution).N().latex)){
                    alert("Correct!");
                    correct = true;
                }
                else {
                    alert("FX!");
            }} else {



            let solution = document.getElementById("mf").getValue();
            console.log(solution);

            console.log(ce.parse(solution).N().latex);

            randomSolution = randomSolution.trim();
            //in randomsolution replace \dfrac with /frac
            randomSolution = randomSolution.replace(/\\dfrac/g, '\\frac');
            console.log(randomSolution);
            console.log(ce.parse(randomSolution).N().latex);

            if((ce.parse(randomSolution).N().latex === ce.parse(solution).N().latex)){
                alert("Correct!");
                correct = true;
            }
            else {
                alert("FX!");
            }}
            //POST
            //upload login, answer,points,testid(filename),section to database
            //if correct = true, points = 1, else points = 0

        }

    </script>

    <script defer src="//unpkg.com/mathlive"></script>
</head>
<body>
<h1>Random Task:</h1>
<p><?php echo $randomTask; ?></p>

<h1>Random Solution:</h1>
<p><?php echo $randomSolution; ?></p>

<h1>Write Your Solution:</h1>
<math-field id="mf"></math-field>


<button id="submitSolution" onclick="submitSolution()">Submit</button>

<style>
    #solutionContainer {
        display: flex;
    }

    #solutionInput {
        border: 1px solid #ccc;
        padding: 5px;
        width: 300px;s
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

