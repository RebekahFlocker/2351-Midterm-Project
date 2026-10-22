<?php
session_start();

// Helper function for letter grades
function getLetterGrade($grade) {
    if ($grade >= 90) return 'A';
    if ($grade >= 80) return 'B';
    if ($grade >= 70) return 'C';
    if ($grade >= 60) return 'D';
    return 'F';
}

// Initialize session students array if not already set (using the names from your user array)
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [
        'Macrae Cain' => 88,
        'Bob Smith' => 92,
        'Sally Sue' => 75,
        'Jenn Jones' => 84,
        'Olivia Cain' => 95,
        'Rebekah Flocker' => 98
    ];
}

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputName = trim($_POST['student_name'] ?? '');
    $inputGrade = trim($_POST['student_grade'] ?? '');

    if ($inputName === '') {
        $errorMessage = 'Please enter a student name.';
    } elseif ($inputGrade === '' || !is_numeric($inputGrade)) {
        $errorMessage = 'Please enter a valid numeric grade.';
    } else {
        $numericGrade = floatval($inputGrade);
        if ($numericGrade < 0 || $numericGrade > 100) {
            $errorMessage = 'Grade must be a number between 0 and 100.';
        } else {
            if (array_key_exists($inputName, $_SESSION['students'])) {
                $successMessage = "Updated grade for: " . htmlspecialchars($inputName);
            } else {
                $successMessage = "Added new student: " . htmlspecialchars($inputName);
            }
            $_SESSION['students'][$inputName] = $numericGrade;
            $_SESSION['success_msg'] = $successMessage;
            header('Location: index.php');
            exit;
        }
    }
}

if (!empty($errorMessage)) {
    $_SESSION['error_msg'] = $errorMessage;
    header('Location: index.php');
    exit;
}