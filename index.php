<?php
include 'config.php';
include 'controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management System</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Alert styling */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
            transition: opacity 0.5s ease;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Form styling */
        form {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        form button {
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #218838;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td button {
            padding: 6px 12px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            color: #fff;
            transition: background-color 0.3s;
        }

        /* Update button styling */
        .btn-edit {
            background-color: #ffc107;
            border: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }

        /* Delete button styling */
        .btn-delete {
            background-color: #dc3545;
            border: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        white-space: nowrap;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }

        td.action-buttons {
        padding: 10px;
        vertical-align: middle;
        }

    </style>
</head>
<body>

<div class="container">
    <!-- Display Success or Error Message -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success" id="alertMessage">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error" id="alertMessage">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <h2>Add New Patient</h2>
    <form method="post" action="controller.php">
        <input type="hidden" name="action" value="create">
        <input type="text" name="name" placeholder="Name" required>
        <input type="number" name="age" placeholder="Age" required>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <textarea name="diagnosis" placeholder="Diagnosis"></textarea>
        <button type="submit">Add Patient</button>
    </form>

    <h2>Patient List</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Diagnosis</th><th>Actions</th></tr>
        <?php
        $patients = getAllPatients();
        while($row = $patients->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['diagnosis'] ?></td>
            <td class="action-buttons">
                <form method="post" action="controller.php" style="display:inline;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="name" value="<?= $row['name'] ?>" required>
                    <input type="number" name="age" value="<?= $row['age'] ?>" required>
                    <select name="gender" required>
                        <option value="Male" <?= $row['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $row['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= $row['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <textarea name="diagnosis" required><?= $row['diagnosis'] ?></textarea>
                    <button type="submit" class="btn-edit">Update</button>
                </form>
                <a href="controller.php?action=delete&id=<?= $row['id'] ?>" class="btn-delete">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- JavaScript to auto-dismiss the alert message after 5 seconds -->
<script>
    setTimeout(function() {
        var alertMessage = document.getElementById('alertMessage');
        if (alertMessage) {
            alertMessage.style.opacity = '0'; // Fade out effect
            setTimeout(function() {
                alertMessage.style.display = 'none';
            }, 500); // Ensure it's fully hidden after fading
        }
    }, 2500); // 2500 ms = 2.5 seconds
</script>

</body>
</html>
