<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AWS Website Checker</title>
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Google Fonts for modern typography -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="https://www.eginnovations.com/images/eg-small.svg" type="image/x-icon">
    <link rel="icon" href="https://www.eginnovations.com/images/eg-small.svg" type="image/x-icon">
  <style>
    /* Reset & Global Styles */
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: #f7f7f7;
      color: #333;
    }
    /* AWS Top Bar */
    .top-bar {
      background-color: #232F3E;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
    }
    .top-bar .brand {
      display: flex;
      align-items: center;
      gap: 0.8rem;
    }
    .top-bar .brand img {
      height: 40px;
    }
    .top-bar .brand span {
      font-size: 1.8rem;
      font-weight: bold;
    }
    .top-bar nav a {
      color: #FF9900;
      text-decoration: none;
      font-size: 1rem;
      margin-left: 1.5rem;
    }
    /* Main Container */
    .container {
      max-width: 1200px;
      margin: 2rem auto;
      background: #fff;
      border-radius: 10px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    header {
      text-align: center;
      margin-bottom: 2rem;
    }
    header h2 {
      margin: 0.2rem 0;
      color: #FF9900;
    }
    #datetime {
      font-size: 1rem;
      color: #555;
      margin-bottom: 1rem;
    }
    /* Overall Progress Bar */
    .progress-container {
      background: #e0e0e0;
      border-radius: 20px;
      overflow: hidden;
      height: 30px;
      margin: 0 auto 2rem;
      max-width: 600px;
    }
    .progress-fill {
      background: linear-gradient(45deg, #FF9900, #FFB84D);
      height: 100%;
      width: 0;
      transition: width 0.5s ease;
      line-height: 30px;
      text-align: center;
      color: #fff;
      font-weight: bold;
    }
    /* Test List */
    .test-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .test-item {
      background: #fafafa;
      border: 1px solid #ddd;
      padding: 1.2rem;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .test-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .test-title {
      font-size: 1.2rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
      color: #333;
    }
    .test-status {
      font-size: 0.95rem;
      color: #666;
    }
    /* System Metrics Panel */
    .metrics {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .metric-card {
      background: #f0f4f8;
      border: 1px solid #dcdcdc;
      padding: 1rem;
      border-radius: 8px;
      text-align: center;
    }
    .metric-title {
      font-size: 1rem;
      font-weight: bold;
      color: #333;
      margin-bottom: 0.5rem;
    }
    .metric-value {
      font-size: 1.2rem;
      color: #FF9900;
    }
    /* Error Console */
    .error-console {
      background: #ffecec;
      border: 1px solid #ffaaaa;
      padding: 1rem;
      border-radius: 8px;
      max-height: 250px;
      overflow-y: auto;
      margin-bottom: 2rem;
    }
    .error-console h3 {
      margin-top: 0;
      font-size: 1.4rem;
      color: #d8000c;
    }
    .error-entry {
      padding: 0.5rem;
      border-bottom: 1px solid #ffd3d3;
      font-size: 0.95rem;
      transition: opacity 1s ease;
    }
    /* AWS Footer */
    .aws-footer {
      text-align: center;
      margin-top: 2rem;
    }
    .aws-footer a {
      text-decoration: none;
      color: #FF9900;
      font-size: 1.2rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    .aws-footer img {
      height: 40px;
    }
    @media (max-width: 768px) {
      .container {
        margin: 1rem;
        padding: 1.5rem;
      }
      .top-bar {
        flex-direction: column;
        gap: 1rem;
      }
      .top-bar nav a {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <!-- AWS Themed Top Bar -->
  <div class="top-bar">
    <div class="brand">
      <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/Amazon_Web_Services_Logo.svg" alt="AWS Logo">
      <span>AWS Website Checker</span>
    </div>
    <nav>
      <a href="https://www.eginnovations.com/documentation/Monitoring-AWS-EC2-Cloud/AWS-EC2-Web-Access-Test.htm" target="_blank">Visit documentation</a>
    </nav>
  </div>

  <div class="container">
    <header>
      <h2>Diagnostic Suite</h2>
      <div id="datetime"></div>
      <div class="progress-container">
        <div class="progress-fill" id="overallProgress">0%</div>
      </div>
    </header>

    <div class="test-list" id="testList"></div>

    <!-- System Metrics Panel -->
    <div class="metrics" id="metricsPanel">
      <div class="metric-card">
        <div class="metric-title">Server Speed</div>
        <div class="metric-value" id="serverSpeed">0 Gbps</div>
      </div>
      <div class="metric-card">
        <div class="metric-title">Database Response</div>
        <div class="metric-value" id="dbResponse">0 ms</div>
      </div>
      <div class="metric-card">
        <div class="metric-title">Network Latency</div>
        <div class="metric-value" id="netLatency">0 ms</div>
      </div>
      <div class="metric-card">
        <div class="metric-title">CPU Load</div>
        <div class="metric-value" id="cpuLoad">0%</div>
      </div>
    </div>

    <div class="error-console">
      <h3><i class="fas fa-exclamation-triangle"></i> Live Error Console</h3>
      <div id="errorLog"></div>
    </div>

    <div class="aws-footer">
      <a href="https://www.eginnovations.com/documentation/Monitoring-AWS-EC2-Cloud/AWS-EC2-Web-Access-Test.htm" target="_blank">
        <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/Amazon_Web_Services_Logo.svg" alt="AWS Logo">
        Visit AWS Website Checker
      </a>
    </div>
  </div>

  <!-- Include Moment.js and Moment Timezone -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data.min.js"></script>
  <script>
    // ---------------- TEST SCHEDULING SETUP ----------------
    const tests = [
      'Mobile Layout Validation',
      'Cross-Browser Compatibility',
      'Responsive Breakpoints Check',
      'iOS Safari Rendering',
      'Android Chrome Compatibility',
      'CSS Grid/Flexbox Validation',
      'Viewport Meta Tag Check',
      'Touch Target Analysis',
      'Network Throttling Test',
      'LCP (Largest Contentful Paint)',
      'FID (First Input Delay)',
      'CLS (Cumulative Layout Shift)',
      'SSL Certificate Check',
      'DNS Resolution Test',
      'CDN Latency Measurement',
      'API Response Validation',
      'Database Connection Pooling',
      'Load Balancer Health Check',
      'Cache Invalidation Test',
      'Security Headers Audit'
    ];

    // Define suite start (6:45 AM) and end (8:00 PM) in UK time
    const suiteStart = moment.tz("Europe/London").set({ hour: 6, minute: 45, second: 0, millisecond: 0 });
    const suiteEnd = moment.tz("Europe/London").set({ hour: 20, minute: 0, second: 0, millisecond: 0 });

    // Allocate internal time slots for each test.
    let testSlots = [];
    testSlots.push({
      start: suiteStart.clone(),
      end: suiteStart.clone().add(15, 'minutes')
    });
    const remainingTestsCount = tests.length - 1;
    const remainingDuration = suiteEnd.diff(suiteStart.clone().add(15, 'minutes'), 'minutes');
    const slotDuration = remainingDuration / remainingTestsCount;
    for (let i = 1; i < tests.length; i++) {
      const prevSlot = testSlots[i - 1];
      testSlots.push({
        start: prevSlot.end.clone(),
        end: prevSlot.end.clone().add(slotDuration, 'minutes')
      });
    }

    // ---------------- ERROR LOG PERSISTENCE & DIVERSITY ----------------
    const ERROR_STORAGE_KEY = "aws_error_logs";
    // 15 different error messages
    const errorReports = [
      "API response delay detected",
      "Database connection timeout",
      "Cache miss error",
      "Memory leak detected in module X",
      "Server overload warning",
      "DNS resolution failure",
      "SSL certificate near expiry",
      "High CPU usage detected",
      "Network latency spike",
      "Load balancer error",
      "Failed to retrieve external data",
      "Memory allocation error",
      "Disk space running low",
      "Unexpected shutdown event",
      "Critical security header missing"
    ];
    function loadErrorLogs() {
      const logs = localStorage.getItem(ERROR_STORAGE_KEY);
      return logs ? JSON.parse(logs) : [];
    }
    function saveErrorLogs(logs) {
      localStorage.setItem(ERROR_STORAGE_KEY, JSON.stringify(logs));
    }
    function renderErrorLogs() {
      const errorLogContainer = document.getElementById('errorLog');
      const logs = loadErrorLogs();
      errorLogContainer.innerHTML = "";
      logs.forEach(log => {
        const entry = document.createElement('div');
        entry.className = "error-entry";
        entry.innerHTML = `<strong>[${moment(log.timestamp).tz("Europe/London").format('h:mm:ss A')}]</strong> ${log.message} - <em>${log.status}</em>`;
        errorLogContainer.appendChild(entry);
      });
    }
    function addErrorLog(message) {
      let logs = loadErrorLogs();
      const newLog = {
        timestamp: moment.tz("Europe/London").valueOf(),
        message: message,
        status: "Auto-fix in progress"
      };
      logs.push(newLog);
      saveErrorLogs(logs);
      renderErrorLogs();
    }
    function updateErrorLogs() {
      let logs = loadErrorLogs();
      let updated = false;
      logs = logs.map(log => {
        if (moment().tz("Europe/London").diff(moment(log.timestamp), 'seconds') > 30 && log.status === "Auto-fix in progress") {
          log.status = (Math.random() < 0.5) ? "Fixed" : "Manual fix required – please check server logs";
          updated = true;
        }
        return log;
      });
      if (updated) {
        saveErrorLogs(logs);
        renderErrorLogs();
      }
    }

    // ---------------- PROGRESS & TEST LIST UPDATE ----------------
    function updateOverallProgress() {
      const now = moment.tz("Europe/London");
      let percent = now.isBefore(suiteStart) ? 0 : now.isAfter(suiteEnd) ? 100 : ((now.diff(suiteStart)) / (suiteEnd.diff(suiteStart))) * 100;
      percent = Math.floor(percent);
      const progressEl = document.getElementById('overallProgress');
      progressEl.style.width = percent + "%";
      progressEl.textContent = percent + "%";
    }
    function updateDateTime() {
      document.getElementById('datetime').textContent = moment.tz("Europe/London").format('dddd, MMMM Do YYYY, h:mm:ss A [UK Time]');
    }
    function updateTestList() {
      const now = moment.tz("Europe/London");
      const testListContainer = document.getElementById('testList');
      testListContainer.innerHTML = "";
      tests.forEach((test, index) => {
        const slot = testSlots[index];
        let statusText = "";
        if (now.isBefore(slot.start)) {
          statusText = "Pending";
        } else if (now.isBetween(slot.start, slot.end)) {
          const progress = Math.floor(((now.diff(slot.start)) / slot.end.diff(slot.start)) * 100);
          statusText = "In Progress: " + progress + "% complete";
        } else {
          const durationMinutes = slot.end.diff(slot.start, 'minutes');
          statusText = "Completed in " + durationMinutes + " minutes";
        }
        const itemDiv = document.createElement('div');
        itemDiv.className = 'test-item';
        itemDiv.innerHTML = `
          <div class="test-title">${test}</div>
          <div class="test-status">${statusText}</div>
        `;
        testListContainer.appendChild(itemDiv);
      });
    }

    // ---------------- SYSTEM METRICS UPDATE ----------------
    function updateMetrics() {
      // Simulate live system metrics
      document.getElementById('serverSpeed').textContent = (Math.random() * 2 + 1.0).toFixed(1) + " Gbps";
      document.getElementById('dbResponse').textContent = Math.floor(Math.random() * 100 + 20) + " ms";
      document.getElementById('netLatency').textContent = Math.floor(Math.random() * 50 + 10) + " ms";
      document.getElementById('cpuLoad').textContent = Math.floor(Math.random() * 50 + 10) + "%";
    }

    // ---------------- SIMULATE RANDOM ERROR GENERATION ----------------
    function logRandomErrors() {
      const now = moment.tz("Europe/London");
      tests.forEach((test, index) => {
        const slot = testSlots[index];
        // Generate error only if the test is in progress (10% chance)
        if (now.isBetween(slot.start, slot.end) && Math.random() < 0.1) {
          const errorMessage = errorReports[Math.floor(Math.random() * errorReports.length)];
          addErrorLog(`Error in ${test}: ${errorMessage}`);
        }
      });
    }

    // ---------------- INITIALIZE & INTERVALS ----------------
    updateOverallProgress();
    updateDateTime();
    updateTestList();
    renderErrorLogs();
    updateMetrics();
    setInterval(updateOverallProgress, 1000);
    setInterval(updateDateTime, 1000);
    setInterval(updateTestList, 5000);
    setInterval(updateMetrics, 3000);
    setInterval(logRandomErrors, 7000);
    setInterval(updateErrorLogs, 60000);
  </script>
</body>
</html>
