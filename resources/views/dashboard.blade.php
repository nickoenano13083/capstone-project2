<x-app-layout>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --body-bg-color: #F0F2F5;
            --sidebar-bg-color: #ffffff;
            --main-header-bg: #ffffff;
            --widget-bg-color:rgb(243, 234, 234);
            --text-primary: #333;
            --text-secondary: #777;
            --accent-color: #2D5BFF;
            --accent-color-dark: #2C3E50;
            --card-border-radius: 12px;
            --card-box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .dark {
            --body-bg-color: #0f172a;
            --sidebar-bg-color: #1e293b;
            --main-header-bg: #1e293b;
            --widget-bg-color: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-color: #3b82f6;
            --accent-color-dark: #1e40af;
            --card-border-radius: 12px;
            --card-box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        body {
            background: var(--body-bg-color) !important;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            transition: background-color 0.3s ease;
        }

        .dark body {
            background: linear-gradient(to bottom, #0f172a, #1e293b) !important;
        }

        .dark .dashboard-main-content {
            background: linear-gradient(to bottom, #0f172a, #1e293b) !important;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        .dashboard-main-content {
            padding: 24px;
        }

        .dashboard-header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            background-color: var(--main-header-bg);
            padding: 16px 24px;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-box-shadow);
            margin-bottom: 24px;
            gap: 20px;
        }
        

        .dashboard-header h1 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .search-bar {
            flex-grow: 2;
            max-width: 800px;
            min-width: 500px;
            margin-left: 10px; /* Close to Dashboard text */
        }

        .search-bar input {
            width: 100%;
            padding: 10px 16px 10px 40px; /* Left padding for icon space */
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .dark .search-bar input {
            background-color: #475569;
            border-color: #64748b;
            color: var(--text-primary);
            padding: 10px 16px 10px 40px; /* Maintain left padding for icon space */
        }

        .dark .search-bar input::placeholder {
            color: var(--text-secondary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0; /* Prevent shrinking */
            margin-left: auto; /* Push to the right side */
        }

        .notification-bell {
            position: relative;
            font-size: 24px;
            color: var(--text-secondary);
        }

        .notification-bell .badge {
            position: absolute;
            top: -4px;
            right: -8px;
            background-color: #ef4444;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }
        
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--accent-color-dark);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
        }

        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--widget-bg-color);
            color: var(--text-primary);
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .theme-toggle-btn:hover {
            background-color: var(--accent-color);
            color: white;
            transform: scale(1.05);
        }

        .dark .theme-toggle-btn {
            background-color: #475569;
            border-color: #64748b;
            color: #f1f5f9;
        }

        .dark .theme-toggle-btn:hover {
            background-color: var(--accent-color);
            color: white;
        }

        .dark .notification-dropdown,
        .dark .profile-dropdown {
            background: var(--main-header-bg) !important;
            border-color: #475569 !important;
            color: var(--text-primary) !important;
        }

        .dark .notification-dropdown strong,
        .dark .profile-dropdown strong {
            color: var(--text-primary) !important;
        }

        .dark .notification-dropdown hr {
            border-color: #475569 !important;
        }

        .dark .profile-dropdown div[style*="color:#888"] {
            color: var(--text-secondary) !important;
        }

        .dark .profile-dropdown a {
            color: var(--accent-color) !important;
        }

        .top-banner {
            background-color:rgb(35, 58, 101);
            border-radius: var(--card-border-radius);
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            margin-bottom: 20px;
        }
        
        .church-info h2 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        
        .church-info p {
            font-size: 14px;
            opacity: 0.8;
            margin: 4px 0 0;
        }

        .stats-grid {
            display: flex;
            gap: 16px;
        }

        .stat-card {
            background-color: rgba(157, 154, 233, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 150px;
            backdrop-filter: blur(5px);
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: 700;
        }

        .stat-card .label {
            font-size: 14px;
            font-weight: 500;
        }
        
        .stat-card .icon {
            font-size: 24px;
            opacity: 0.8;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 24px;
        }

        .grid-col-4 { grid-column: span 4; }
        .grid-col-5 { grid-column: span 5; }
        .grid-col-3 { grid-column: span 3; }

        /* Religious theme background for widget */
        .widget {
            background-color: var(--widget-bg-color);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 20px 20px 16px 20px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            color: var(--text-primary);
            border: 1px solid rgba(15, 23, 42, 0.06);
            position: relative;
        }
        .widget:hover {
            box-shadow: 0 14px 40px rgba(15,23,42,0.15);
            transform: translateY(-3px);
        }

        .dark .widget {
            background-color: var(--widget-bg-color);
            border: 1px solid #475569;
        }
        .right-widget-header {
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .birthdays-list .list-item {
            margin-bottom: 18px;
            padding: 8px 0;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .birthdays-list .list-item.today {
            background: linear-gradient(90deg, #e0e7ff 60%, #f0f4ff 100%);
            box-shadow: 0 2px 8px #7aa2f733;
        }
        .birthdays-list .avatar-placeholder {
            background: linear-gradient(135deg, #7aa2f7 60%, #a5b4fc 100%);
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .birthdays-list .info .name {
            font-weight: 700;
            font-size: 1.05rem;
        }
        .birthdays-list .info .detail {
            font-size: 0.95rem;
            color: #7a7a7a;
        }
        .new-signups-grid {
            margin-bottom: 18px;
            gap: 10px;
        }
        .new-signups-grid img,
        .new-signups-grid .avatar-placeholder {
            border: 2px solid #e0e7ff;
            transition: border 0.2s, transform 0.2s;
        }
        .new-signups-grid img:hover,
        .new-signups-grid .avatar-placeholder:hover {
            border: 2px solid #7aa2f7;
            transform: scale(1.08);
        }
        .new-signups-list table {
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
            font-size: 0.98rem;
        }
        .new-signups-list th, .new-signups-list td {
            padding: 8px 10px;
            text-align: left;
        }
        .new-signups-list tr {
            transition: background 0.2s;
        }
        .new-signups-list tr:hover {
            background: #e0e7ff;
        }
        .new-signups-list th {
            background: #e0e7ff;
            font-weight: 700;
        }

        .people-count-widget {
            background-color:rgb(35, 58, 101);
            color: white;
            text-align: center;
        }

        .people-count-widget .total-circle {
            width: 120px;
            height: 120px;
            border: 5px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .people-count-widget .total-circle .number {
            font-size: 36px;
            font-weight: 700;
        }
        .people-count-widget .total-circle .label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        
        .people-breakdown {
            display: flex;
            justify-content: space-around;
            margin-bottom: 24px;
        }
        
        .people-breakdown div {
            text-align: center;
        }

        .people-breakdown .label {
            font-size: 16px;
        }
        
        .people-breakdown .number {
            font-size: 24px;
            font-weight: 600;
        }
        
        .request-btn {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #4A5C78;
            color: white;
            padding: 16px;
            border-radius: 10px;
            margin-top: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .request-btn:hover {
            background-color: #5A6C88;
        }

        .request-btn .badge {
            background-color: #ef4444;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
        }
        
        .widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px dashed rgba(15,23,42,0.12);
        }

        .widget-header h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .widget .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            background: #eef2ff;
            color: #4338ca;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .upcoming-event-widget .event-tag {
            background-color: #eef2ff;
            color: var(--accent-color);
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 12px;
        }
        
        .upcoming-event-widget .event-image {
            width: 100%;
            height: 150px;
            background: url('{{ asset('images/junetheme.png') }}') no-repeat center center;
            background-size: cover;
            border-radius: 10px;
            margin-bottom: 16px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 16px;
            position: relative;
        }
        .upcoming-event-widget .event-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(91, 68, 181, 0.66), transparent);
            border-radius: 10px;
        }
        
        .upcoming-event-widget .event-image * {
            position: relative;
            z-index: 1;
        }
        
        .upcoming-event-widget .event-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .upcoming-event-widget .event-date {
            font-size: 14px;
        }
        
        .activity-feed .feed-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .activity-feed .feed-item:last-child {
            margin-bottom: 0;
        }

        .activity-feed .item-icon {
            font-size: 16px;
            color: var(--accent-color);
            background-color: #eef2ff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .activity-feed .item-content p {
            margin: 0;
            line-height: 1.5;
            color: var(--text-primary);
        }
        .activity-feed .item-content .meta {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .right-widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .right-widget-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .header-actions-icons {
            display: flex;
            gap: 8px;
        }
        
        .header-actions-icons .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background-color: #eef2ff;
            color: var(--accent-color-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .header-actions-icons .icon-btn:hover {
            background-color: #dde5ff;
        }
        
        .list-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .list-item img, .list-item .avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .list-item .avatar-placeholder {
            background-color: #e2e8f0;
        }
        
        .list-item .info .name {
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .list-item .info .detail {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .new-signups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .new-signups-grid img {
            width: 100%;
            height: auto;
            aspect-ratio: 1/1;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .view-all-btn {
            width: 100%;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .view-all-btn:hover {
            background-color: #f1f5f9;
        }

        .quick-access-section {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            width: 100%;
            gap: 16px;
        }

        .quick-access-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, #2D5BFF 0%, #2C3E50 100%);
            box-shadow: 0 4px 15px rgba(45, 91, 255, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-width: 200px;
            max-width: 300px;
            animation: pulse-glow 2s ease-in-out infinite alternate;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        @keyframes pulse-glow {
            0% {
                box-shadow: 0 4px 15px rgba(45, 91, 255, 0.4), 0 0 20px rgba(45, 91, 255, 0.2);
            }
            100% {
                box-shadow: 0 4px 15px rgba(45, 91, 255, 0.6), 0 0 30px rgba(45, 91, 255, 0.4);
            }
        }

        .quick-access-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .quick-access-btn:hover::before {
            left: 100%;
        }

        .quick-access-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 91, 255, 0.6);
            animation: none;
        }

        .btn-icon {
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            flex-shrink: 0;
        }

        .btn-content {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .btn-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .btn-subtitle {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 400;
        }

        @media (max-width: 768px) {
            .quick-access-section {
                margin-top: 16px;
            }
            
            .quick-access-btn {
                padding: 12px 20px;
                min-width: 180px;
                max-width: 280px;
            }
            
            .btn-icon {
                font-size: 20px;
                width: 36px;
                height: 36px;
            }
            
            .btn-title {
                font-size: 14px;
            }
            
            .btn-subtitle {
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            .quick-access-section {
                margin-top: 12px;
                gap: 12px;
            }
            
            .quick-access-btn {
                padding: 10px 16px;
                min-width: 160px;
                max-width: 240px;
            }
            
            .btn-icon {
                font-size: 18px;
                width: 32px;
                height: 32px;
            }
            
            .btn-title {
                font-size: 13px;
            }
            
            .btn-subtitle {
                font-size: 10px;
            }
        }

        @media (max-width: 360px) {
            .quick-access-section {
                margin-top: 10px;
                gap: 10px;
            }
            
            .quick-access-btn {
                padding: 8px 14px;
                min-width: 140px;
                max-width: 200px;
            }
            
            .btn-icon {
                font-size: 16px;
                width: 28px;
                height: 28px;
            }
            
            .btn-title {
                font-size: 12px;
            }
            
            .btn-subtitle {
                font-size: 9px;
            }
        }

        /* Medium desktop screens */
        @media (min-width: 1024px) {
            .search-bar {
                max-width: 900px;
                min-width: 600px;
                flex-grow: 3;
                margin-left: 15px; /* Slightly more space on larger screens */
            }
        }

        /* Large desktop screens */
        @media (min-width: 1280px) {
            .search-bar {
                max-width: 1000px;
                min-width: 700px;
                flex-grow: 4;
                margin-left: 20px; /* More space on larger screens */
            }
        }

        /* Extra large desktop screens */
        @media (min-width: 1440px) {
            .search-bar {
                max-width: 1200px;
                min-width: 800px;
                flex-grow: 5;
                margin-left: 25px; /* Even more space on extra large screens */
            }
        }

        @media (max-width: 1200px) {
            .search-bar {
                margin: 0 24px;
            }
            .grid-col-4, .grid-col-5, .grid-col-3 {
                grid-column: span 12;
            }
        }

        @media (max-width: 992px) {
            .top-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            .stats-grid {
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-header h1 { 
                display: none; 
            }
            .search-bar { 
                order: 2; /* Move search bar to middle */
                width: 100% !important; /* Full width on mobile - override desktop styles */
                max-width: 100% !important; /* Override desktop max-width */
                min-width: auto !important; /* Override desktop min-width */
                flex-grow: 1 !important; /* Override desktop flex-grow */
                margin-left: 0 !important; /* Override desktop margin-left */
                background: rgba(255, 255, 255, 0.9); /* Subtle background for better visibility */
                padding: 8px; /* Add padding around search input */
                border-radius: 8px; /* Rounded corners */
                margin: 0; /* Remove margins */
            }
            .search-bar input {
                font-size: 16px; /* Prevent zoom on iOS */
                padding: 12px 16px 12px 40px; /* Larger touch target with icon space */
                min-height: 44px; /* Minimum touch target size */
                border: 2px solid #e2e8f0; /* Slightly thicker border for better visibility */
            }
            
            .search-bar input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            .header-actions {
                order: 3; /* Move actions to end */
                gap: 8px; /* Slightly larger gap for better touch targets */
            }
            .dashboard-header {
                flex-direction: row; /* Keep horizontal layout */
                align-items: center; /* Center align items */
                padding: 12px 16px; /* Reduce padding on mobile */
                gap: 12px; /* Reduce gap on mobile */
            }
        }

        .prayer-banner-enhanced {
            background: linear-gradient(90deg, #0f223a 60%, #3a5a99 100%);
            border-radius: 20px;
            box-shadow: 0 4px 24px #0002;
            padding: 48px 32px 32px 32px;
            color: #fff;
            width: 100%;
            max-width: 98vw;
            position: relative;
            text-align: center;
            overflow: hidden;
        }
        .prayer-banner-enhanced::before {
            content: '';
            position: absolute;
            right: 30px; bottom: 10px;
            width: 120px; height: 120px;
            background: url('data:image/svg+xml;utf8,<svg fill="%23fff" fill-opacity="0.07" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><path d="M32 2v60M2 32h60" stroke="%23fff" stroke-width="4" stroke-linecap="round"/></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
        }
        .prayer-quote {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }
        .quote-icon {
            font-size: 1.5rem;
            color: #ffe082;
            margin-right: 8px;
            vertical-align: top;
        }
        .prayer-ref {
            text-align: center;
            font-size: 1.2rem;
            font-style: italic;
            color: #ffe082;
            font-weight: 600;
            margin-bottom: 10px;
            z-index: 1;
            position: relative;
        }
        .prayer-actions {
            text-align: left;
            margin-top: 10px;
            z-index: 1;
            position: relative;
        }
        .prayer-btn {
            background: #fff2;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 16px;
            margin-right: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .prayer-btn:hover {
            background: #ffe082;
            color: #1e293b;
        }
        .prayer-btn.danger {
            background: #ef4444;
            color: #fff;
        }
        .prayer-btn.danger:hover {
            background: #b91c1c;
            color: #fff;
        }
        /* Modal styles */
        .modal {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }
        .modal-content {
            background: var(--main-header-bg);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 24px;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--card-box-shadow);
            position: relative;
            transition: all 0.3s ease;
        }

        .dark .modal-content {
            background: var(--main-header-bg);
            border: 1px solid #475569;
        }
        .modal-content h3, .modal-content h4 {
            margin-top: 0;
            color: var(--text-primary);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .modal-content p {
            margin-bottom: 20px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .dark .modal-content h3, .dark .modal-content h4 {
            color: var(--text-primary);
            border-bottom-color: #475569;
        }

        .dark .modal-content p {
            color: var(--text-secondary);
        }
        .modal-content form {
            margin-top: 20px;
        }
        .modal-content textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 12px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: var(--main-header-bg);
            color: var(--text-primary);
        }
        .modal-content textarea:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
        }
        .modal-content input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 16px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: var(--main-header-bg);
            color: var(--text-primary);
        }
        .modal-content input[type="text"]:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
        }

        .dark .modal-content textarea,
        .dark .modal-content input[type="text"] {
            background-color: #475569;
            border-color: #64748b;
            color: var(--text-primary);
        }

        .dark .modal-content textarea::placeholder,
        .dark .modal-content input[type="text"]::placeholder {
            color: var(--text-secondary);
        }

        .dark .modal-content label {
            color: var(--text-primary) !important;
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }
        .prayer-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .prayer-btn i {
            font-size: 0.9em;
        }
        .prayer-btn.primary {
            background: #4f46e5;
            color: white;
        }
        .prayer-btn.primary:hover {
            background: #4338ca;
        }
        .prayer-btn.danger {
            background: #fef2f2;
            color: #dc2626;
        }
        .prayer-btn.danger:hover {
            background: #fee2e2;
        }
        .prayer-btn.secondary {
            background: #f1f5f9;
            color: #475569;
        }
        .prayer-btn.secondary:hover {
            background: #e2e8f0;
        }
        .analytics-card {
            background: var(--widget-bg-color);
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--card-box-shadow);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            color: var(--text-primary);
        }
        .analytics-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .dark .analytics-card {
            background: var(--widget-bg-color);
            border: 1px solid #475569;
            color: var(--text-primary);
        }
        .analytics-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
            margin: 8px 0 4px;
        }
        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .dark .stat-value {
            color: var(--text-primary);
        }
        .dark .stat-label {
            color: var(--text-secondary);
        }
        .stat-trend {
            font-size: 0.875rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }
        .progress-bar {
            height: 4px;
            background:rgb(194, 203, 212);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 16px;
        }
        .progress-fill {
            height: 100%;
            border-radius: 2px;
        }
        
        .chart-container {
            position: relative;
            height: 160px;
            margin-top: 15px;
        }
        
        .chart-legend {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 5px;
            text-align: center;
        }

        .dark .chart-legend {
            color: var(--text-secondary);
        }

        /* Dark mode styles for search results */
        .dark .search-results-section h4 {
            color: var(--text-primary) !important;
        }

        .dark .search-results-section .space-y-2 > div {
            background-color: var(--widget-bg-color) !important;
            border: 1px solid #475569;
        }

        .dark .search-results-section .space-y-2 > div:hover {
            background-color: #475569 !important;
        }

        .dark .search-results-section .font-medium {
            color: var(--text-primary) !important;
        }

        .dark .search-results-section .text-sm {
            color: var(--text-secondary) !important;
        }

        .dark .search-results-section .text-center {
            color: var(--text-secondary) !important;
        }

        /* Dark mode styles for widgets */
        .dark .widget h3 {
            color: var(--text-primary) !important;
        }

        .dark .widget p {
            color: var(--text-secondary) !important;
        }

        .dark .widget .name {
            color: var(--text-primary) !important;
        }

        .dark .widget .detail {
            color: var(--text-secondary) !important;
        }

        .dark .widget .meta {
            color: var(--text-secondary) !important;
        }

        .dark .widget .list-item {
            color: var(--text-primary) !important;
        }

        .dark .widget .view-all-btn {
            background-color: var(--widget-bg-color) !important;
            border-color: #475569 !important;
            color: var(--text-primary) !important;
        }

        .dark .widget .view-all-btn:hover {
            background-color: #475569 !important;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        @media (max-width: 480px) {
            .dashboard-header {
                padding: 8px 12px;
                gap: 8px;
            }

            .search-bar {
                width: 100% !important; /* Ensure full width on small mobile */
                max-width: 100% !important; /* Override desktop styles */
                min-width: auto !important; /* Override desktop min-width */
                margin: 0; /* Remove margins */
            }

            .search-bar input {
                padding: 10px 12px 10px 40px; /* Slightly smaller padding with icon space */
                font-size: 16px; /* Prevent zoom on iOS */
                border-radius: 6px; /* Smaller radius for very small screens */
            }

            .header-actions {
                gap: 6px; /* Reduce gap on smaller screens */
            }

            .notification-bell {
                font-size: 18px;
            }

            .profile-avatar {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }
        }

        @media (max-width: 360px) {
            .dashboard-header {
                padding: 6px 8px;
                gap: 6px;
            }

            .search-bar {
                width: 100% !important; /* Ensure full width on very small mobile */
                max-width: 100% !important; /* Override desktop styles */
                min-width: auto !important; /* Override desktop min-width */
                margin: 0; /* Remove margins */
            }

            .search-bar input {
                padding: 8px 10px 8px 40px; /* Smaller padding with icon space */
                font-size: 16px; /* Keep 16px to prevent zoom */
                border-radius: 4px; /* Smaller radius for very small screens */
            }

            .search-bar input::placeholder {
                font-size: 14px; /* Slightly larger placeholder text */
            }

            .header-actions {
                gap: 4px; /* Minimal gap for very small screens */
            }

            .notification-bell {
                font-size: 16px;
            }

            .notification-bell .badge {
                width: 16px;
                height: 16px;
                font-size: 10px;
                top: -2px;
                right: -4px;
            }

            .profile-avatar {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            .theme-toggle-btn {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="search"],
            textarea,
            select {
                font-size: 16px !important;
            }
            
            /* Ensure search input has proper padding for icon */
            .search-bar input[type="text"] {
                padding-left: 40px !important;
            }
            
            @media (max-width: 360px) {
                input[type="text"],
                input[type="email"],
                input[type="password"],
                input[type="search"],
                textarea,
                select {
                    font-size: 14px !important;
                }
            }
        }

        @media (max-width: 768px) {
            html, body {
                overflow-x: hidden;
                width: 100%;
                max-width: 100vw;
                position: relative;
            }

            .dashboard-main-content {
                padding: 16px;
                width: 100%;
                max-width: 100vw;
                overflow-x: hidden;
            }

            .dashboard-header {
                width: 100%;
                max-width: 100vw;
                box-sizing: border-box;
            }

            .widget {
                width: 100%;
                max-width: 100vw;
                box-sizing: border-box;
                margin-bottom: 16px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                width: 100%;
            }

            .grid-col-4,
            .grid-col-5,
            .grid-col-3 {
                grid-column: span 1;
            }

            .stats-grid {
                flex-direction: column;
                gap: 8px;
            }

            .stat-card {
                min-width: auto;
                width: 100%;
            }

            .top-banner {
                flex-direction: column;
                text-align: center;
                gap: 16px;
                padding: 16px;
            }

            .church-info {
                text-align: center;
            }

            .prayer-banner-enhanced {
                padding: 24px 16px;
                font-size: 14px;
            }

            .prayer-banner-enhanced .prayer-quote {
                font-size: 16px;
                line-height: 1.4;
            }

            .prayer-banner-enhanced .prayer-ref {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .analytics-card {
                padding: 16px;
                border-radius: 10px;
                margin-bottom: 12px;
            }
            
            .card-header {
                margin-bottom: 12px;
                align-items: center;
            }
            
            .card-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                font-size: 1rem;
            }
            
            .card-icon i {
                font-size: 1rem;
            }
            
            .stat-value {
                font-size: 1.5rem;
                margin: 6px 0 2px;
            }
            
            .stat-label {
                font-size: 0.8rem;
            }
            
            .stat-trend {
                font-size: 0.75rem;
                padding: 3px 8px;
            }
            
            .progress-bar {
                height: 3px;
                margin-top: 12px;
            }
            
            .chart-container {
                height: 120px;
                margin-top: 10px;
            }
            
            .chart-legend {
                font-size: 0.65rem;
                margin-top: 3px;
            }
        }

        @media (max-width: 360px) {
            .analytics-card {
                padding: 12px;
                border-radius: 8px;
                margin-bottom: 10px;
            }
            
            .card-header {
                margin-bottom: 10px;
            }
            
            .card-icon {
                width: 32px;
                height: 32px;
                border-radius: 6px;
                font-size: 0.9rem;
            }
            
            .card-icon i {
                font-size: 0.9rem;
            }
            
            .stat-value {
                font-size: 1.25rem;
                margin: 4px 0 2px;
            }
            
            .stat-label {
                font-size: 0.75rem;
            }
            
            .stat-trend {
                font-size: 0.7rem;
                padding: 2px 6px;
            }
            
            .progress-bar {
                height: 2px;
                margin-top: 10px;
            }
            
            .chart-container {
                height: 100px;
                margin-top: 10px;
            }
            
            .chart-legend {
                font-size: 0.6rem;
                margin-top: 2px;
            }
        }

        @media (hover: none) and (pointer: coarse) {
            .notification-bell,
            .profile-avatar,
            .search-bar input,
            .stat-card,
            .request-btn,
            .view-all-btn {
                min-height: 44px;
                min-width: 44px;
            }

            .search-bar input {
                min-height: 44px;
                padding: 12px 16px 12px 40px; /* Maintain icon space for touch devices */
            }

            .notification-bell,
            .profile-avatar {
                min-height: 44px;
                min-width: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .header-actions {
                gap: 8px;
            }

            .widget,
            .stat-card,
            .request-btn,
            .view-all-btn {
                touch-action: manipulation;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }
        }

        @media (max-width: 768px) {
            /* Mobile responsiveness for search results */
            .search-results-section {
                margin-bottom: 16px;
            }
            
            .search-results-section h4 {
                font-size: 16px;
            }
            
            .search-results-section .space-y-2 > div {
                padding: 12px;
            }
            
            .search-results-section .space-y-2 .text-center {
                padding: 16px 8px;
            }
            
            /* Make search results single column on mobile */
            .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3 {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            /* Mobile responsiveness for JESUS IS LORD CHURCH stats cards only */
            .top-banner {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 16px;
                padding: 20px 16px;
            }
            
            .church-info h2 {
                font-size: 20px;
                line-height: 1.2;
                margin-bottom: 8px;
                text-align: center;
            }
            
            .church-info p {
                font-size: 13px;
                text-align: center;
                opacity: 0.9;
            }
            
            .stats-grid {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: center;
                align-items: center;
                width: 100%;
                margin-top: 8px;
            }
            
            .stat-card {
                background-color: rgba(157, 154, 233, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.3);
                padding: 16px 12px;
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 8px;
                min-width: auto;
                width: calc(50% - 6px);
                max-width: 160px;
                min-height: 100px;
                backdrop-filter: blur(8px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            
            .stat-card:active {
                transform: scale(0.95);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }
            
            .stat-card .number {
                font-size: 24px;
                font-weight: 700;
                line-height: 1;
                text-align: center;
            }
            
            .stat-card .label {
                font-size: 12px;
                font-weight: 600;
                text-align: center;
                line-height: 1.2;
                opacity: 0.95;
            }
            
            .stat-card .icon {
                font-size: 20px;
                opacity: 0.9;
                margin-bottom: 4px;
            }
        }
        
        @media (max-width: 480px) {
            .top-banner {
                padding: 16px 12px;
                gap: 12px;
            }
            
            .church-info h2 {
                font-size: 18px;
                margin-bottom: 6px;
            }
            
            .church-info p {
                font-size: 12px;
            }
            
            .stats-grid {
                gap: 8px;
                margin-top: 6px;
            }
            
            .stat-card {
                width: calc(50% - 4px);
                max-width: 140px;
                min-height: 90px;
                padding: 12px 8px;
                gap: 6px;
            }
            
            .stat-card .number {
                font-size: 20px;
            }
            
            .stat-card .label {
                font-size: 11px;
            }
            
            .stat-card .icon {
                font-size: 18px;
                margin-bottom: 2px;
            }
        }
        
        @media (max-width: 360px) {
            .top-banner {
                padding: 12px 8px;
                gap: 10px;
            }
            
            .church-info h2 {
                font-size: 16px;
                margin-bottom: 4px;
            }
            
            .church-info p {
                font-size: 11px;
            }
            
            .stats-grid {
                gap: 6px;
                margin-top: 4px;
            }
            
            .stat-card {
                width: calc(50% - 3px);
                max-width: 120px;
                min-height: 80px;
                padding: 10px 6px;
                gap: 4px;
            }
            
            .stat-card .number {
                font-size: 18px;
            }
            
            .stat-card .label {
                font-size: 10px;
            }
            
            .stat-card .icon {
                font-size: 16px;
                margin-bottom: 1px;
            }
        }
        /* Member widgets layout */
        .member-widgets-grid {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            align-items: start;
        }
        @media (min-width: 1024px) {
            .member-widgets-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="dashboard-main-content" style="background: var(--body-bg-color);">
        <!-- Dashboard Header -->
        <header class="dashboard-header">
            <h1>Dashboard</h1>
            <div class="search-bar">
                <form method="GET" action="{{ route('dashboard') }}" id="searchForm">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               id="searchInput"
                               value="{{ request('search') }}" 
                               placeholder="Search members, events, chapters..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               autocomplete="off">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        @if(request('search'))
                            <button type="button" 
                                    onclick="clearSearch()" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
            <div class="header-actions">
                <button class="theme-toggle-btn" onclick="toggleTheme()" title="Toggle Dark Mode">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </button>
                <div class="notification-bell">
                    <a href="{{ route('notifications.index') }}" 
                       class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-full transition-colors duration-200">
                        <i class="fas fa-bell text-xl"></i>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                {{ auth()->user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </a>
                </div>
                @php $authUser = auth()->user(); @endphp
                @if($authUser && $authUser->profile_photo_path)
                    <img src="{{ $authUser->profile_photo_url }}" alt="{{ $authUser->name }}" class="profile-avatar object-cover" onclick="toggleProfileDropdown(event)">
                @else
                    <div class="profile-avatar" onclick="toggleProfileDropdown(event)">{{ strtoupper($authUser->name[0] ?? 'U') }}</div>
                @endif
            </div>
        </header>

        <!-- Prayer Banner at Top -->
        <div class="prayer-banner-container" style="width:100%; padding:0 0 16px 0; display:flex; justify-content:space-between; align-items:center; position:relative; margin-bottom:12px;">
            <div class="prayer-banner-enhanced">
                <div class="prayer-quote">
                    <i class="fas fa-quote-left quote-icon"></i>
                    <span id="bible-verse-text">{{ $prayerVerse ?? "I pray that out of his glorious riches he may strengthen you with power through his Spirit in your inner being, so that Christ may dwell in your hearts through faith. And I pray that you, being rooted and established in love." }}</span>
                </div>
                <div class="prayer-ref" id="bible-verse-reference">
                    — <span>{{ $prayerReference ?? "Ephesians 3:16-17" }}</span>
                </div>
                @if(Auth::check() && Auth::user()->role === 'Admin')
                <div class="prayer-actions">
                    <button onclick="showEditPrayerModal()" class="prayer-btn primary">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Top Banner with Stats -->
        <div class="top-banner" style="margin-top: 0;">
            <div class="church-info">
                <h2>JESUS IS LORD CHURCH</h2>
                <p>Sorsogon, Philippines</p>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="number">{{ $totalAdmins ?? 5 }}</div>
                    <div class="label">Admins</div>
                    <i class="fas fa-user-shield icon"></i>
                </div>
                <div class="stat-card">
                    <div class="number">{{ $totalEvents }}</div>
                    <div class="label">Events</div>
                    <i class="fas fa-calendar-check icon"></i>
                </div>
                <div class="stat-card">
                    <div class="number">{{ $totalMembers }}</div>
                    <div class="label">Total Members</div>
                    <i class="fas fa-users icon"></i>
                </div>
                <div class="stat-card">
                    <div class="number">{{ $analyticsData['demographics']['total_chapters'] ?? 6 }}</div>
                    <div class="label">Chapters</div>
                    <i class="fas fa-school icon"></i>
                </div>
            </div>
            <!-- Quick Access Buttons Section -->
            @if(Auth::check() && Auth::user()->role === 'Admin')
            <div class="quick-access-container" style="margin-top: 8px;">
                <div class="quick-access-section">
                    <a href="{{ route('events.create') }}" class="quick-access-btn create-event-btn">
                        <div class="btn-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="btn-content">
                            <div class="btn-title">Create Event</div>
                            <div class="btn-subtitle">Add new church event</div>
                        </div>
                    </a>
                    <a href="{{ route('prayer-requests.index') }}" class="quick-access-btn prayer-request-btn">
                        <div class="btn-icon">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div class="btn-content">
                            <div class="btn-title">Prayer Request</div>
                            <div class="btn-subtitle">Submit prayer needs</div>
                        </div>
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Search Results Section -->
        @if(request('search'))
            <div class="widget" style="margin-bottom: 24px;">
                <div class="widget-header">
                    <h3><i class="fas fa-search mr-2"></i>Search Results for "{{ request('search') }}"</h3>
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times mr-1"></i>Clear Search
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Members Results -->
                    <div class="search-results-section">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-users text-blue-500 mr-2"></i>
                            <h4 class="text-lg font-semibold" style="color: var(--text-primary);">Members ({{ $members->count() }})</h4>
                        </div>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($members as $member)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                        @if($member->phone)
                                            <div class="text-sm text-gray-500">{{ $member->phone }}</div>
                                        @endif
                                    </div>
                                    <a href="{{ route('members.show', $member) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-user-slash text-4xl mb-2"></i>
                                    <p>No members found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Events Results -->
                    <div class="search-results-section">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-calendar-alt text-green-500 mr-2"></i>
                            <h4 class="text-lg font-semibold" style="color: var(--text-primary);">Events ({{ $events->count() }})</h4>
                        </div>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($events as $event)
                                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $event->title }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $event->date->format('M d, Y') }}
                                                @if($event->time)
                                                    at {{ $event->time }}
                                                @endif
                                            </div>
                                            @if($event->location)
                                                <div class="text-sm text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $event->location }}
                                                </div>
                                            @endif
                                            @if($event->description)
                                                <div class="text-sm text-gray-600 mt-1">
                                                    {{ Str::limit($event->description, 100) }}
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('events.show', $event) }}" class="text-green-600 hover:text-green-800 text-sm ml-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-2"></i>
                                    <p>No events found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Chapters Results -->
                    <div class="search-results-section">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-school text-purple-500 mr-2"></i>
                            <h4 class="text-lg font-semibold" style="color: var(--text-primary);">Chapters ({{ $chapters->count() }})</h4>
                        </div>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($chapters as $chapter)
                                <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $chapter->name }}</div>
                                            @if($chapter->description)
                                                <div class="text-sm text-gray-600 mt-1">
                                                    {{ Str::limit($chapter->description, 80) }}
                                                </div>
                                            @endif
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-users mr-1"></i>
                                                {{ $chapter->members->count() }} members
                                            </div>
                                        </div>
                                        <a href="{{ route('chapters.show', $chapter) }}" class="text-purple-600 hover:text-purple-800 text-sm ml-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-school text-4xl mb-2"></i>
                                    <p>No chapters found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Prayer Requests Results (if any) -->
                @if($prayerRequests->count() > 0)
                    <div class="mt-6">
                        <div class="search-results-section">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-praying-hands text-orange-500 mr-2"></i>
                                <h4 class="text-lg font-semibold text-gray-800">Prayer Requests ({{ $prayerRequests->count() }})</h4>
                            </div>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($prayerRequests as $prayerRequest)
                                    <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">
                                                    {{ Str::limit($prayerRequest->request, 60) }}
                                                </div>
                                                <div class="text-sm text-gray-500 mt-1">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        @if($prayerRequest->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($prayerRequest->status === 'answered') bg-green-100 text-green-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($prayerRequest->status) }}
                                                    </span>
                                                    <span class="ml-2">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $prayerRequest->prayer_date->format('M d, Y') }}
                                                    </span>
                                                </div>
                                                @if($prayerRequest->member)
                                                    <div class="text-sm text-gray-500">
                                                        <i class="fas fa-user mr-1"></i>
                                                        {{ $prayerRequest->member->name }}
                                                    </div>
                                                @endif
                                            </div>
                                            <a href="{{ route('prayer-requests.show', $prayerRequest) }}" class="text-orange-600 hover:text-orange-800 text-sm ml-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Additional Search Results -->
                @php
                    $totalResults = $members->count() + $events->count() + $chapters->count() + $prayerRequests->count();
                @endphp
                
                @if($totalResults === 0)
                    <div class="text-center py-12">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No results found</h3>
                        <p class="text-gray-500 mb-4">Try searching with different keywords or check your spelling.</p>
                        <div class="text-sm text-gray-400">
                            <p>Search suggestions:</p>
                            <ul class="mt-2 space-y-1">
                                <li>• Member names, email addresses, or phone numbers</li>
                                <li>• Event titles, descriptions, or locations</li>
                                <li>• Chapter names or descriptions</li>
                                <li>• Prayer request content or status</li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="text-sm font-medium">
                                Found {{ $totalResults }} result{{ $totalResults !== 1 ? 's' : '' }} for "{{ request('search') }}"
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @php($userRole = strtolower(auth()->user()->role ?? ''))
        @if($userRole !== 'member')
        <!-- Enhanced Analytics Section -->
        <div class="analytics-section" style="margin-top: 24px; background: var(--widget-bg-color); border-radius: 16px; padding: 25px; box-shadow: var(--card-box-shadow); color: var(--text-primary);">
            <div style="margin-bottom: 25px;">
                <h2 style="margin: 0 0 5px 0; color: var(--text-primary); font-size: 1.5rem; font-weight: 600;">
                    <i class="fas fa-chart-pie" style="margin-right: 10px; color: #4f46e5;"></i>Church Analytics
                </h2>
                <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">Key metrics and performance indicators</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
                <!-- Members Card with Chart -->
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 5px;">Total Members</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: var(--text-primary); line-height: 1.2;">{{ number_format($totalMembers) }}</div>
                        </div>
                        <div class="card-icon" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="membersChart"></canvas>
                    </div>
                    <div class="chart-legend">Overall Members</div>
                </div>

                <!-- Member by Gender Card with Chart -->
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 5px;">Member by Gender</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: var(--text-primary); line-height: 1.2;">{{ $totalMembers }}</div>
                        </div>
                        <div class="card-icon" style="background: linear-gradient(135deg, #4f46e5, #8b5cf6);">
                            <i class="fas fa-venus-mars"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div class="chart-legend">Gender Distribution</div>
                </div>

                <!-- Members by Age Group Card with Chart -->
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 5px;">Members by Age Group</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: var(--text-primary); line-height: 1.2;">{{ $totalMembers }}</div>
                        </div>
                        <div class="card-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="ageGroupChart"></canvas>
                    </div>
                    <div class="chart-legend">Age Distribution</div>
                </div>

                <!-- Prayer Request Status Card with Chart -->
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 5px;">Prayer Request Status</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: var(--text-primary); line-height: 1.2;">{{ $prayerStats['pending'] + $prayerStats['answered'] }}</div>
                        </div>
                        <div class="card-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                            <i class="fas fa-pray"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="prayerChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        <span style="color: #f59e42;">•</span> Pending: {{ $prayerStats['pending'] }} 
                        <span style="margin-left: 10px; color: #34d399;">•</span> Answered: {{ $prayerStats['answered'] }}
                    </div>
                </div>
            </div>
            
            @if(auth()->user()->role === 'Admin')
            <div style="text-align: right; margin-top: 20px;">
                <a href="{{ route('analytics.index') }}" style="color: #4f46e5; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; transition: all 0.2s;">
                    View Detailed Analytics
                    <i class="fas fa-arrow-right" style="margin-left: 6px; transition: transform 0.2s;"></i>
                </a>
            </div>
            @endif
        </div>
        @else
        <!-- Member Dashboard: Announcements and Events -->
        <div class="member-widgets-grid">
        <div class="widget" style="overflow:hidden;">
            <div class="widget-header">
                <h3><span class="badge-pill"><i class="fas fa-church"></i> Next Service</span></h3>
            </div>
            @if($nextService)
                <div class="space-y-3">
                    <div class="font-semibold" style="color: var(--text-primary); font-size:1.05rem;">{{ $nextService->title }}</div>
                    <div class="flex items-center gap-3 text-sm" style="color: var(--text-secondary);">
                        <span class="inline-flex items-center gap-2"><i class="fas fa-calendar"></i>{{ optional($nextService->date)->format('D, M d, Y') }}</span>
                        @if($nextService->time)
                            <span class="inline-flex items-center gap-2"><i class="fas fa-clock"></i>{{ $nextService->time }}</span>
                        @endif
                    </div>
                    @if($nextService->location)
                        <div class="text-sm inline-flex items-center gap-2" style="color: var(--text-secondary);">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $nextService->location }}
                        </div>
                    @endif
                    @if($nextService->description)
                        <div class="text-sm" style="color: var(--text-secondary); line-height:1.5;">
                            {{ Str::limit($nextService->description, 140) }}
                        </div>
                    @endif
                    <div class="flex items-center gap-2" style="margin-top:4px;">
                        <a href="{{ route('events.show', $nextService) }}" class="view-all-btn" style="width:auto; padding:8px 12px; border-color:#c7d2fe;">
                            <i class="fas fa-eye mr-1"></i> View
                        </a>
                        <a href="{{ route('events.index', ['highlight' => $nextService->id]) }}" class="view-all-btn" style="width:auto; padding:8px 12px; border-color:#c7d2fe;">
                            <i class="fas fa-bell mr-1"></i> Remind Me
                        </a>
                    </div>
                </div>
            @else
                <div class="text-sm" style="color: var(--text-secondary);">No upcoming service scheduled.</div>
            @endif
        </div>
        <div class="widget" style="overflow:hidden;">
            <div class="widget-header">
                <h3><span class="badge-pill" style="background:#fff7ed;color:#c2410c;"><i class="fas fa-bullhorn"></i> Announcements</span></h3>
                <a href="{{ route('notifications.index') }}" class="text-sm" style="color:#4f46e5; font-weight:600;">View all</a>
            </div>
            <div class="space-y-3">
                @forelse($announcements as $announcement)
                    <div class="p-3" style="background:#fff; border:1px solid #e2e8f0; border-radius:12px;">
                        <div class="flex items-start gap-3">
                            <div class="avatar-placeholder" style="width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:#fef3c7;color:#c2410c;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="flex-1">
                                <div class="name" style="font-weight:700; color:var(--text-primary);">{{ $announcement->title ?? 'Announcement' }}</div>
                                <div class="detail" style="margin-top:4px;">{{ Str::limit($announcement->content ?? ($announcement->body ?? ''), 120) }}</div>
                                <div class="meta" style="font-size:12px;color:var(--text-secondary); margin-top:6px; display:flex; gap:8px; align-items:center;">
                                    <span class="inline-flex items-center gap-1"><i class="far fa-clock"></i>{{ optional($announcement->created_at)->diffForHumans() }}</span>
                                    @if($announcement->user)
                                        <span>• by {{ $announcement->user->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-sm" style="color: var(--text-secondary);">No announcements yet.</div>
                @endforelse
            </div>
        </div>
        <div class="widget" style="overflow:hidden;">
            <div class="widget-header">
                <h3><span class="badge-pill" style="background:#ecfeff;color:#0e7490;"><i class="fas fa-calendar-alt"></i> Upcoming Events</span></h3>
                <a href="{{ route('events.index') }}" class="text-sm" style="color:#4f46e5; font-weight:600;">View all</a>
            </div>
            <div class="space-y-3">
                @forelse($eventsForWidget as $event)
                    <div class="p-3" style="background:#fff; border:1px solid #e2e8f0; border-radius:12px;">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <div class="font-semibold" style="color: var(--text-primary);">{{ $event->title }}</div>
                                <div class="flex items-center gap-3 text-sm" style="color: var(--text-secondary); margin-top:4px;">
                                    <span class="inline-flex items-center gap-2"><i class="fas fa-calendar"></i>{{ optional($event->date)->format('M d, Y') }}</span>
                                    @if($event->time)
                                        <span class="inline-flex items-center gap-2"><i class="fas fa-clock"></i>{{ $event->time }}</span>
                                    @endif
                                </div>
                                @if($event->location)
                                    <div class="text-sm inline-flex items-center gap-2" style="color: var(--text-secondary);">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $event->location }}
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('events.show', $event) }}" class="text-sm" style="color:#0ea5e9;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-sm" style="color: var(--text-secondary);">No upcoming events.</div>
                @endforelse
            </div>
        </div>
        </div>
        @endif

        <div id="profileDropdown" class="profile-dropdown" style="display:none; position:absolute; right:24px; top:120px; background:var(--main-header-bg); box-shadow:var(--card-box-shadow); border-radius:8px; min-width:220px; z-index:100; color:var(--text-primary); border:1px solid #e2e8f0;">
            <div style="padding:16px;">
                <div style="font-weight:600; margin-bottom:4px;">{{ auth()->user()->name ?? 'User' }}</div>
                <div style="font-size:13px; color:#888; margin-bottom:12px;">{{ auth()->user()->email ?? '' }}</div>
                <a href="{{ route('profile.edit') }}" style="display:block; padding:8px 0; color:#2D5BFF; text-decoration:none;">My Account</a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:#ef4444; padding:8px 0; width:100%; text-align:left; cursor:pointer;">Logout</button>
                </form>
            </div>
        </div>
        <script>

            function toggleProfileDropdown(event) {
                event.stopPropagation();
                var dropdown = document.getElementById('profileDropdown');
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                // Hide dropdown when clicking outside
                document.addEventListener('click', function handler(e) {
                    if (!dropdown.contains(e.target) && !e.target.closest('.profile-avatar')) {
                        dropdown.style.display = 'none';
                        document.removeEventListener('click', handler);
                    }
                });
            }
            // Theme toggle logic
            document.addEventListener('DOMContentLoaded', function() {
                // Load theme from localStorage
                let isDark = localStorage.getItem('dashboardTheme') === 'dark';
                // Helper to set theme
                function setTheme(isDark) {
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        document.getElementById('theme-icon').className = 'fas fa-sun';
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.getElementById('theme-icon').className = 'fas fa-moon';
                    }
                }
                setTheme(isDark);

                // Auto-refresh notification count every 30 seconds
                function updateNotificationCount() {
                    fetch('{{ route("notifications.unread-count") }}')
                        .then(response => response.json())
                        .then(data => {
                            const badge = document.querySelector('.notification-badge');
                            if (data.count > 0) {
                                if (badge) {
                                    badge.textContent = data.count;
                                    badge.style.display = 'flex';
                                } else {
                                    // Create badge if it doesn't exist
                                    const bellLink = document.querySelector('a[href="{{ route("notifications.index") }}"]');
                                    if (bellLink) {
                                        const newBadge = document.createElement('span');
                                        newBadge.className = 'notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse';
                                        newBadge.textContent = data.count;
                                        bellLink.appendChild(newBadge);
                                    }
                                }
                            } else {
                                if (badge) {
                                    badge.style.display = 'none';
                                }
                            }
                        })
                        .catch(error => console.log('Error fetching notification count:', error));
                }

                // Update notification count on page load and every 30 seconds
                updateNotificationCount();
                setInterval(updateNotificationCount, 30000);
            });

            // Global theme toggle function
            function toggleTheme() {
                let isDark = document.documentElement.classList.contains('dark');
                let newTheme = !isDark;
                localStorage.setItem('dashboardTheme', newTheme ? 'dark' : 'light');
                
                if (newTheme) {
                    document.documentElement.classList.add('dark');
                    document.getElementById('theme-icon').className = 'fas fa-sun';
                } else {
                    document.documentElement.classList.remove('dark');
                    document.getElementById('theme-icon').className = 'fas fa-moon';
                }
            }
        </script>
        <!-- Edit Modal (hidden by default) -->
        <div id="editPrayerModal" class="modal" style="display:none;">
            <div class="modal-content">
                <h3>Update Bible Verse</h3>
                <form method="POST" action="{{ route('dashboard.updateBibleVerse') }}">
                    @csrf
                    <div>
                        <label for="verse" style="display: block; margin-bottom: 6px; font-weight: 500; color: #475569;">Verse Text</label>
                        <textarea name="verse" id="verse" rows="4" required>{{ $prayerVerse ?? '' }}</textarea>
                    </div>
                    <div>
                        <label for="reference" style="display: block; margin-bottom: 6px; font-weight: 500; color: #475569;">Reference</label>
                        <input type="text" name="reference" id="reference" value="{{ $prayerReference ?? '' }}" placeholder="e.g., John 3:16" required>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="prayer-btn secondary" onclick="closeEditPrayerModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="prayer-btn primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function showEditPrayerModal() {
            document.getElementById('editPrayerModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeEditPrayerModal() {
            document.getElementById('editPrayerModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Search functionality
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            window.location.href = '{{ route("dashboard") }}';
        }

        // Auto-submit search form on Enter key
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            
            if (searchInput) {
                // Add search suggestions or auto-complete functionality here
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });

                // Add focus styling
                searchInput.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-500');
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-500');
                });
            }
        });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
               // Members Chart
            const membersCtx = document.getElementById('membersChart').getContext('2d');
            new Chart(membersCtx, {
         type: 'doughnut',
            data: {
        datasets: [{
            data: [{{ $totalMembers ?? 0 }}, 0], // A bit of a hack to make a full circle
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
            ctx.fillStyle = document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1f2937';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText({{ $totalMembers ?? 0 }}, centerX, centerY);
            ctx.restore();
        }
            }]
            });

                // Member by Gender Chart
                const genderCtx = document.getElementById('genderChart').getContext('2d');
                new Chart(genderCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Male', 'Female'],
                        datasets: [{
                            data: [{{ $genderStats['male'] ?? 0 }}, {{ $genderStats['female'] ?? 0 }}],
                            backgroundColor: ['#4f46e5', '#ec4899'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            }
                        },
                        layout: {
                            padding: 10
                        }
                    }
                });

                // Members by Age Group Chart
                const ageCtx = document.getElementById('ageGroupChart').getContext('2d');
                new Chart(ageCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Kids (3-12)', 'Youth (13-25)', 'Adults (26-59)', 'Seniors (60+)'],
                        datasets: [{
                            data: [
                                {{ $ageGroups['3-12'] ?? 0 }}, 
                                {{ $ageGroups['13-25'] ?? 0 }}, 
                                {{ $ageGroups['26-59'] ?? 0 }}, 
                                {{ $ageGroups['60+'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.7)',
                                'rgba(124, 58, 237, 0.7)',
                                'rgba(139, 92, 246, 0.7)',
                                'rgba(167, 139, 250, 0.7)'
                            ],
                            borderColor: [
                                'rgba(79, 70, 229, 1)',
                                'rgba(124, 58, 237, 1)',
                                'rgba(139, 92, 246, 1)',
                                'rgba(167, 139, 250, 1)'
                            ],
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' members';
                                    }
                                }
                            }
                        },
                        scales: { 
                            x: { 
                                display: false,
                                grid: { display: false }
                            }, 
                            y: { 
                                display: false,
                                beginAtZero: true,
                                grid: { display: false }
                            } 
                        }
                    }
                });

                // Prayer Request Status Chart
                const prayerCtx = document.getElementById('prayerChart').getContext('2d');
                new Chart(prayerCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Answered'],
                        datasets: [{
                            data: [
                                {{ $prayerStats['pending'] ?? 0 }}, 
                                {{ $prayerStats['answered'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(245, 158, 66, 0.7)',  // Pending - orange
                                'rgba(52, 211, 153, 0.7)'   // Answered - green
                            ],
                            borderColor: [
                                'rgba(245, 158, 66, 1)',
                                'rgba(52, 211, 153, 1)'
                            ],
                            borderWidth: 1,
                            cutout: '70%'
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
                                        return context.label + ': ' + context.raw + ' requests';
                                    }
                                }
                            }
                        },
                        layout: {
                            padding: 10
                        }
                    }
                });
            });
        </script>
    </x-app-layout>