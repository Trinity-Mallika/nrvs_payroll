<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order Tracking</title>

<!-- Bootstrap (optional if already included in your template) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f5f6fa;
}

/* Card */
.tracking-card {
    max-width: 500px;
    margin: 40px auto;
    border-radius: 12px;
}

/* Timeline */
.tracking-wrapper {
    position: relative;
    margin-left: 20px;
    border-left: 3px solid #ddd;
    padding-left: 25px;
}

.tracking-step {
    position: relative;
    margin-bottom: 30px;
}

/* Circle */
.tracking-step .circle {
    position: absolute;
    left: -38px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #ccc;
    color: #fff;
    text-align: center;
    line-height: 30px;
    font-size: 14px;
    font-weight: bold;
}

/* States */
.tracking-step.done .circle {
    background: #28a745;
}

.tracking-step.active .circle {
    background: #ffc107;
    color: #000;
}

/* Text */
.tracking-step h6 {
    margin: 0;
    font-weight: 600;
}

.tracking-step small {
    color: #888;
}
</style>
</head>

<body>

<div class="container">
    <div class="card tracking-card shadow p-4">

        <h4 class="mb-4 text-center">🚚 Order Tracking</h4>

        <div class="tracking-wrapper">

            <!-- Step 1 -->
            <div class="tracking-step done">
                <div class="circle">✔</div>
                <h6>Order Placed</h6>
                <small>12 Apr 2026, 10:00 AM</small>
            </div>

            <!-- Step 2 -->
            <div class="tracking-step done">
                <div class="circle">✔</div>
                <h6>Gate Pass Created</h6>
                <small>12 Apr 2026, 10:15 AM</small>
            </div>

            <!-- Step 3 (CURRENT) -->
            <div class="tracking-step active">
                <div class="circle">⏳</div>
                <h6>Vehicle Dispatched</h6>
                <small>In Progress</small>
            </div>

            <!-- Step 4 -->
            <div class="tracking-step">
                <div class="circle">4</div>
                <h6>Reached Destination</h6>
                <small>Pending</small>
            </div>

            <!-- Step 5 -->
            <div class="tracking-step">
                <div class="circle">5</div>
                <h6>Completed</h6>
                <small>Pending</small>
            </div>

        </div>

    </div>
</div>

</body>
</html>