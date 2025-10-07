<x-app-layout>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern glassmorphism and gradient styles */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: none;
            margin: 0;
            margin-top: 10px;
        
           
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .gradient-button:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .modern-input {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }
        
        .modern-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .action-button {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        .animated-icon {
            transition: transform 0.3s ease;
        }
        
        .animated-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
        }
        
        /* Pulse animation for loading states */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Badge styles */
        .modern-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        /* Table row hover effects */
        .table-row-hover {
            transition: all 0.3s ease;
        }
        
        .table-row-hover:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            transform: scale(1.01);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Modern card grid */
        .member-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .member-card:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.95) 100%);
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        /* Profile image with gradient border */
        .profile-image-container {
            position: relative;
            padding: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
        }
        
        .profile-image {
            border-radius: 50%;
            background: white;
            padding: 2px;
        }
        
        /* Search input with enhanced styling */
        .search-input-container {
            position: relative;
        }
        
        .search-input-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .search-input-container:focus-within::after {
            width: 100%;
        }
        
        /* Current styles */
.flex-1.md\:w-80 {
    flex: 1 1 0%;
    min-width: 0;
    width: 100%;
}

/* Adjust the width and other properties */
.search-container {
    flex: 1 1 auto;  /* Makes it flexible */
    min-width: 0;    /* Prevents overflow */
    width: 100%;     /* Full width by default */
    max-width: 100%; /* Prevents overflow */
}

