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

if (preg_match_all('/\\\\section\*\{(.*?)\}/s', $latexContent, $section) > 0) {
    $thesection= $section[1][$randomIndex];// Output: B34A5A
    //echo $thesection;
} else {
    echo "No section found.";
}

// Retrieve the randomly selected task and solution
$randomTask = $taskArray[$randomIndex];
$randomSolution = $solutionArray[$randomIndex];

// Replace LaTeX image inclusion with HTML img tag
$randomTask = preg_replace('/\\\\includegraphics\{(.*?)\}/', '<img class="pic" src="../../$1" alt="Block Diagram">', $randomTask);

// Decode LaTeX special characters
$randomTask = html_entity_decode($randomTask, ENT_QUOTES);
$randomSolution = html_entity_decode($randomSolution, ENT_QUOTES);

// Replace "\dfrac" with fraction markup
$randomTask = preg_replace('/\$(.*?)\$/s', '<span>\($1\)</span>', $randomTask);

//$solution= $_POST['solution'];
//echo $solution;
//$login = $_SESSION['login'];

//
require_once "../config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
//check which user generated the test and add +1 to the number of tests generated
$sql = "SELECT * FROM users WHERE login = :login";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':login', $_SESSION['login']);
$success = $stmt->execute();

if ($success) {
    // Retrieve the filename and section
    $filename = $_GET['filename'];
    $section = $thesection; // Assuming you have defined $thesection elsewhere

    // Insert query
    $sql = "INSERT INTO generate (testname, section, login) VALUES (:filename, :section, :login)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':filename', $filename);
    $stmt->bindParam(':section', $section);
    $stmt->bindParam(':login', $_SESSION['login']);

    $success = $stmt->execute();

    if ($success) {
        // Insertion successful
        //echo "Data inserted successfully.";
    } else {
        // Insertion failed
        $errorInfo = $stmt->errorInfo();
        echo "Error inserting data: " . $errorInfo[2];
    }
} else {
    // SELECT query failed
    $errorInfo = $stmt->errorInfo();
    echo "Error retrieving user data: " . $errorInfo[2];
}//echo $_GET['filename'];
try{
$sql = "SELECT point FROM assignments WHERE filename = :filename";
$stmt = $pdo->prepare($sql);
    $stmt->bindParam(':filename', $_GET['filename']);

    // Execute the query
    $success = $stmt->execute();

    // Check if the query was successful
    if ($success) {
        // Check if there are any rows returned
        if ($stmt->rowCount() > 0) {
            // Retrieve the points
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $point = $row['point'];
                // Use the retrieved point as needed (e.g., display, store in a variable, etc.)
                //echo "Point: $point";
            }
        } else {
            echo "No points found for the specified filename.";
        }
    } else {
        // Handle query error
        $errorInfo = $stmt->errorInfo();
        echo "Error executing the query: " . $errorInfo[2];
    }
} catch (PDOException $e) {
    // Handle PDOException
    echo "Error: " . $e->getMessage();
}

// Close the database connection (if needed)
$connection = null;

//
//$sql = "INSERT INTO student (login, answer, points, testid, section) VALUES (:login, :answer, :points, :testid,:section)";
//$stmt = $pdo->prepare($sql);




