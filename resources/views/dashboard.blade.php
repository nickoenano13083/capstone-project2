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

        body {
            background: linear-gradient(to bottom, #f9fafb, #eef2ff) !important;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        .dashboard-main-content {
            padding: 24px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--main-header-bg);
            padding: 16px 24px;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-box-shadow);
            margin-bottom: 24px;
        }
        
        .dashboard-header .menu-toggle {
            display: none; /* Hidden on desktop, shown on mobile */
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        .dashboard-header h1 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .search-bar {
            flex-grow: 1;
            margin: 0 40px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
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
            background-color:rgb(224, 228, 236);
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(23, 23, 108, 0.10);
            padding: 28px 24px;
            transition: box-shadow 0.2s;
        }
        .widget:hover {
            box-shadow: 0 10px 32px rgba(23, 23, 108, 0.18);
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
            margin-bottom: 20px;
        }

        .widget-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
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
            .dashboard-header .menu-toggle { display: block; }
            .dashboard-header h1 { display: none; }
            .search-bar { display: none; }
            .header-actions {
                justify-content: flex-end; /* Align to right side */
                gap: 6px; /* Further reduce gap for much closer spacing */
                order: 2; /* Move actions below title */
                margin-top: 8px;
                padding-right: 16px; /* Add right padding for consistent spacing */
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
            background: #fff;
            color: #1e293b;
            border-radius: 12px;
            padding: 24px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
        }
        .modal-content h3, .modal-content h4 {
            margin-top: 0;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .modal-content p {
            margin-bottom: 20px;
            color: #475569;
            line-height: 1.6;
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
        }
        .modal-content input[type="text"]:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
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
            background:rgba(198, 201, 214, 0.39);
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .analytics-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
                padding: 12px;
                gap: 12px;
            }

            .dashboard-header h1 {
                font-size: 16px;
                padding: 0 35px;
            }

            .search-bar input {
                padding: 10px 14px;
                font-size: 15px;
            }

            .header-actions {
                gap: 16px;
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
                padding: 8px;
                gap: 8px;
            }

            .dashboard-header .menu-toggle {
                left: 12px;
                font-size: 20px;
            }

            .dashboard-header h1 {
                font-size: 14px;
                padding: 0 30px;
                line-height: 1.2;
            }

            .search-bar {
                order: 3;
                margin: 4px 0;
            }

            .search-bar input {
                padding: 8px 12px;
                font-size: 14px;
                border-radius: 8px;
            }

            .search-bar input::placeholder {
                font-size: 12px;
            }

            .header-actions {
                gap: 8px;
                padding-right: 12px;
                margin-top: 4px;
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
            .dashboard-header .menu-toggle,
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
                padding: 12px 16px;
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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="dashboard-main-content" style="background: linear-gradient(to bottom, #f9fafb, #eef2ff);">
        <!-- Dashboard Header -->
        <header class="dashboard-header">
            <button class="menu-toggle"><i class="fas fa-bars"></i></button>
            <h1>Dashboard</h1>
            <div class="search-bar">
                <form method="GET" action="{{ route('dashboard') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search People, Groups, Events, Tags">
                </form>
            </div>
            <div class="header-actions">
                <div class="notification-bell" onclick="toggleNotificationDropdown(event)">
                    <i class="fas fa-bell"></i>
                    @php
                        $totalNotifications = ($unreadCount ?? 0) + ($pendingCount ?? 0) + ($answeredCount ?? 0);
                    @endphp
                    @if($totalNotifications > 0)
                        <div class="badge" id="notificationBadge">{{ $totalNotifications }}</div>
                    @endif
                </div>
                <div class="profile-avatar" onclick="toggleProfileDropdown(event)">{{ strtoupper(auth()->user()->name[0] ?? 'U') }}</div>
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
                <p>Sorsogon City, Sorsogon, Philippines</p>
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
                <h3>Search Results for "{{ request('search') }}"</h3>
                <div>
                    <div>
                        <h4>Members</h4>
                        @forelse($members as $member)
                            <div>{{ $member->name }}</div>
                        @empty
                            <div>No members found.</div>
                        @endforelse
                    </div>
                    <div>
                        <h4>Events</h4>
                        @forelse($events as $event)
                            <div>{{ $event->title }}</div>
                        @empty
                            <div>No events found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        <!-- Enhanced Analytics Section -->
        <div class="analytics-section" style="margin-top: 24px; background: rgba(255, 255, 255, 0.8); border-radius: 16px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="margin-bottom: 25px;">
                <h2 style="margin: 0 0 5px 0; color: #2D3748; font-size: 1.5rem; font-weight: 600;">
                    <i class="fas fa-chart-pie" style="margin-right: 10px; color: #4f46e5;"></i>Church Analytics
                </h2>
                <p style="margin: 0; color: #64748b; font-size: 0.9rem;">Key metrics and performance indicators</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
                <!-- Members Card with Chart -->
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 5px;">Total Members</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #1e293b; line-height: 1.2;">{{ number_format($totalMembers) }}</div>
                        </div>
                        <div class="card-icon" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="membersChart"></canvas>
                    </div>
                    <div class="chart-legend">Monthly Growth</div>
                </div>

                <!-- Member by Gender Card with Chart -->
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 5px;">Member by Gender</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #1e293b; line-height: 1.2;">{{ $totalMembers }}</div>
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
                            <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 5px;">Members by Age Group</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #1e293b; line-height: 1.2;">{{ $totalMembers }}</div>
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
                            <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 5px;">Prayer Request Status</div>
                            <div style="font-size: 1.8rem; font-weight: 700; color: #1e293b; line-height: 1.2;">{{ $prayerStats['pending'] + $prayerStats['answered'] }}</div>
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

        <!-- Notification Dropdown -->
        <div id="notificationDropdown" class="notification-dropdown" style="display:none; position:absolute; right:24px; top:70px; background:#fff; box-shadow:0 2px 8px #0002; border-radius:8px; min-width:300px; z-index:100;">
            <div style="padding:16px;">
                <strong>Notifications</strong>
                <hr>
                @foreach($unreadMessages as $msg)
                    <div style="margin-bottom:8px;">
                        <i class="fas fa-envelope"></i>
                        <strong>{{ $msg->sender_name ?? 'Message' }}</strong>: {{ \Illuminate\Support\Str::limit($msg->content, 40) }}
                    </div>
                @endforeach

                @if(auth()->user()->role === 'Admin')
                    @foreach($pendingPrayerRequests as $pr)
                        <div>
                            <i class="fas fa-praying-hands"></i>
                            <strong>Prayer Request</strong>: {{ \Illuminate\Support\Str::limit($pr->request, 40) }}
                        </div>
                    @endforeach
                @elseif(auth()->user()->role === 'Member')
                    @foreach($answeredPrayerRequests as $pr)
                        <div>
                            <i class="fas fa-check-circle"></i>
                            <strong>Your prayer request was answered:</strong> {{ \Illuminate\Support\Str::limit($pr->request, 40) }}
                        </div>
                    @endforeach
                @endif

                @if($totalNotifications == 0)
                    <div>No new notifications.</div>
                @endif
            </div>
        </div>
        <div id="profileDropdown" class="profile-dropdown" style="display:none; position:absolute; right:24px; top:120px; background:#fff; box-shadow:0 2px 8px #0002; border-radius:8px; min-width:220px; z-index:100;">
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
            let notificationsMarked = false;
            function toggleNotificationDropdown(event) {
                event.stopPropagation();
                var dropdown = document.getElementById('notificationDropdown');
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                if (dropdown.style.display === 'block' && !notificationsMarked) {
                    fetch("{{ route('notifications.markRead') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(res => res.json()).then(data => {
                        notificationsMarked = true;
                        var badge = document.getElementById('notificationBadge');
                        if (badge) badge.style.display = 'none';
                    });
                }
                document.addEventListener('click', function handler(e) {
                    if (!dropdown.contains(e.target) && !e.target.closest('.notification-bell')) {
                        dropdown.style.display = 'none';
                        document.removeEventListener('click', handler);
                    }
                });
            }

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
                        document.body.classList.add('dark-theme');
                    } else {
                        document.body.classList.remove('dark-theme');
                    }
                }
                setTheme(isDark);
            });
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
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Members Chart
                const membersCtx = document.getElementById('membersChart').getContext('2d');
                new Chart(membersCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            data: [12, 19, 15, 27, 34, 42, {{ $totalMembers ?? 0 }}],
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { display: false }, y: { display: false } },
                        elements: { line: { borderJoinStyle: 'round' } }
                    }
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