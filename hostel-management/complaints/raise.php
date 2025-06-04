<?php
session_start();
include('../config.php');

$email = $_SESSION['email'] ?? 'student1@example.com'; // fallback
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Raise a Complaint</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0f2f5;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }
    .form-control, .form-select {
      border-radius: 10px;
    }
    .btn-custom {
      background: linear-gradient(90deg, #00b4db, #0083b0);
      color: #fff;
      border-radius: 30px;
      padding: 12px 30px;
      font-weight: 500;
    }
    .btn-custom:hover {
      background: linear-gradient(90deg, #0083b0, #00b4db);
    }
    .header {
      background: linear-gradient(90deg, #00b4db, #0083b0);
      color: white;
      padding: 25px 30px;
      border-radius: 0 0 10px 10px;
    }
  </style>
</head>
<body>

<div class="header text-center">
  <h2><i class="fa-solid fa-comment-dots me-2"></i>Raise a Complaint</h2>
  <p class="mb-0">Submit your concern and we’ll get it resolved soon 🙌</p>
</div>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card p-4">
        <form action="submit_complaint.php" method="POST">
          <div class="mb-3">
            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="subject" name="subject" placeholder="e.g. Water leakage in bathroom" required>
          </div>

          <div class="mb-3">
            <label for="category" class="form-label">Complaint Category <span class="text-danger">*</span></label>
            <select class="form-select" id="category" name="category" required>
              <option value="">-- Select Category --</option>
              <option value="Plumbing">Plumbing</option>
              <option value="Electricity">Electricity</option>
              <option value="Cleanliness">Cleanliness</option>
              <option value="Mess">Mess</option>
              <option value="Others">Others</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Describe the issue clearly..." required></textarea>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-custom"><i class="fa-solid fa-paper-plane me-2"></i>Submit Complaint</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</body>
</html>