?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/9.4.4/math.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/9.4.4/math.js"></script>
    <script src="//unpkg.com/@cortex-js/compute-engine"></script>
    <style>
        html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body{
            margin: 20px;
        }
        #mf{
            min-width: 200px;
            min-height: 50px;
            font-size: 30px;
            margin: 0 auto;
            display: block;
        }
        .pic{
            width: 800px;
            margin: 0 auto;
            display: block;
        }
        @media screen and (max-width: 800px) {
            .pic{
                width: 98%;
            }
            body{
                margin: 0;
            }
        }
        span{
            font-size: 20px;

        }
        p{
            font-size: 20px;
            margin: 30px auto;
            text-align: center;
        }
        h1{
            padding-top: 20px;
            text-align: center;
        }
        #mf{
            width: 400px;
        }

        button{
            border-radius: 5px;
            width: 60px;
            height: 40px;
            margin: 20px auto;
            display: block;
            border: transparent;
        }

    </style>
    <script>
        let correct = false;
        const ce = new ComputeEngine.ComputeEngine();
        let randomSolution = <?php echo json_encode(preg_replace('/\\\\begin{equation\*}(.*?)\\\\end{equation\*}/s', '$1', $randomSolution)); ?>;

        function submitSolution() {
            // check which user submitted the test and add filename section and login to odovzdane table

            let filename = <?php echo json_encode($_GET['filename']) ?>;
            let isOdozva = filename.includes("odozva02");
            let isOdozva2 = filename.includes("odozva01");
            if (isOdozva) {
                const sides = randomSolution.split('=');
                const leftSide = sides[0].trim();  // y(t)
                let rightSide = sides[1].trim(); // 0.0833 - 1.5 * e^(-t) + 0.1666 * e^(-3*t) + 0.25 * e^(-4*t)
                let thirdSide = sides[2].trim(); // 0.0833 - 1.5 * e^(-t) + 0.1666 * e^(-3*t) + 0.25 * e^(-4*t)
                var solution = document.getElementById("mf").getValue();
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
                    // alert("Correct!");
                    correct = true;
                }
            //     else {
            //         alert("FX!");
            // }
            }
            else if (isOdozva2){
                const sides = randomSolution.split('=');
                const leftSide = sides[0].trim();  // y(t)
                let rightSide = sides[1].trim(); // 0.0833 - 1.5 * e^(-t) + 0.1666 * e^(-3*t) + 0.25 * e^(-4*t)

                var solution = document.getElementById("mf").getValue();
                console.log(solution);


                rightSide = rightSide.trim();
                //in randomsolution replace \dfrac with /frac
                rightSide = rightSide.replace(/\\dfrac/g, '\\frac');
                console.log(rightSide);
                console.log(ce.parse(rightSide).N().latex);
                console.log(ce.parse(solution).N().latex);

                if((ce.parse(rightSide).N().latex === ce.parse(solution).N().latex)){
                    // alert("Correct!");
                    correct = true;
                }
                // else {
                //     alert("FX!");
                // }
            }

                else {



            var solution = document.getElementById("mf").getValue();
            console.log(solution);

            // let xhr = new XMLHttpRequest();
            // xhr.open("POST", "PHP_SELF", true);
            // xhr.setRequestHeader('Content-Type', 'application/json');
            // let data ={
            //     "solution": solution,
            // }
            // let dataJson = JSON.stringify(data);
            // xhr.send(dataJson);




            console.log(ce.parse(solution).N().latex);

            randomSolution = randomSolution.trim();
            //in randomsolution replace \dfrac with /frac
            randomSolution = randomSolution.replace(/\\dfrac/g, '\\frac');
            console.log(randomSolution);
            console.log(ce.parse(randomSolution).N().latex);

            if((ce.parse(randomSolution).N().latex === ce.parse(solution).N().latex)){
                // alert("Correct!");
                correct = true;
            }
            // else {
            //     alert("FX!");
            // }
                }

            let points = <?php echo json_encode($point) ?>;
            console.log(points);
            let login = <?php echo json_encode($_SESSION['login']) ?>;
            let testid = <?php echo json_encode($_GET['filename']) ?>;
            let section = <?php echo json_encode($thesection) ?>;

            let urlW = 'summary.php?points=' + points + '&login=' + login + '&testid=' + testid + '&section=' + section + '&solution=' + solution + '&correct=' + correct;
            window.location.href = urlW;

        }

    </script>

    <script defer src="//unpkg.com/mathlive"></script>
</head>
<body>
<h1>Your task is - Vaša úloha je:</h1>
<p><?php echo $randomTask; ?></p>

<!--<h1>Random Solution:</h1>-->
<!--<p>--><?php //echo $randomSolution; ?><!--</p>-->

<h1>Write Your Solution:</h1>
<math-field id="mf"></math-field>


<button style="box-shadow: 0 1px 2px 1px #ddd;color: #ffffff;background-color: #3993f0;" class="btn btn-sm btn-google btn-block text-uppercase btn-outline my-btn" id="submitSolution" onclick="submitSolution()">Submit</button>

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

