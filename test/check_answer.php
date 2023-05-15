<?php


$problems = [
    [
        'id' => 1,
        'statement' => 'Solve the equation: $x^2 - 4x + 4 = 0$',
        'solution' => 'The solution is $x = 2$',
        'sol' => '2'
        // Additional metadata
    ],
    // Add more problems
];

// Randomly select a problem
$randomProblem = $problems[array_rand($problems)];

// Display the problem statement to the student
echo $randomProblem['statement'];


$studentAnswer = $_POST['answer'];

if ($studentAnswer == $randomProblem['sol']) {
    echo 'Correct!';
    // Update student's score, store the submission, etc.
} else {
    echo 'Incorrect!';
    echo $randomProblem['solution'];
    // Provide feedback, show the correct solution, etc.
}
