<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Leave Details Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
            padding: 30px;
            min-height: 100vh;
        }

        /* Main Container */
        .dashboard-container {
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Header Section */
        .header {
            background: white;
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .title-section h1 {
            font-size: 28px;
            color: #1a2a4f;
            margin-bottom: 5px;
        }

        .title-section p {
            color: #6c757d;
            font-size: 14px;
        }

        .year-filter {
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .year-filter label {
            font-weight: 600;
            color: #1a2a4f;
        }

        .year-select {
            padding: 10px 20px;
            border: 2px solid #e0e4e8;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .year-select:hover {
            border-color: #4f46e5;
        }

        /* Stats Cards Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #1a2a4f;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Employee Card Container */
        .employees-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Individual Employee Card */
        .employee-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .employee-card:hover {
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        /* Employee Header */
        .employee-header {
            background: linear-gradient(135deg, #1a2a4f 0%, #2a3f6e 100%);
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .employee-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .employee-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .employee-details h3 {
            color: white;
            font-size: 18px;
            margin-bottom: 4px;
        }

        .employee-details span {
            color: rgba(255,255,255,0.7);
            font-size: 13px;
        }

        .yearly-summary {
            background: rgba(255,255,255,0.15);
            padding: 10px 20px;
            border-radius: 30px;
            display: flex;
            gap: 25px;
        }

        .yearly-summary div {
            text-align: center;
        }

        .yearly-summary .label {
            font-size: 11px;
            color: rgba(255,255,255,0.7);
        }

        .yearly-summary .value {
            font-size: 18px;
            font-weight: 700;
            color: white;
        }

        /* Months Grid */
        .months-grid {
            padding: 20px 25px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            background: #fefefe;
        }

        /* Month Card */
        .month-card {
            background: #f8f9fc;
            border-radius: 16px;
            padding: 14px;
            transition: all 0.2s ease;
            border: 1px solid #eef2f6;
        }

        .month-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }

        .month-name {
            font-weight: 700;
            font-size: 16px;
            color: #1a2a4f;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 2px solid #e0e4e8;
            text-align: center;
        }

        .leave-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .stat-item {
            text-align: center;
            padding: 8px 5px;
            border-radius: 12px;
        }

        .stat-item .stat-num {
            font-size: 20px;
            font-weight: 800;
        }

        .stat-item .stat-label-sm {
            font-size: 10px;
            color: #6c757d;
            margin-top: 4px;
        }

        .attendance-stat {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }
        .attendance-stat .stat-num { color: #155724; }

        .leave-stat {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        }
        .leave-stat .stat-num { color: #721c24; }

        .used-stat {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        }
        .used-stat .stat-num { color: #856404; }

        .remaining-stat {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        }
        .remaining-stat .stat-num { color: #0c5460; }

        /* Full width stat for used/remaining row */
        .stats-row-duo {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 12px;
        }

        /* Legend Section */
        .legend-section {
            margin-top: 30px;
            background: white;
            border-radius: 20px;
            padding: 20px 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .legend-title {
            font-weight: 700;
            color: #1a2a4f;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .legend-items {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            align-items: center;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .legend-color {
            width: 30px;
            height: 30px;
            border-radius: 8px;
        }

        .legend-text {
            font-size: 13px;
            color: #495057;
        }

        .note {
            margin-top: 15px;
            font-size: 12px;
            color: #6c757d;
            padding-top: 12px;
            border-top: 1px solid #e9ecef;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .months-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .employee-header {
                flex-direction: column;
                text-align: center;
            }
            .yearly-summary {
                justify-content: center;
            }
            .months-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .stats-row {
                grid-template-columns: 1fr;
            }
        }

        /* Badge */
        .badge-year {
            background: #4f46e5;
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header with Year Filter -->
        <div class="header">
            <div class="title-section">
                <h1>📊 Employee Leave Details</h1>
                <p>Monthly attendance & leave tracking dashboard</p>
            </div>
            <div class="year-filter">
                <label>📅 Select Year:</label>
                <select id="yearSelect" class="year-select">
                    <option value="2023">2023</option>
                    <option value="2024" selected>2024</option>
                    <option value="2025">2025</option>
                </select>
                <span class="badge-year">Current: 2024</span>
            </div>
        </div>

        <!-- Overall Stats Cards -->
        <div class="stats-row" id="overallStats">
            <!-- Dynamic stats will be updated via JS -->
        </div>

        <!-- Employees Container -->
        <div class="employees-container" id="employeesContainer">
            <!-- Employee cards will be dynamically rendered here -->
        </div>

        <!-- Legend -->
        <div class="legend-section">
            <div class="legend-title">📋 Legend & Information</div>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);"></div>
                    <span class="legend-text">Attendance (Days Present)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #f8d7da, #f5c6cb);"></div>
                    <span class="legend-text">Leave Taken (Days)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #fff3cd, #ffeaa7);"></div>
                    <span class="legend-text">Used (YTD Cumulative Leave)</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #d1ecf1, #bee5eb);"></div>
                    <span class="legend-text">Remaining (Annual - Used)</span>
                </div>
            </div>
            <div class="note">
                <strong>📌 Note:</strong> Annual leave policy: 12 days per year. Used = Cumulative leaves taken from Jan to current month. Remaining = 12 - Used. Total working days = Calendar days (demo purpose).
            </div>
        </div>
    </div>

    <script>
        // ---------- STATIC DUMMY DATA ----------
        // Employees data
        const employeesData = [
            { code: "EMP001", name: "Aarav Sharma", department: "IT" },
            { code: "EMP002", name: "Bhavna Reddy", department: "HR" },
            { code: "EMP003", name: "Chirag Mehta", department: "Finance" },
            { code: "EMP004", name: "Divya Nair", department: "Marketing" },
            { code: "EMP005", name: "Eshan Verma", department: "Operations" }
        ];

        // Month names
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        // Days in each month (non-leap year demo, but we'll handle static)
        const daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        // Helper: Generate random but realistic attendance/leave data
        // For each employee, year, month -> returns { attendance, leave, used, remaining }
        // used is cumulative from Jan to current month
        function generateMonthlyData(employeeCode, year, monthIndex) {
            // Create deterministic but varied data based on employee code and month
            const empHash = employeeCode.charCodeAt(employeeCode.length-1) || 0;
            const monthFactor = (monthIndex + 1) * (empHash % 5 + 1);
            
            // Random leave taken in this month (between 0 and 4 days)
            let leaveThisMonth = Math.floor(Math.random() * 5); // 0 to 4
            // Ensure we don't exceed working days (say 22 working days in a month, demo we use total days minus weekends approx)
            const totalDays = daysInMonth[monthIndex];
            const workingDays = totalDays - 8; // roughly 4 weekends = 8 days off, so 23 or 22 approx
            if (leaveThisMonth > workingDays) leaveThisMonth = workingDays - 1;
            if (leaveThisMonth < 0) leaveThisMonth = 0;
            
            const attendance = workingDays - leaveThisMonth;
            
            // For used & remaining, we need cumulative from Jan to this month
            // We'll simulate based on a fixed annual entitlement
            return { attendance, leave: leaveThisMonth };
        }

        // Compute cumulative used and remaining for a given employee, year, up to a particular month
        function getEmployeeMonthDetails(employeeCode, year, targetMonth) {
            let cumulativeLeave = 0;
            let monthlyDetails = [];
            // Build all months data from Jan to targetMonth
            for (let m = 0; m <= targetMonth; m++) {
                const { attendance, leave } = generateMonthlyData(employeeCode, year, m);
                cumulativeLeave += leave;
                monthlyDetails.push({
                    month: m,
                    monthName: monthNames[m],
                    attendance: attendance,
                    leave: leave,
                    used: cumulativeLeave,
                    remaining: Math.max(0, 12 - cumulativeLeave) // annual entitlement 12
                });
            }
            return monthlyDetails[targetMonth];
        }

        // Precompute full year data for an employee for all months
        function getFullYearData(employeeCode, year) {
            let cumulative = 0;
            const yearData = [];
            for (let m = 0; m < 12; m++) {
                const { attendance, leave } = generateMonthlyData(employeeCode, year, m);
                cumulative += leave;
                yearData.push({
                    monthIndex: m,
                    monthName: monthNames[m],
                    attendance: attendance,
                    leave: leave,
                    used: cumulative,
                    remaining: Math.max(0, 12 - cumulative)
                });
            }
            return yearData;
        }

        // Calculate overall stats (total attendance, total leave, total used, total remaining across all employees)
        function calculateOverallStats(selectedYear) {
            let totalAttendance = 0;
            let totalLeaveTaken = 0;
            let totalUsed = 0;
            let totalRemaining = 0;
            
            employeesData.forEach(emp => {
                let cumulative = 0;
                for (let m = 0; m < 12; m++) {
                    const { attendance, leave } = generateMonthlyData(emp.code, selectedYear, m);
                    cumulative += leave;
                    totalAttendance += attendance;
                    totalLeaveTaken += leave;
                    if (m === 11) {
                        totalUsed += cumulative;
                        totalRemaining += Math.max(0, 12 - cumulative);
                    }
                }
            });
            
            return { totalAttendance, totalLeaveTaken, totalUsed, totalRemaining };
        }

        // Render overall stats cards
        function renderOverallStats(year) {
            const stats = calculateOverallStats(year);
            const statsContainer = document.getElementById('overallStats');
            statsContainer.innerHTML = `
                <div class="stat-card">
                    <div class="stat-icon">📆</div>
                    <div class="stat-value">${stats.totalAttendance}</div>
                    <div class="stat-label">Total Attendance Days (All Employees)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏖️</div>
                    <div class="stat-value">${stats.totalLeaveTaken}</div>
                    <div class="stat-label">Total Leave Taken (All Employees)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-value">${stats.totalUsed}</div>
                    <div class="stat-label">Total Used Leaves (YTD)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✨</div>
                    <div class="stat-value">${stats.totalRemaining}</div>
                    <div class="stat-label">Total Remaining Leaves</div>
                </div>
            `;
        }

        // Render employee cards with monthly grid (No tables)
        function renderEmployees(year) {
            const container = document.getElementById('employeesContainer');
            container.innerHTML = '';
            
            employeesData.forEach(emp => {
                const yearData = getFullYearData(emp.code, year);
                // Calculate yearly totals for employee summary
                let totalAttendanceYear = 0;
                let totalLeaveYear = 0;
                let finalUsed = 0;
                yearData.forEach(month => {
                    totalAttendanceYear += month.attendance;
                    totalLeaveYear += month.leave;
                    if (month.monthIndex === 11) finalUsed = month.used;
                });
                const finalRemaining = Math.max(0, 12 - finalUsed);
                
                // Create employee card
                const card = document.createElement('div');
                card.className = 'employee-card';
                
                // Employee Header
                card.innerHTML = `
                    <div class="employee-header">
                        <div class="employee-info">
                            <div class="employee-avatar">
                                ${emp.name.charAt(0)}${emp.name.split(' ')[1]?.charAt(0) || emp.name.charAt(1)}
                            </div>
                            <div class="employee-details">
                                <h3>${emp.name} <span style="font-size:12px;">(${emp.code})</span></h3>
                                <span>${emp.department} Department</span>
                            </div>
                        </div>
                        <div class="yearly-summary">
                            <div>
                                <div class="label">Yearly Attendance</div>
                                <div class="value">${totalAttendanceYear}</div>
                            </div>
                            <div>
                                <div class="label">Total Leaves</div>
                                <div class="value">${totalLeaveYear}</div>
                            </div>
                            <div>
                                <div class="label">Used (Yearly)</div>
                                <div class="value">${finalUsed}</div>
                            </div>
                            <div>
                                <div class="label">Remaining</div>
                                <div class="value">${finalRemaining}</div>
                            </div>
                        </div>
                    </div>
                    <div class="months-grid" id="monthsGrid-${emp.code}">
                        <!-- Monthly cards will go here -->
                    </div>
                `;
                
                container.appendChild(card);
                
                // Populate months grid
                const monthsGrid = document.getElementById(`monthsGrid-${emp.code}`);
                yearData.forEach(month => {
                    const monthCard = document.createElement('div');
                    monthCard.className = 'month-card';
                    monthCard.innerHTML = `
                        <div class="month-name">${month.monthName}</div>
                        <div class="leave-stats">
                            <div class="stat-item attendance-stat">
                                <div class="stat-num">${month.attendance}</div>
                                <div class="stat-label-sm">Attendance</div>
                            </div>
                            <div class="stat-item leave-stat">
                                <div class="stat-num">${month.leave}</div>
                                <div class="stat-label-sm">Leave</div>
                            </div>
                        </div>
                        <div class="stats-row-duo">
                            <div class="stat-item used-stat">
                                <div class="stat-num">${month.used}</div>
                                <div class="stat-label-sm">Used</div>
                            </div>
                            <div class="stat-item remaining-stat">
                                <div class="stat-num">${month.remaining}</div>
                                <div class="stat-label-sm">Remaining</div>
                            </div>
                        </div>
                    `;
                    monthsGrid.appendChild(monthCard);
                });
            });
        }
        
        // Update year displayed on badge
        function updateYearBadge(year) {
            const badge = document.querySelector('.badge-year');
            if (badge) badge.textContent = `Current: ${year}`;
        }
        
        // Refresh entire dashboard based on selected year
        function refreshDashboard(year) {
            renderOverallStats(year);
            renderEmployees(year);
            updateYearBadge(year);
        }
        
        // Event listener for year select dropdown
        const yearSelect = document.getElementById('yearSelect');
        yearSelect.addEventListener('change', (e) => {
            const selectedYear = parseInt(e.target.value);
            refreshDashboard(selectedYear);
        });
        
        // Initialize with default year 2024
        refreshDashboard(2024);
        
        // Additionally, you can also add a hover effect tooltip logic (just for show)
        // This is pure static data representation as requested: "static data me ui bna k do dont use any table"
        // No HTML tables are used! Only flex, grid and div based layout.
    </script>
</body>
</html>