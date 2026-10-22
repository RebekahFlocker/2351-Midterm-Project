<?php
session_start();

// Enforce login check: If not logged in, send them to login.php immediately
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$currentUser = $_SESSION['user'];

function getLetterGrade($grade) {
    if ($grade >= 90) return 'A';
    if ($grade >= 80) return 'B';
    if ($grade >= 70) return 'C';
    if ($grade >= 60) return 'D';
    return 'F';
}

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

$students = $_SESSION['students'];
$errorMessage = $_SESSION['error_msg'] ?? '';
$successMessage = $_SESSION['success_msg'] ?? '';

unset($_SESSION['error_msg'], $_SESSION['success_msg']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Gradebook - Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; text-align: left;">Student Grade Book</h1>
            <p style="margin: 5px 0 0 0; color: #666;">Welcome, <strong><?php echo htmlspecialchars(($currentUser['firstName'] ?? '') . ' ' . ($currentUser['lastName'] ?? '')); ?></strong> (<?php echo htmlspecialchars($currentUser['eMail'] ?? ''); ?>)</p>
        </div>
        <a href="login.php" style="color: #dc3545; text-decoration: none; font-weight: bold; background: #f8d7da; padding: 8px 15px; border-radius: 4px;">Log Out</a>
    </div>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <div class="alert alert-danger" id="js-error" style="display: none;"></div>

    <form action="process-grade.php" method="POST" onsubmit="return validateForm();">
        <div class="form-group">
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" name="student_name" placeholder="e.g. John Doe">
        </div>

        <div class="form-group">
            <label for="student_grade">Number Grade (0-100):</label>
            <input type="text" id="student_grade" name="student_grade" placeholder="e.g. 88.5">
        </div>

        <button type="submit">Submit Grade</button>
    </form>

    <h2>Grade Roster</h2>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Original Grade</th>
                <th>Original Letter</th>
                <th>Adjusted Grade (+10%)</th>
                <th>Adjusted Letter</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalOriginalGrade = 0;
            $studentCount = count($students);

            foreach ($students as $name => $origGrade):
                $totalOriginalGrade += $origGrade;
                $origLetter = getLetterGrade($origGrade);
                $adjGrade = $origGrade * 1.10;
                $adjLetter = getLetterGrade($adjGrade);
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo number_format($origGrade, 2); ?></td>
                    <td><strong><?php echo $origLetter; ?></strong></td>
                    <td><?php echo number_format($adjGrade, 2); ?></td>
                    <td><strong><?php echo $adjLetter; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="summary-box">
        <?php
        $averageGrade = $studentCount > 0 ? ($totalOriginalGrade / $studentCount) : 0;
        ?>
        <p><strong>Total Students:</strong> <?php echo $studentCount; ?></p>
        <p><strong>Sum of Original Grades:</strong> <?php echo number_format($totalOriginalGrade, 2); ?></p>
        <p><strong>Average Original Grade:</strong> <?php echo number_format($averageGrade, 2); ?></p>
    </div>

    <div class="debug-container">
        <h3>Debug Information (var_dump):</h3>
        <pre><?php var_dump($students); ?></pre>
    </div>
</div>

<script>
function validateForm() {
    var nameInput = document.getElementById('student_name').value.trim();
    var gradeInput = document.getElementById('student_grade').value.trim();
    var errorDiv = document.getElementById('js-error');
    
    errorDiv.style.display = 'none';
    errorDiv.innerHTML = '';

    if (nameInput === '') {
        errorDiv.innerHTML = 'JavaScript Error: Student Name cannot be empty.';
        errorDiv.style.display = 'block';
        return false;
    }

    if (gradeInput === '' || isNaN(gradeInput)) {
        errorDiv.innerHTML = 'JavaScript Error: Please enter a valid numerical grade.';
        errorDiv.style.display = 'block';
        return false;
    }

    var numericGrade = parseFloat(gradeInput);
    if (numericGrade < 0 || numericGrade > 100) {
        errorDiv.innerHTML = 'JavaScript Error: Grade must be between 0 and 100.';
        errorDiv.style.display = 'block';
        return false;
    }

    return true;
}
</script>

</body>
</html>