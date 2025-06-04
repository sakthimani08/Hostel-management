<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            color: #2d3436;
        }

        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            background: linear-gradient(to bottom right, #1e3c72, #2a5298);
            color: white;
            box-shadow: 4px 0 15px rgba(0,0,0,0.15);
            padding-top: 30px;
            transition: all 0.3s ease;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #00e3aa;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 25px;
            color: #ffffffcc;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .sidebar i {
            width: 20px;
        }

        .main {
            margin-left: 260px;
            padding: 60px;
        }

        .glass-box {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .glass-box h1 {
            font-size: 32px;
            font-weight: 700;
            color: #34495e;
        }

        .glass-box p {
            font-size: 18px;
            color: #2f3640;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>🏢 Hostel Admin</h2>
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="register-student.php"><i class="fas fa-user-plus"></i> Register Student</a>
        <a href="hostel-students.php"><i class="fas fa-users"></i> Hostel Students</a>
        <a href="fees.php"><i class="fas fa-wallet"></i> Fees</a>
        <a href="complaints.php"><i class="fas fa-comments"></i> Complaints</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main">
        <div class="glass-box">
            <h1>👋 Welcome Admin</h1>
            <p>Use the sidebar to navigate through student records, hostel rooms, fees, and complaints. This dashboard gives you full control of the hostel management system with a clean and premium interface.</p>
        </div>
    </div>

</body>
</html>
