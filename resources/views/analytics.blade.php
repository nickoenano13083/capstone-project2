<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .analytics-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            height: 100%;
            color: #1f2937;
        }

        .dark .analytics-card {
            background-color: var(--widget-bg-color, #334155);
            color: var(--text-primary, #f1f5f9);
            border: 1px solid #475569;
        }
        .progress-bar-custom {
            height: 8px;
            background-color: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 6px;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .summary-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
        }
        .summary-card .icon {
            font-size: 24px;
            margin-bottom: 10px;
            color: #4f46e5;
        }
        h5 {
            color: #1f2937;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 16px;
        }

        .dark h5 {
            color: var(--text-primary, #f1f5f9);
        }
        .analytics-header {
            background-color:rgb(35, 58, 101);
            color: #2D3748;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }

        .dark .analytics-header {
            background-color: var(--widget-bg-color, #334155);
            color: var(--text-primary, #f1f5f9);
            border: 1px solid #475569;
        }
        .analytics-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            color:rgb(212, 220, 237);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .analytics-header h1 i {
            color:rgb(206, 205, 239);
            font-size: 1.3em;
        }
        .analytics-header p {
            font-size: 0.9rem;
            color:rgb(197, 213, 234);
            margin: 0;
            line-height: 1.5;
        }

        .dark .analytics-header h1 {
            color: var(--text-primary, #f1f5f9);
        }
        .dark .analytics-header h1 i {
            color: var(--text-primary, #f1f5f9);
        }
        .dark .analytics-header p {
            color: var(--text-secondary, #94a3b8);
        }
    </style>

    <div class="bg-gray-50 p-4" style="background: var(--body-bg-color, #f0f2f5); color: var(--text-primary, #333);">
        @if(auth()->user() && auth()->user()->role === 'Admin')
            <header class="analytics-header">
                <h1><i class="fas fa-chart-pie"></i> Church Analytics Dashboard</h1>
                <p>Track and analyze your church's growth and engagement metrics</p>
            </header>
            
            <!-- Top Row: Members by Age Group and Prayer Request Status -->
            <div class="flex flex-col lg:flex-row gap-6 mb-6">
                <!-- Members by Age Group -->
                <div class="flex-1">
                    <div class="analytics-card h-full">
                        <h5 class="mb-4">Members by Age Group</h5>
                        <div class="chart-container">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Prayer Request Status -->
                <div class="flex-1">
                    <div class="analytics-card h-full">
                        <h5 class="mb-4">Prayer Request Status</h5>
                        <div class="flex flex-col lg:flex-row gap-4">
                            <div class="chart-container flex-1">
                                <canvas id="prayerRequestChart"></canvas>
                            </div>
                            <div class="flex flex-col justify-center gap-4 w-full lg:w-1/3">
                                <div class="text-center p-3 rounded-lg bg-gray-100">
                                    <div class="text-2xl font-bold text-yellow-400">{{ $prayerStats['pending'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-400">Pending</div>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-gray-100">
                                    <div class="text-2xl font-bold text-green-400">{{ $prayerStats['answered'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-400">Answered</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row: Overall Members and Members by Gender -->
            <div class="flex flex-col lg:flex-row gap-6 mb-6 mt-6">
                <!-- Overall Members Chart -->
                <div class="flex-1">
                    <div class="analytics-card h-full">
                        <div class="flex justify-between items-center mb-4">
                            <h5>Overall Members</h5>
                            <span class="text-sm text-gray-400">Total: {{ $totalMembers }}</span>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-4">
                            <div class="chart-container flex-1">
                                <canvas id="overallScoreChart"></canvas>
                            </div>
                            <div class="flex flex-col justify-center gap-4 w-full lg:w-1/3">
                                <div class="text-center p-3 rounded-lg bg-gray-100">
                                    <div class="text-2xl font-bold text-indigo-400">{{ $genderStats['male'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-400">Male</div>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-gray-100">
                                    <div class="text-2xl font-bold text-pink-400">{{ $genderStats['female'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-400">Female</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Members by Gender -->
                <div class="flex-1">
                    <div class="analytics-card h-full">
                        <h5 class="mb-4">Members by Gender</h5>
                        <div class="flex flex-col lg:flex-row gap-4">
                            <div class="chart-container flex-1">
                                <canvas id="genderChart"></canvas>
                            </div>
                            <div class="flex flex-col justify-center gap-4 w-full lg:w-1/3">
                                <div class="text-center p-3 rounded-lg bg-gray-100">
                                    <div class="text-2xl font-bold text-indigo-400">{{ $genderStats['male'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-400">Male</div>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-gray-100">
                                    <div class="text-2xl font-bold text-pink-400">{{ $genderStats['female'] ?? 0 }}</div>
                                    <div class="text-sm text-gray-400">Female</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row: Top Chapters by Members and Event & Attendance Trends -->
            <div class="flex flex-col lg:flex-row gap-6 mb-6">
                <!-- Top Chapters by Members -->
                <div class="flex-1">
                    <div class="analytics-card h-full">
                        <div class="flex justify-between items-center mb-4">
                            <h5>Top Chapters by Members</h5>
                            <span class="text-sm text-gray-400">{{ count($chapterStats) }} chapters</span>
                        </div>
                        <div class="space-y-4">
                            @foreach($chapterStats as $chapter)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-gray-600">{{ $chapter['name'] }}</span>
                                    <span class="text-gray-500">{{ $chapter['members'] }} members</span>
                                </div>
                                <div class="progress-bar-custom">
                                    <div class="progress-bar-fill" style="width: {{ $totalMembers > 0 ? ($chapter['members'] / $totalMembers) * 100 : 0 }}%;"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Event & Attendance Trends -->
                <div class="flex-1">
                    <div class="analytics-card h-full">
                        <h5 class="mb-4">Event & Attendance Trends</h5>
                        <div class="chart-container">
                            <canvas id="trendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const totalMembers = {{ $totalMembers }};
                    const genderStats = @json($genderStats);
                    const ageGroups = @json($ageGroups);
                    const prayerStats = @json($prayerStats);
                    const eventsPerMonth = @json($eventsPerMonth);
                    const attendanceTrend = @json($attendanceTrend);
                    
                    const isDark = document.documentElement.classList.contains('dark');
                    const chartTextColor = isDark ? '#f1f5f9' : '#1f2937';
                    const chartGridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

                    const overallScoreChartCtx = document.getElementById('overallScoreChart').getContext('2d');
                    new Chart(overallScoreChartCtx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [totalMembers, 0], // A bit of a hack to make a full circle
                                backgroundColor: ['#28a745', '#f0f0f0'],
                                borderColor: '#ffffff',
                                borderWidth: 2,
                                cutout: '70%',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: false }
                            }
                        },
                        plugins: [{
                            id: 'centerText',
                            afterDraw: function(chart) {
                                let ctx = chart.ctx;
                                ctx.save();
                                let centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                                let centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                                
                                ctx.font = 'bold 48px sans-serif';
                                ctx.fillStyle = isDark ? '#f1f5f9' : '#1f2937';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(totalMembers, centerX, centerY);
                                ctx.restore();
                            }
                        }]
                    });

                    // Gender Chart
                    new Chart(document.getElementById('genderChart'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Male', 'Female', 'Other'],
                            datasets: [{
                                data: [genderStats.male, genderStats.female, genderStats.other],
                                backgroundColor: ['#6366f1', '#f472b6', '#facc15'],
                                borderColor: '#ffffff',
                                borderWidth: 4,
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { color: chartTextColor } },
                            }
                        }
                    });

                    // Age Group Chart
                    new Chart(document.getElementById('ageChart'), {
                        type: 'bar',
                        data: {
                            labels: ['Kids (3-12)', 'Youth (13-25)', 'Adults (26-59)', 'Seniors (60+)'],
                            datasets: [{
                                label: 'Number of Members',
                                data: [
                                    {{ $ageGroups['3-12'] ?? 0 }},
                                    {{ $ageGroups['13-25'] ?? 0 }},
                                    {{ $ageGroups['26-59'] ?? 0 }},
                                    {{ $ageGroups['60+'] ?? 0 }}
                                ],
                                backgroundColor: [
                                    'rgba(99, 102, 241, 0.7)',
                                    'rgba(139, 92, 246, 0.7)',
                                    'rgba(167, 139, 250, 0.7)',
                                    'rgba(199, 210, 254, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(99, 102, 241, 1)',
                                    'rgba(139, 92, 246, 1)',
                                    'rgba(167, 139, 250, 1)',
                                    'rgba(199, 210, 254, 1)'
                                ],
                                borderWidth: 1,
                                categoryPercentage: 0.8,
                                barPercentage: 0.9
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.parsed.y + ' members';
                                        },
                                        title: function(tooltipItems) {
                                            return tooltipItems[0].label;
                                        }
                                    }
                                },
                                datalabels: {
                                    display: true,
                                    color: isDark ? '#f1f5f9' : '#1f2937',
                                    anchor: 'end',
                                    align: 'top',
                                    formatter: function(value) {
                                        return value + ' members';
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: chartTextColor,
                                        stepSize: 1,
                                        callback: function(value) {
                                            if (value % 1 === 0) {
                                                return value;
                                            }
                                        }
                                    },
                                    grid: {
                                        color: chartGridColor,
                                        drawBorder: false
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: chartTextColor
                                    },
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });

                    // Prayer Request Chart
                    new Chart(document.getElementById('prayerRequestChart'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Pending', 'Answered'],
                            datasets: [{
                                data: [prayerStats.pending, prayerStats.answered],
                                backgroundColor: ['#f59e42', '#34d399'],
                                borderColor: '#ffffff',
                                borderWidth: 4,
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { color: chartTextColor } },
                            }
                        }
                    });

                    // Trends Chart
                    new Chart(document.getElementById('trendsChart'), {
                        type: 'line',
                        data: {
                            labels: eventsPerMonth.map(e => e.month.substring(0, 3)),
                            datasets: [
                                {
                                    label: 'Events',
                                    data: eventsPerMonth.map(e => e.count),
                                    borderColor: '#818cf8',
                                    tension: 0.3,
                                },
                                {
                                    label: 'Attendance',
                                    data: attendanceTrend.map(a => a.count),
                                    borderColor: '#34d399',
                                    tension: 0.3,
                                }
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: true, labels: { color: chartTextColor } } },
                            scales: {
                                y: { beginAtZero: true, ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
                                x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }
                            }
                        }
                    });
                });
            </script>
        @else
            <div class="alert alert-danger">
                <strong>Access Denied:</strong> You do not have permission to view this page. Only administrators can view analytics.
            </div>
        @endif
    </div>
</x-app-layout> 