/* For medium screens and up */
@media (min-width: 768px) {
    .search-container {
        width: 24rem; /* Equivalent to w-96 */
        max-width: 100%;
    }
}
        /* Enhanced button styles */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: rgba(107, 114, 128, 0.1);
            border: 2px solid rgba(107, 114, 128, 0.2);
            color: #374151;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .btn-secondary:hover {
            background: rgba(107, 114, 128, 0.2);
            border-color: rgba(107, 114, 128, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(107, 114, 128, 0.2);
        }
        
        /* Enhanced filter section */
        .filter-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Stats counter animation */
        .stats-counter {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.25rem;
        }
        
        /* Toggle buttons with modern styling */
        .toggle-active {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
            color: white !important;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }
        
        .toggle-inactive {
            background: rgba(243, 244, 246, 0.8);
            color: #6b7280;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
        
        .toggle-inactive:hover {
            background: rgba(229, 231, 235, 0.8);
            transform: translateY(-1px);
        }
        
        /* Responsive grid improvements */
        @media (max-width: 640px) {
            .member-card {
                margin-bottom: 1rem;
            }
            
            .filter-section {
                padding: 1rem;
                border-radius: 15px;
            }
        }
        
        /* Enhanced table styling */
        .modern-table {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .table-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
        }
        
        /* Action buttons in table */
        .action-btn-view {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .action-btn-view:hover {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }
        
        .action-btn-edit {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .action-btn-edit:hover {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(245, 158, 11, 0.3);
        }
        
        .action-btn-archive {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .action-btn-archive:hover {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
        }
        
        .action-btn-unarchive {
            background: linear-gradient(135deg, #34C759, #2ECC71);
            color: white;
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .action-btn-unarchive:hover {
            background: linear-gradient(135deg, #2ECC71, #34C759);
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(52, 199, 89, 0.3);
        }
        
        /* Simple alternating color coding for member containers */
        .member-row-odd {
            background: linear-gradient(135deg, rgba(219, 234, 254, 0.8) 0%, rgba(191, 219, 254, 0.6) 100%);
            border-left: 4px solid #3b82f6;
        }
        
        .member-row-odd:hover {
            background: linear-gradient(135deg, rgba(219, 234, 254, 0.9) 0%, rgba(191, 219, 254, 0.7) 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        .member-row-even {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(29, 78, 216, 0.08) 100%);
            border-left: 4px solid #1e40af;
        }
        
        .member-row-even:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.12) 0%, rgba(29, 78, 216, 0.1) 100%);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.15);
        }
        
        /* Grid view alternating colors */
        .member-card-odd {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(219, 234, 254, 0.3) 100%);
            border: 2px solid #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }
        
        .member-card-odd:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(219, 234, 254, 0.4) 100%);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
            transform: translateY(-4px);
        }
        
        .member-card-even {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(37, 99, 235, 0.15) 100%);
            border: 2px solid #1e40af;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.1);
        }
        
        .member-card-even:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(37, 99, 235, 0.2) 100%);
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.2);
            transform: translateY(-4px);
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .member-row-odd,
            .member-row-even {
                border-left: 3px;
                padding: 12px !important;
            }
            
            .member-card-odd,
            .member-card-even {
                border-width: 1px;
                margin-bottom: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .member-row-odd,
            .member-row-even {
                border-left: 2px;
                padding: 8px !important;
            }
            
            .member-card {
                margin-bottom: 0.5rem;
            }
        }
        
        /* Shimmer animation */
        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        /* Status indicators */
        .status-indicator {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        .status-indicator-active {
            background: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
        }
        
        .status-indicator-inactive {
            background: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        
        .status-indicator-pending {
            background: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .member-row-active,
            .member-row-inactive,
            .member-row-pending {
                border-left: 3px;
                border-right: 3px;
            }
            
            .member-card-active,
            .member-card-inactive,
            .member-card-pending {
                border-width: 1px;
                margin-bottom: 0.75rem;
            }
            
            .status-indicator {
                width: 10px;
                height: 10px;
                top: 6px;
                right: 6px;
            }
        }
        
        @media (max-width: 480px) {
            .member-row-active,
            .member-row-inactive,
            .member-row-pending {
                border-left: 2px;
                border-right: 2px;
                padding: 8px !important;
            }
            
            .member-card {
                margin-bottom: 0.5rem;
            }
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .print-header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
            }
            
            .print-header h1 {
                margin: 0;
                color: #333;
            }
            
            .print-header p {
                margin: 5px 0 0 0;
                color: #666;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            
            th {
                background-color: #f5f5f5;
                font-weight: bold;
            }
            
            .status-active {
                color: #10b981;
                font-weight: bold;
            }
            
            .status-inactive {
                color: #ef4444;
                font-weight: bold;
            }
            
            .status-pending {
                color: #f59e0b;
                font-weight: bold;
            }
            
            .print-footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #666;
            }
        }
    </style>
    <script>
        function toggleView(view) {
            const tableView = document.getElementById('members-table-view');
            const gridView = document.getElementById('members-grid-view');
            const tableBtn = document.getElementById('table-view-btn');
            const gridBtn = document.getElementById('grid-view-btn');
            
            if (tableView && gridView && tableBtn && gridBtn) {
                if (view === 'table') {
                    tableView.style.display = '';
                    gridView.style.display = 'none';
                    tableBtn.classList.add('bg-blue-100', 'text-blue-700');
                    gridBtn.classList.remove('bg-blue-100', 'text-blue-700');
                    localStorage.setItem('membersViewPreference', 'table');
                } else {
                    tableView.style.display = 'none';
                    gridView.style.display = 'grid';
                    gridBtn.classList.add('bg-blue-100', 'text-blue-700');
                    tableBtn.classList.remove('bg-blue-100', 'text-blue-700');
                    localStorage.setItem('membersViewPreference', 'grid');
                }
            }
        }

        // Set initial view based on preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('membersViewPreference') || 'table';
            toggleView(savedView);
        });

        // Print functionality
        function printMembers() {
            // Get current filters to apply to print
            const currentUrl = new URL(window.location);
            const params = new URLSearchParams(currentUrl.search);
            
            // Create a new window for printing
            const printWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
            
            // Get the members table content
            const membersTable = document.getElementById('members-table-view');
            const membersGrid = document.getElementById('members-grid-view');
            
            let content = '';
            if (membersTable && membersTable.style.display !== 'none') {
                content = membersTable.innerHTML;
            } else if (membersGrid && membersGrid.style.display !== 'none') {
                content = membersGrid.innerHTML;
            }
            
            // Create print content with ultra-compact layout for single page
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Members List - ${new Date().toLocaleDateString()}</title>
                    <style>
                        * { box-sizing: border-box; }
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 0; 
                            padding: 10px; 
                            font-size: 9px;
                            line-height: 1.1;
                        }
                        .print-header { 
                            text-align: center; 
                            margin-bottom: 10px; 
                            border-bottom: 1px solid #333; 
                            padding-bottom: 5px; 
                        }
                        .print-header h1 { margin: 0; color: #333; font-size: 14px; }
                        .print-header p { margin: 2px 0 0 0; color: #666; font-size: 9px; }
                        table { 
                            width: 100%; 
                            border-collapse: collapse; 
                            margin-top: 8px;
                            font-size: 8px;
                        }
                        th, td { 
                            border: 0.5px solid #ccc; 
                            padding: 2px 3px; 
                            text-align: left; 
                            vertical-align: top;
                        }
                        th { 
                            background-color: #f5f5f5; 
                            font-weight: bold; 
                            font-size: 7px;
                            text-transform: uppercase;
                        }
                        .status-active { color: #10b981; font-weight: bold; }
                        .status-inactive { color: #ef4444; font-weight: bold; }
                        .status-pending { color: #f59e0b; font-weight: bold; }
                        .print-footer { 
                            margin-top: 10px; 
                            text-align: center; 
                            font-size: 8px; 
                            color: #666; 
                        }
                        .no-print { display: none !important; }
                        .print-toolbar {
                            position: fixed;
                            top: 10px;
                            right: 10px;
                            z-index: 1000;
                            background: white;
                            padding: 8px;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                        }
                        .print-toolbar button {
                            margin: 0 3px;
                            padding: 6px 12px;
                            border: none;
                            border-radius: 3px;
                            cursor: pointer;
                            font-size: 10px;
                        }
                        .print-btn {
                            background: #007bff;
                            color: white;
                        }
                        .close-btn {
                            background: #6c757d;
                            color: white;
                        }
                        .print-btn:hover { background: #0056b3; }
                        .close-btn:hover { background: #545b62; }
                        @media print {
                            .print-toolbar { display: none !important; }
                            body { margin: 0; padding: 5px; font-size: 8px; }
                            table { font-size: 7px; }
                            th, td { padding: 1px 2px; }
                            .print-header h1 { font-size: 12px; }
                            .print-header p { font-size: 8px; }
                            .print-footer { font-size: 7px; }
                        }
                        @page {
                            margin: 0.5in;
                            size: A4 landscape;
                        }
                    </style>
                </head>
                <body>
                    <div class="print-toolbar">
                        <button class="print-btn" onclick="window.print()">Print</button>
                        <button class="close-btn" onclick="window.close()">Close</button>
                    </div>
                    <div class="print-header">
                        <h1>Church Members List</h1>
                        <p>Generated on ${new Date().toLocaleString()}</p>
                        <p>Total Members: ${document.querySelectorAll('.member-row-active, .member-row-inactive, .member-row-pending').length}</p>
                    </div>
                    ${content}
                    <div class="print-footer">
                        <p>This document was generated from the Church Management System</p>
                    </div>
                </body>
                </html>
            `;
            
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
        }
    </script>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <x-page-header :icon="'fas fa-users'" title="Member Management" subtitle="Manage your church's members, filter by status, chapter, and more">
                        <div class="flex space-x-3">
                            <button onclick="printMembers()" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <i class="fas fa-print mr-2"></i>
                                Print
                            </button>
                            <a href="{{ route('members.download') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                        </div>
                    </x-page-header>
                    <!-- Header and Search Bar -->
                    <div class="flex flex-col space-y-4 mb-6 no-print">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                
                            

                        <form action="{{ route('members.index') }}" method="GET" id="searchForm" class="relative w-full">
                            <div class="relative flex w-full">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        id="memberSearch"
                                        value="{{ request('search') }}"
                                        placeholder="Search members by name, email, phone, or status..." 
                                        class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-l-lg bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base transition duration-150 ease-in-out"
                                        autocomplete="off"
                                    >
                                    @if(request('search'))
                                    <button type="button" 
                                            onclick="this.closest('form').querySelector('input[name=\'search\']').value = ''; this.closest('form').submit();"
                                            class="absolute right-0 top-0 bottom-0 px-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150"
                                >
                                    <span class="hidden sm:inline">Search</span>
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2 text-xs text-gray-500 flex items-center">
                                <span class="inline-flex items-center mr-3">
                                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-1"></span>
                                    <span>Type to search</span>
                                </span>
                                <span class="inline-flex items-center">
                                    <kbd class="px-1.5 py-0.5 text-xs font-mono bg-gray-100 rounded border border-gray-300 mr-1">/</kbd>
                                    <span>to focus</span>
                                </span>
                            </div>
                        </form>
                    </div>

                    <!-- Active Filters -->
                    @php
                        $activeFilters = array_filter([
                            'search' => request('search'),
                            'status' => request('status'),
                            'role' => request('role'),
                            'gender' => request('gender'),
                            'chapter_id' => request('chapter_id'),
                            'join_date_from' => request('join_date_from'),
                            'join_date_to' => request('join_date_to'),
                            'age_group' => request('age_group'),
                            'show_archived' => request('show_archived'),
                        ]);
                    @endphp

                    @if(count($activeFilters) > 0)
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                            @foreach($activeFilters as $key => $value)
                                @if($value)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}
                                    <a href="{{ route('members.index', array_merge(request()->except(['page', $key]))) }}" 
                                       class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-blue-200 text-blue-800 hover:bg-blue-300">
                                        <span class="sr-only">Remove filter</span>
                                        <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                                            <path fill-rule="evenodd" d="M4 3.293l2.146-2.147a.5.5 0 011.708.708L4.707 4l2.147 2.146a.5.5 0 01-.708.708L4 4.707l-2.146 2.147a.5.5 0 01-.708-.708L3.293 4 1.146 1.854a.5.5 0 01.708-.708L4 3.293z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </span>
                                @endif
                            @endforeach
                            <a href="{{ route('members.index') }}" 
                               class="ml-2 text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                Clear all
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Filters Toggle -->
                    <div class="mb-6 no-print">
                        <button id="filtersToggle" 
                                class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Filters</span>
                            <svg id="filtersChevron" class="w-4 h-4 ml-1 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Filters Panel -->
                    <div id="filtersPanel" class="hidden mb-6 p-4 bg-gray-200 rounded-lg">
                        <form id="filtersForm" action="{{ route('members.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="transfer" {{ request('status') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="work" {{ request('status') == 'work' ? 'selected' : '' }}>Work</option>
                                    <option value="deceased" {{ request('status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                                </select>
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select name="gender" id="gender" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Genders</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Age Group -->
                            <div>
                                <label for="age_group" class="block text-sm font-medium text-gray-700 mb-1">Age Group</label>
                                <select name="age_group" id="age_group" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Ages</option>
                                    @foreach($ageGroups as $value => $label)
                                        <option value="{{ $value }}" {{ request('age_group') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Chapter Filter -->
                            <div>
                                <label for="chapter_id" class="block text-sm font-medium text-gray-700 mb-1">Chapter</label>
                                <select name="chapter_id" id="chapter_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Chapters</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                            {{ $chapter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Show Archived Toggle -->
                            <div class="flex items-center">
                                <input type="checkbox" id="show_archived" name="show_archived" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       {{ request('show_archived') ? 'checked' : '' }}>
                                <label for="show_archived" class="ml-2 block text-sm text-gray-700">
                                    Show Archived Members
                                </label>
                            </div>

                            <div class="flex items-end space-x-3 md:col-span-2 pt-1">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2.5 bg-blue-100 from-blue-600 to-blue-700 text-gray-700 font-medium text-sm rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-blue-100">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                    Apply Filters
                                </button>
                                <a href="{{ route('members.index') }}" 
                                   class="inline-flex items-center px-4 py-2.5 bg-green-100 border border-gray-500 text-gray-700 font-medium text-sm rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Tabs for Active/Archived Members -->
                    <div class="flex border-b border-gray-200 mb-6 no-print">
                        <button type="button" 
                                class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm {{ !request('show_archived') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                data-tab="active">
                            Active Members
                        </button>
                        <button type="button" 
                                class="tab-button py-4 px-6 text-center border-b-2 font-medium text-sm {{ request('show_archived') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                data-tab="archived">
                            Archived Members
                        </button>
                    </div>

                    <!-- Members Count and View Toggle -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 no-print">
                        <p class="text-sm text-gray-600 mb-2 sm:mb-0">
                            Showing <span class="font-medium">{{ $members->firstItem() }}</span> to 
                            <span class="font-medium">{{ $members->lastItem() }}</span> of 
                            <span class="font-medium">{{ $members->total() }}</span> 
                            {{ request('show_archived') ? 'archived' : 'active' }} members
                        </p>
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button id="table-view-btn" onclick="toggleView('table')" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-blue-100 border border-gray-200 rounded-l-lg hover:bg-blue-200 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:bg-blue-100">
                                <i class="fas fa-table mr-1"></i> Table View
                            </button>
                            <button id="grid-view-btn" onclick="toggleView('grid')" type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-r border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500 focus:text-blue-700">
                                <i class="fas fa-th-large mr-1"></i> Grid View
                            </button>
                        </div>
                    </div>

                    <!-- Table View -->
                    <div id="members-table-view" class="flex flex-col h-[calc(100vh-350px)]">
                        <div class="overflow-x-auto flex-1">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden border border-gray-200 rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0 z-10">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" 
                                                    onclick="sortTable('name')">
                                                    Member
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Birthday
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Age
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Address
                                                </th>
                                              
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider no-print">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($members as $index => $member)
                                            <tr class="hover:bg-gray-50 {{ $index % 2 == 0 ? 'member-row-odd' : 'member-row-even' }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0">
                                                            @if($member->user && $member->user->profile_photo_path)
                                                                <img class="h-10 w-10 rounded-full" src="{{ $member->user->profile_photo_url }}" alt="{{ $member->name }}">
                                                            @else
                                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                                    <span class="text-gray-600 text-lg font-semibold">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $member->phone ?? 'N/A' }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $member->gender ?? 'N/A' }}
                                                        @if($member->birthday)
                                                            <span class="mx-1">•</span>
                                                            {{ \Carbon\Carbon::parse($member->birthday)->format('M d, Y') }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $category = $member->age_category;
                                                        $bgColor = match($category) {
                                                            'Kid' => 'bg-blue-100 text-blue-800',
                                                            'Youth' => 'bg-green-100 text-green-800',
                                                            'Adult' => 'bg-purple-100 text-purple-800',
                                                            'Senior' => 'bg-yellow-100 text-yellow-800',
                                                            default => 'bg-gray-100 text-gray-800'
                                                        };
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bgColor }}">
                                                        {{ $category }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $member->address ?? 'N/A' }}
                                                    </div>
                                                </td>
                                                
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if(strtolower($member->status) === 'active')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Active
                                                        </span>
                                                    @elseif(strtolower($member->status) === 'inactive')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Inactive
                                                        </span>
                                                    @elseif(strtolower($member->status) === 'pending')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Pending
                                                        </span>
                                                    @elseif(strtolower($member->status) === 'transfer')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Transfer
                                                        </span>
                                                    @elseif(strtolower($member->status) === 'work')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                            Work
                                                        </span>
                                                    @elseif(strtolower($member->status) === 'deceased')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Deceased
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            {{ $member->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium no-print">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <a href="{{ route('members.show', $member) }}" 
                                                           class="text-white bg-blue-500 hover:bg-blue-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                           data-tooltip="View Member"
                                                           data-tooltip-position="top">
                                                            <i class="fas fa-eye w-4 h-4"></i>
                                                        </a>
                                                        
                                                        @if($member->is_archived)
                                                            <form action="{{ route('members.unarchive', $member) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" 
                                                                        class="text-white bg-green-500 hover:bg-green-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                                        data-tooltip="Unarchive Member"
                                                                        data-tooltip-position="top"
                                                                        onclick="return confirm('Are you sure you want to unarchive this member?')">
                                                                    <i class="fas fa-box-open w-4 h-4"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('members.archive', $member) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" 
                                                                        class="text-white bg-gray-500 hover:bg-gray-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                                        data-tooltip="Archive Member"
                                                                        data-tooltip-position="top"
                                                                        onclick="return confirm('Are you sure you want to archive this member? They will be moved to the archived section.')">
                                                                    <i class="fas fa-archive w-4 h-4"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            @if(request('show_archived') && $members->isEmpty())
                                                <tr>
                                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                        No archived members found. <a href="{{ route('members.index') }}" class="text-blue-600 hover:text-blue-800">View active members</a>
                                                    </td>
                                                </tr>
                                            @elseif($members->isEmpty())
                                                <tr>
                                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                        No members found. 
                                                        @if(!request('show_archived'))
                                                            <a href="{{ route('members.index', ['show_archived' => true]) }}" class="text-blue-600 hover:text-blue-800">Check archived members</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-4 sticky bottom-0 bg-white pt-2 border-t border-gray-200">
                            <div class="pagination-container">
                                {{ $members->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- Grid View -->
                    <div id="members-grid-view" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-4">
                        @forelse($members as $index => $member)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full {{ $index % 2 == 0 ? 'member-card-odd' : 'member-card-even' }}">
                            <div class="p-4 flex-1">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex-shrink-0">
                                        @if($member->user && $member->user->profile_photo_path)
                                            <img class="h-12 w-12 rounded-full object-cover" src="{{ $member->user->profile_photo_url }}" alt="{{ $member->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $member->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-phone-alt mr-2"></i>
                                    <span>{{ $member->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    <span>Joined {{ $member->join_date?->format('M d, Y') ?? 'N/A' }}</span>
                                </div>
                                <div class="mt-2">
                                    @if(strtolower($member->status) === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @elseif(strtolower($member->status) === 'inactive')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @elseif(strtolower($member->status) === 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif(strtolower($member->status) === 'transfer')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Transfer
                                        </span>
                                    @elseif(strtolower($member->status) === 'work')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Work
                                        </span>
                                    @elseif(strtolower($member->status) === 'deceased')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Deceased
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $member->status }}
                                        </span>
                                    @endif
                                    @if($member->is_archived)
                                        <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Archived
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-2">
                                <a href="{{ route('members.show', $member) }}" 
                                   class="text-white bg-blue-500 hover:bg-blue-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                   data-tooltip="View Member">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </a>
                                
                                @if($member->is_archived)
                                    <form action="{{ route('members.unarchive', $member) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-white bg-green-500 hover:bg-green-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                data-tooltip="Unarchive Member"
                                                data-tooltip-position="top"
                                                onclick="return confirm('Are you sure you want to unarchive this member?')">
                                            <i class="fas fa-box-open w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('members.archive', $member) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-white bg-gray-500 hover:bg-gray-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                data-tooltip="Archive Member"
                                                data-tooltip-position="top"
                                                onclick="return confirm('Are you sure you want to archive this member? They will be moved to the archived section.')">
                                            <i class="fas fa-archive w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @empty
                            <div class="col-span-full text-center py-8">
                                <i class="fas fa-users-slash text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">
                                    @if(request('show_archived'))
                                        No archived members found. <a href="{{ route('members.index') }}" class="text-blue-600 hover:text-blue-800">View active members</a>
                                    @else
                                        No members found. 
                                        <a href="{{ route('members.index', ['show_archived' => true]) }}" class="text-blue-600 hover:text-blue-800">Check archived members</a>
                                    @endif
                                </p>
                            </div>
                        @endforelse
                        
                        <!-- Pagination for Grid View -->
                        <div class="mt-6 px-4">
                            {{ $members->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Custom styles for the pagination */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #3182ce;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            z-index: 2;
            color: #2c5282;
            background-color: #ebf8ff;
            border-color: #e2e8f0;
        }

        .pagination .active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #3182ce;
            border-color: #3182ce;
        }

        .pagination .disabled .page-link {
            color: #a0aec0;
            pointer-events: none;
            background-color: #fff;
            border-color: #e2e8f0;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Toggle filters panel
        document.addEventListener('DOMContentLoaded', function() {
            const filtersToggle = document.getElementById('filtersToggle');
            const filtersPanel = document.getElementById('filtersPanel');
            const filtersChevron = document.getElementById('filtersChevron');
            
            // Check local storage for filters panel state
            const filtersPanelState = localStorage.getItem('filtersPanelState');
            if (filtersPanelState === 'expanded') {
                filtersPanel.classList.remove('hidden');
                filtersChevron.classList.add('rotate-180');
            }

            filtersToggle.addEventListener('click', function() {
                filtersPanel.classList.toggle('hidden');
                filtersChevron.classList.toggle('rotate-180');
                
                // Save state to local storage
                if (filtersPanel.classList.contains('hidden')) {
                    localStorage.setItem('filtersPanelState', 'collapsed');
                } else {
                    localStorage.setItem('filtersPanelState', 'expanded');
                }
            });

            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-tooltip]');
            
            tooltips.forEach(tooltip => {
                const tooltipText = tooltip.getAttribute('data-tooltip');
                const position = tooltip.getAttribute('data-tooltip-position') || 'top';
                
                const tooltipElement = document.createElement('div');
                tooltipElement.className = `hidden absolute z-10 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded shadow-lg tooltip-${position}`;
                tooltipElement.textContent = tooltipText;
                
                tooltip.style.position = 'relative';
                tooltip.style.display = 'inline-flex';
                tooltip.style.alignItems = 'center';
                tooltip.style.justifyContent = 'center';
                
                tooltip.appendChild(tooltipElement);
                
                tooltip.addEventListener('mouseenter', () => {
                    tooltipElement.classList.remove('hidden');
                    const rect = tooltip.getBoundingClientRect();
                    const tooltipRect = tooltipElement.getBoundingClientRect();
                    
                    switch(position) {
                        case 'top':
                            tooltipElement.style.bottom = '100%';
                            tooltipElement.style.left = '50%';
                            tooltipElement.style.transform = 'translateX(-50%)';
                            tooltipElement.style.marginBottom = '5px';
                            break;
                        // Add other positions if needed
                        default:
                            break;
                    }
                });
                
                tooltip.addEventListener('mouseleave', () => {
                    tooltipElement.classList.add('hidden');
                });
            });
        });
    </script>
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tab = this.dataset.tab;
                    const url = new URL(window.location.href);
                    
                    if (tab === 'archived') {
                        url.searchParams.set('show_archived', '1');
                    } else {
                        url.searchParams.delete('show_archived');
                    }
                    
                    window.location.href = url.toString();
                });
            });
        });
    </script>
    <style>
        .tooltip-top:after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #1F2937 transparent transparent transparent;
        }
    </style>
    @endpush

</x-app-layout>