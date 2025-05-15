<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .card {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<!-- links of the adminheader -->
<?php include('../admindashboard/adminHead.php'); ?>

<body>
<div class="dashboard-wrapper">
<?php include('../admindashboard/adminHeader.php'); ?>

    <main class="adminMessages-main">
        <div class="card">
            <h1>Coming Soon</h1>
        </div>
    </main>
</div>

</body>
</html>