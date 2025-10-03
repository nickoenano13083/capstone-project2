<x-app-layout>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        body {
            background: #f3f6fb;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            overflow-x: hidden; /* prevent horizontal scroll */
        }
        #leaflet-map {
            width: 100%;
            height: 78vh;
            min-height: 350px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 10px solid #e2e8f0;
            margin-top: 8px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            box-sizing: border-box; /* include border in width to avoid overflow */
        }
        .search-container {
            background: transparent;
            border-radius: 20px;
            padding: 18px 24px;
            margin-top: 18px;
            margin-bottom: 0;
            max-width: 2000px;
            margin: 0.5rem auto 1rem auto;
            left: 0; right: 0;
            box-shadow: none;
        }
        .search-bar-wrapper {
            position: relative;
            width: 100%;
            max-width: 1000px;
        }
        #map-search {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #a5b4fc;
            box-shadow: none;
            font-size: 1rem;
            background: #f8fafc;
        }
        #map-search-btn {
            border-radius: 0 12px 12px 0;
            background: linear-gradient(90deg, #6366f1 0%, #2563eb 100%);
            font-weight: 300;
            box-shadow: 0 2px 8px rgba(99,102,241,0.08);
            transition: background 0.2s;
        }
        #map-search-btn:hover {
            background: linear-gradient(90deg, #2563eb 0%, #6366f1 100%);
        }
        .search-suggestions {
            position: absolute;
            background: #fff;
            border: 1px solid #a5b4fc;
            border-top: none;
            width: 100%;
            max-width: 300px;
            z-index: 1000;
            left: 0;
            top: 100%;
            box-shadow: 0 2px 8px rgba(99,102,241,0.07);
            border-radius: 0 0 12px 12px;
        }
        .search-suggestion {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 1rem;
        }
        .search-suggestion:hover {
            background: #e0e7ff;
        }
        .map-legend-container {
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            max-width: 200px;
            transition: all 0.3s ease;
        }

        .legend-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-weight: 600;
            color: #333;
        }

        .legend-toggle:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .legend-toggle:active {
            transform: translateY(0);
        }

        .legend-icon {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            color: #4F46E5;
        }

        .toggle-arrow {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease;
            margin-left: 8px;
        }

        .legend-toggle.collapsed .toggle-arrow {
            transform: rotate(-90deg);
        }

        .legend-content {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 15px;
            margin-top: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            max-height: 300px;
            overflow: hidden;
            opacity: 1;
        }

        .legend-toggle.collapsed + .legend-content {
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            margin-top: 0;
            border: none;
            opacity: 0;
            pointer-events: none;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }

        .legend-item:last-child {
            margin-bottom: 0;
        }

        .legend-item:hover {
            transform: translateX(3px);
        }

        .legend-marker {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            margin-right: 10px;
            position: relative;
            flex-shrink: 0;
        }

        .legend-marker .pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            background: currentColor;
            border-radius: 50%;
            opacity: 0.8;
            animation: pulse 2s infinite;
        }

        .legend-marker.town { color: #3B82F6; }
        .legend-marker.event { color: #EF4444; }
        .legend-marker.chapter { color: #8B5CF6; }
        .legend-marker.user-location { color: #10B981; }

        .legend-label {
            font-size: 13px;
            color: #4B5563;
            font-weight: 500;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            70% {
                transform: scale(1.2);
                opacity: 0.4;
            }
            100% {
                transform: scale(0.8);
                opacity: 0.8;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .map-legend-container {
                bottom: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .legend-content {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: space-between;
            }

            .legend-item {
                flex: 1 0 calc(50% - 10px);
                margin-bottom: 8px;
                min-width: 0;
            }
        }
        /* Enhanced Filter Buttons */
        .filter-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateZ(0);
            will-change: transform, box-shadow, background;
            border: none;
            font-weight: 500;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 0.6rem 1.2rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
        }

        /* Button Specific Styles */
        .filter-btn[data-filter="all"] {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: #0369a1;
        }
        
        .filter-btn[data-filter="towns"] {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: #15803d;
        }
        
        .filter-btn[data-filter="events"] {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #b91c1c;
        }
        
        .filter-btn[data-filter="chapters"] {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            color: #6d28d9;
        }

        /* Hover States */
        .filter-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .filter-btn[data-filter="all"]:hover {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        }
        
        .filter-btn[data-filter="towns"]:hover {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        }
        
        .filter-btn[data-filter="events"]:hover {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        }
        
        .filter-btn[data-filter="chapters"]:hover {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        }

        /* Active State */
        .filter-btn.active {
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            transform: translateY(-1px);
        }

        .filter-btn[data-filter="all"].active {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
        }
        
        .filter-btn[data-filter="towns"].active {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
        }
        
        .filter-btn[data-filter="events"].active {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .filter-btn[data-filter="chapters"].active {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        /* Underline Animation */
        .filter-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: currentColor;
            border-radius: 3px 3px 0 0;
            transform: translateX(-50%);
            transition: width 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .filter-btn.active::after {
            width: 60%;
            opacity: 1;
        }

        /* Ripple Effect */
        .filter-btn .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.7);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Icon Animation */
        .filter-btn svg {
            transition: transform 0.3s ease, opacity 0.3s ease;
            margin-right: 0.5rem;
        }

        .filter-btn:hover svg {
            transform: scale(1.1) rotate(5deg);
        }

        .filter-btn.active svg {
            transform: scale(1.1);
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));
        }

        /* Focus State */
        .filter-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }

        /* Loading State */
        .filter-btn.loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }

        .filter-btn.loading::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Button Group */
        .filter-group {
            display: inline-flex;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 0.5rem;
            border-radius: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .filter-group .filter-btn {
            margin: 0 0.25rem;
        }

        /* Chapter label markers */
        .chapter-label {
            pointer-events: none;
        }
        .chapter-label-badge {
            pointer-events: auto;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: #fff;
            padding: 6px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 6px 14px rgba(124, 58, 237, 0.25);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .chapter-label-badge:hover {
            filter: brightness(1.05);
        }

        /* Header Action Buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border-radius: 9999px;
            border: none;
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease, opacity 0.2s ease;
            box-shadow: 0 8px 16px rgba(67, 56, 202, 0.15);
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(67, 56, 202, 0.25);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .action-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.35), 0 8px 16px rgba(67, 56, 202, 0.15);
        }

        .action-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding: 0.55rem 2.25rem 0.55rem 0.9rem;
            border-radius: 9999px;
            border: 1px solid #c7d2fe;
            color: #3730a3;
            background: #fff url('data:image/svg+xml;utf8,<svg fill="%236366f1" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center / 16px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .action-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-group {
                width: 100%;
                overflow-x: auto;
                padding: 0.75rem 0.5rem;
                justify-content: flex-start;
                -webkit-overflow-scrolling: touch;
            }
            
            .filter-btn {
                flex-shrink: 0;
                padding: 0.5rem 1rem;
                font-size: 0.7rem;
            }
            
            .filter-btn svg {
                margin-right: 0.3rem;
                width: 14px;
                height: 14px;
            }
        }
        
        /* Modern Search Bar Styles */
        .search-container {
            position: relative;
            max-width: 1500px;
            margin: 1.5rem auto;
           
            padding: 0 1rem;
            width: 95%;
            background: transparent;
            box-shadow: none;
        }

        .search-bar-wrapper {
            position: relative;
            width: 100%;
            max-width: 100%;
            transition: all 0.3s ease;
        }

        .search-input-group {
            display: flex;
            position: relative;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
            background: white;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .search-input-group:focus-within {
            border-color: #6366f1;
            box-shadow: 0 4px 25px rgba(99, 102, 241, 0.2);
        }

        #map-search {
            flex: 1;
            padding: 0.9rem 1.2rem;
            border: none;
            font-size: 1rem;
            color: #1e293b;
            background: transparent;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        #map-search::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .search-buttons {
            display: flex;
            align-items: center;
            padding-right: 0.5rem;
        }

        #map-search-btn, #clear-search-btn {
            padding: 0.6rem 1.2rem;
            border: none;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 0.25rem;
        }

        #map-search-btn {
            background: linear-gradient(90deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }

        #map-search-btn:hover {
            background: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-1px);
        }

        #clear-search-btn {
            display: none;
        }

        #clear-search-btn.visible {
            display: flex;
        }

        #clear-search-btn:hover {
            background: #f1f5f9;
            color: #ef4444;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            border-top: none;
        }

        .search-suggestions.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .search-suggestion {
            padding: 0.75rem 1.25rem;
            cursor: pointer;
            font-size: 0.95rem;
            color: #334155;
            display: flex;
            align-items: center;
            transition: all 0.15s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .search-suggestion:last-child {
            border-bottom: none;
        }

        .search-suggestion:hover {
            background: #f8fafc;
            color: #4f46e5;
        }

        .search-suggestion i {
            margin-right: 10px;
            color: #94a3b8;
            width: 20px;
            text-align: center;
        }

        .search-suggestion.history-item {
            color: #64748b;
            font-size: 0.9rem;
        }

        .search-suggestion.history-item i {
            color: #cbd5e1;
        }

        .search-section-title {
            padding: 0.6rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .no-results {
            padding: 1rem;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }

        .search-loading {
            display: none;
            padding: 1rem;
            text-align: center;
            color: #64748b;
        }

        .search-loading.visible {
            display: block;
        }

        .search-loading::after {
            content: '';
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #6366f1;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Features Bar */
        .features-bar {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg,rgb(223, 228, 245) 0%,rgb(216, 223, 241) 100%);
            border-radius: 8px;
            margin: 0 auto 0.5rem auto;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .filter-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .filter-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .filter-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }
        
        .filter-btn.active {
            background: white;
            color: #1e40af;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Enhanced Mobile Responsiveness */
        @media (max-width: 768px) {
            /* Mobile Map Container */
            #leaflet-map {
                height: calc(100vh - 220px); /* tighter to fit header & filters */
                min-height: 360px;
                border-width: 4px;
                border-radius: 8px;
                margin-top: 4px;
            }

            /* Mobile Features Bar */
            .features-bar {
                position: static; /* prevent overlaying app header */
                flex-direction: column;
                gap: 12px;
                padding: 12px 8px;
                margin-bottom: 8px;
                background: linear-gradient(135deg,rgb(223, 228, 245) 0%,rgb(216, 223, 241) 100%);
                border-radius: 12px;
            }

            .filter-group {
                width: 100%;
                justify-content: center;
                gap: 8px;
                padding: 8px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 8px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .filter-btn {
                flex-shrink: 0;
                padding: 12px 16px;
                font-size: 14px;
                min-height: 44px;
                min-width: 80px;
                border-radius: 22px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .filter-btn svg {
                width: 16px;
                height: 16px;
                margin-right: 6px;
            }

            /* Mobile Controls Container */
            .mobile-controls {
                display: none; /* hide header controls on mobile; we'll use FABs */
                gap: 8px;
                width: 100%;
                justify-content: center;
                align-items: center;
            }

            /* Remove fixed left margins for header controls on mobile */
            .mobile-controls > .ml-6,
            .mobile-controls .ml-6 {
                margin-left: 0 !important;
            }

            #find-me-btn {
                flex: 1;
                max-width: 200px;
                padding: 12px 16px;
                font-size: 14px;
                min-height: 44px;
                border-radius: 22px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            #map-theme-switcher {
                flex: 1;
                max-width: 140px;
                padding: 12px 8px;
                font-size: 12px;
                min-height: 44px;
                border-radius: 22px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .group button {
                width: 44px;
                height: 44px;
                min-width: 44px;
                min-height: 44px;
                border-radius: 22px;
                font-size: 16px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            /* Mobile Search Container */
            .search-container {
                position: sticky;
                top: 56px; /* below app header */
                z-index: 20; /* below app header */
                width: 100%;
                max-width: 100%;
                padding: 0 8px;
                margin: 8px auto;
            }

            .search-input-group {
                border-radius: 22px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            }

            #map-search {
                padding: 16px 20px;
                font-size: 16px; /* Prevent zoom on iOS */
                min-height: 44px;
                border-radius: 22px 0 0 22px;
            }

            .search-buttons {
                padding-right: 8px;
            }

            #map-search-btn, #clear-search-btn {
                min-width: 44px;
                min-height: 44px;
                padding: 12px;
                border-radius: 12px;
                font-size: 16px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            /* Mobile Search Suggestions */
            .search-suggestions {
                border-radius: 0 0 22px 22px;
                max-height: 250px;
                margin-top: 4px;
            }

            .search-suggestion {
                padding: 16px 20px;
                font-size: 16px;
                min-height: 56px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .search-suggestion i {
                width: 24px;
                height: 24px;
                font-size: 16px;
                margin-right: 12px;
            }

            .search-section-title {
                padding: 12px 20px;
                font-size: 12px;
            }

            /* Mobile Map Legend */
            .map-legend-container {
                bottom: 8px;
                right: 8px;
                left: 8px;
                max-width: none;
                z-index: 1001;
            }

            .legend-toggle {
                padding: 16px 20px;
                border-radius: 16px;
                min-height: 56px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .legend-icon {
                width: 24px;
                height: 24px;
                margin-right: 12px;
            }

            .toggle-arrow {
                width: 20px;
                height: 20px;
                margin-left: 12px;
            }

            .legend-content {
                padding: 16px;
                border-radius: 16px;
                margin-top: 8px;
            }

            .legend-item {
                padding: 12px 0;
                margin-bottom: 8px;
                min-height: 48px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .legend-marker {
                width: 20px;
                height: 20px;
                margin-right: 12px;
            }

            .legend-label {
                font-size: 14px;
            }

            /* Mobile Popup Content */
            .leaflet-popup-content-wrapper {
                border-radius: 16px;
                min-width: 280px;
                max-width: 320px;
            }

            .leaflet-popup-content {
                margin: 16px;
                font-size: 14px;
                line-height: 1.5;
            }

            .leaflet-popup-content button {
                padding: 12px 16px;
                font-size: 14px;
                min-height: 44px;
                border-radius: 22px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
                margin: 8px 0;
            }

            .get-directions-btn {
                width: 100% !important;
                padding: 16px !important;
                font-size: 16px !important;
                font-weight: 600 !important;
            }

            /* Mobile Map Controls */
            .leaflet-control-container .leaflet-top {
                top: 20px;
            }

            .leaflet-control-container .leaflet-right {
                right: 20px;
            }

            .leaflet-control-zoom {
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            }

            .leaflet-control-zoom a {
                width: 44px;
                height: 44px;
                line-height: 44px;
                font-size: 20px;
                border-radius: 12px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .leaflet-control-layers {
                border-radius: 16px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            }

            .leaflet-control-layers-toggle {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            /* Mobile Routing Control */
            .leaflet-routing-container {
                border-radius: 16px;
                min-width: 280px;
                max-width: 320px;
            }

            .leaflet-routing-collapse-btn {
                height: 44px;
                line-height: 44px;
                font-size: 14px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            /* Mobile Loading States */
            .filter-btn.loading::before {
                width: 20px;
                height: 20px;
                border-width: 3px;
            }

            .search-loading::after {
                width: 16px;
                height: 16px;
                border-width: 2px;
            }

            /* Mobile Animations */
            .filter-btn:hover {
                transform: translateY(-2px);
            }

            .filter-btn:active {
                transform: translateY(0);
            }

            /* Mobile Accessibility */
            button:focus, input:focus, select:focus {
                outline: 3px solid #6366f1;
                outline-offset: 2px;
            }

            /* Mobile Safe Area Support */
            @supports (padding: max(0px)) {
                .features-bar {
                    padding-left: max(12px, env(safe-area-inset-left));
                    padding-right: max(12px, env(safe-area-inset-right));
                    padding-top: max(12px, env(safe-area-inset-top));
                }

                .search-container {
                    padding-left: max(8px, env(safe-area-inset-left));
                    padding-right: max(8px, env(safe-area-inset-right));
                }

                .map-legend-container {
                    bottom: max(8px, env(safe-area-inset-bottom));
                    right: max(8px, env(safe-area-inset-right));
                    left: max(8px, env(safe-area-inset-left));
                }

                #leaflet-map {
                    height: calc(100vh - 280px - env(safe-area-inset-top) - env(safe-area-inset-bottom));
                }
            }
        }

        /* Mobile FABs for map actions */
        @media (max-width: 768px) {
            .mobile-fabs {
                position: fixed;
                right: 16px;
                bottom: 16px;
                display: flex;
                flex-direction: column;
                gap: 12px;
                z-index: 1003;
            }
            .mobile-fabs button {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                border: none;
                color: #fff;
                box-shadow: 0 6px 16px rgba(0,0,0,0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 22px;
            }
            #fab-find-me {
                background: linear-gradient(135deg, #3b82f6, #6366f1);
            }
            #fab-theme {
                background: linear-gradient(135deg, #0ea5e9, #22d3ee);
            }
        }

        /* Small Mobile Devices (320px - 375px) */
        @media (max-width: 375px) {
            .features-bar {
                padding: 8px 4px;
                gap: 8px;
            }

            .filter-group {
                gap: 4px;
                padding: 6px;
            }

            .filter-btn {
                padding: 10px 12px;
                font-size: 12px;
                min-width: 70px;
            }

            .filter-btn svg {
                width: 14px;
                height: 14px;
                margin-right: 4px;
            }

            #find-me-btn {
                font-size: 12px;
                padding: 10px 12px;
            }

            #map-theme-switcher {
                font-size: 11px;
                padding: 10px 6px;
            }

            .search-container {
                padding: 0 4px;
            }

            #map-search {
                padding: 14px 16px;
                font-size: 14px;
            }

            .map-legend-container {
                bottom: 4px;
                right: 4px;
                left: 4px;
            }

            .legend-toggle {
                padding: 12px 16px;
            }

            .legend-content {
                padding: 12px;
            }
        }

        /* Landscape Mobile Orientation */
        @media (max-width: 768px) and (orientation: landscape) {
            .features-bar {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px 12px;
            }

            .filter-group {
                flex: 1;
                min-width: 200px;
            }

            .mobile-controls {
                flex: 1;
                min-width: 200px;
                justify-content: flex-end;
            }

            #leaflet-map {
                height: calc(100vh - 120px);
            }

            .map-legend-container {
                bottom: 8px;
                right: 8px;
                left: auto;
                max-width: 200px;
            }
        }

        /* High Resolution Mobile Devices */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .filter-btn, #find-me-btn, #map-theme-switcher, .group button,
            #map-search-btn, #clear-search-btn, .legend-toggle {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        }

        /* Touch Device Specific */
        @media (hover: none) and (pointer: coarse) {
            .filter-btn:hover, #find-me-btn:hover, .legend-toggle:hover {
                transform: none;
            }

            .filter-btn:active, #find-me-btn:active, .legend-toggle:active {
                transform: scale(0.98);
            }

            .search-suggestion:hover {
                background: transparent;
            }

            .search-suggestion:active {
                background: #f3f4f6;
            }
        }

        /* Enhanced Filter Buttons Mobile */
        @media (max-width: 768px) {
            .filter-group {
                background: rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .filter-btn {
                background: rgba(255, 255, 255, 0.8);
                color: #1e40af;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .filter-btn:hover {
                background: rgba(255, 255, 255, 0.95);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .filter-btn.active {
                background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                color: white;
                border-color: transparent;
                box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
            }

            .filter-btn[data-filter="all"].active {
                background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            }

            .filter-btn[data-filter="towns"].active {
                background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            }

            .filter-btn[data-filter="events"].active {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            }

            .filter-btn[data-filter="chapters"].active {
                background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .map-legend-container {
                bottom: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
            }

            .legend-content {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: space-between;
            }

            .legend-item {
                flex: 1 0 calc(50% - 10px);
                margin-bottom: 8px;
                min-width: 0;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-group {
                width: 100%;
                overflow-x: auto;
                padding: 0.75rem 0.5rem;
                justify-content: flex-start;
                -webkit-overflow-scrolling: touch;
            }
            
            .filter-btn {
                flex-shrink: 0;
                padding: 0.5rem 1rem;
                font-size: 0.7rem;
            }
            
            .filter-btn svg {
                margin-right: 0.3rem;
                width: 14px;
                height: 14px;
            }
        }
    </style>
    <div class="flex flex-col min-h-screen">
        <!-- Map Header aligned like dashboard header -->
        <style>
            .map-header { display:flex; justify-content:flex-start; align-items:center; background:#fff; padding:16px 24px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); margin:12px auto 10px auto; gap:20px; width:100%; max-width:100vw; position:relative; z-index:1; box-sizing:border-box; }
            .map-header h1 { font-size:20px; font-weight:600; color:#333; }
            .map-search-bar { flex-grow:2; max-width:800px; min-width:300px; margin-left:10px; }
            .map-header-actions { display:flex; align-items:center; gap:12px; margin-left:auto; flex-wrap:nowrap; }
            @media (max-width:768px){
                html, body { overflow-x:hidden; width:100%; max-width:100vw; }
                .map-header{ padding:12px 12px 12px 56px; gap:8px; max-width:100vw; box-sizing:border-box; flex-direction: column; align-items: stretch; }
                .map-header h1{ display:none; }
                .map-search-bar{ order:2; width:100%!important; max-width:100%!important; min-width:auto!important; margin-left:0!important; }
                .map-header-actions{ order:3; gap:6px; flex-wrap: nowrap; width:100%; justify-content:flex-start; overflow-x:auto; -webkit-overflow-scrolling: touch; }
                .map-header-actions::-webkit-scrollbar{ display:none; }
                .map-header-actions .filter-group{ flex: 0 0 auto; width:auto; overflow-x:auto; white-space:nowrap; padding:4px 0; -webkit-overflow-scrolling: touch; }
                .map-header-actions .filter-btn{ padding:8px 10px; font-size:12px; }
                #map-theme-switcher.action-select{ flex:0 0 auto; }
                #find-me-btn.action-btn{ flex:0 0 auto; }
            }
            @media (max-width:480px){
                .map-header .action-btn .ml-1{ display:none; }
                .map-header .action-btn{ padding:10px 12px; min-width:44px; min-height:40px; }
                .map-header .action-select{ padding:6px 28px 6px 10px; font-size:12px; }
            }
        </style>
        <header class="map-header">
            <h1>Map</h1>
            <div class="map-search-bar">
                <div class="search-bar-wrapper">
                    <div class="search-input-group">
                        <input 
                            type="text" 
                            id="map-search" 
                            placeholder="Search for locations, churches, or addresses..."
                            autocomplete="off"
                            aria-label="Search locations"
                        >
                        <div class="search-buttons">
                            <button id="clear-search-btn" title="Clear search">
                                <i class="fas fa-times"></i>
                            </button>
                            <button id="map-search-btn" title="Search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="search-suggestions" id="search-suggestions">
                        <div class="search-loading" id="search-loading">Searching...</div>
                        <div id="suggestions-container"></div>
                    </div>
                </div>
            </div>
            <div class="map-header-actions">
                <div class="filter-group" style="margin-right:8px;">
                    <button class="filter-btn" data-filter="all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        All
                    </button>
                    <button class="filter-btn" data-filter="towns">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Towns
                    </button>
                    <button class="filter-btn" data-filter="chapters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Chapters
                    </button>
                </div>
                <button id="find-me-btn" class="action-btn" title="Find my location" aria-label="Find my location"><span style="font-size:1.2em;">📍</span><span class="ml-1">Find My Location</span></button>
                <select id="map-theme-switcher" class="action-select" title="Map theme" aria-label="Map theme">
                    <option value="standard">🗺️ Standard</option>
                </select>
                <div class="relative group">
                    <button class="action-btn" style="width:40px;height:40px;padding:0;" title="Help" aria-label="Help" tabindex="0">?</button>
                    <div class="absolute left-1/2 -translate-x-1/2 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg p-4 text-sm text-gray-700 z-50 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity duration-200">
                        <b>How to use the map:</b><br>
                        - Search for a town/city<br>
                        - Click markers for info<br>
                        - Use the theme switcher<br>
                        - Find your location<br>
                        - Use filters to show/hide types
                    </div>
                </div>
            </div>
        </header>
        <!-- Interactive Features Bar -->
        <div class="features-bar">
            <!-- Filter buttons relocated to header -->
            <!-- Header action buttons removed from features bar to avoid duplication -->
        </div>
        <!-- Removed duplicate search container; search is now in the header -->
        <div class="flex-1" style="position:relative;">
            <div id="leaflet-map"></div>
            
            <!-- Enhanced Map Legend -->
            <div class="map-legend-container">
                <button id="legend-toggle" class="legend-toggle" aria-label="Toggle legend">
                    <svg class="legend-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span class="legend-text">Legend</span>
                    <svg class="toggle-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div id="legend-content" class="legend-content">
                    <div class="legend-item">
                        <div class="legend-marker town">
                            <div class="pulse"></div>
                        </div>
                        <span class="legend-label">Town/City</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-marker chapter">
                            <div class="pulse"></div>
                        </div>
                        <span class="legend-label">Chapter</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-marker user-location">
                            <div class="pulse"></div>
                        </div>
                        <span class="legend-label">Your Location</span>
                    </div>
                </div>
            </div>

            <!-- Mobile Floating Action Buttons -->
            <div class="mobile-fabs" aria-hidden="true">
                <button id="fab-find-me" title="Find my location" aria-label="Find my location">📍</button>
                <button id="fab-theme" title="Change map theme" aria-label="Change map theme">🗺️</button>
            </div>
        </div>
    </div>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('leaflet-map').setView([12.97, 124.02], 10); // Centered on Sorsogon province
            
            // Initialize the findMeBtn variable
            var findMeBtn = document.getElementById('find-me-btn');
            if (!findMeBtn) {
                console.error('Find Me button not found!');
            } else {
                // Enhanced mobile functionality for Find Me button
                findMeBtn.addEventListener('touchstart', function(e) {
                    this.style.transform = 'scale(0.95)';
                }, { passive: true });
                
                findMeBtn.addEventListener('touchend', function(e) {
                    this.style.transform = 'scale(1)';
                }, { passive: true });
                
                // Add loading state for mobile
                findMeBtn.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="animate-spin"><path d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/><path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/></svg> Finding...';
                        this.disabled = true;
                        
                        // Reset after 3 seconds
                        setTimeout(() => {
                            this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg> Find Me';
                            this.disabled = false;
                        }, 3000);
                    }
                });
            }
            
            // Define tile layers with mobile-optimized attribution
            var tileLayers = {
                standard: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: ' OpenStreetMap',
                    className: window.innerWidth <= 768 ? 'mobile-tile-layer' : ''
                }),
                
            };
            tileLayers.standard.addTo(map);
            var currentBaseLayer = tileLayers.standard;

            // Enhanced map theme switcher for mobile (standard only)
            var themeSwitcher = document.getElementById('map-theme-switcher');
            var fabFindMe = document.getElementById('fab-find-me');
            var fabTheme = document.getElementById('fab-theme');
            if (themeSwitcher) {
                // Add touch feedback
                themeSwitcher.addEventListener('touchstart', function(e) {
                    this.style.transform = 'scale(0.98)';
                }, { passive: true });
                
                themeSwitcher.addEventListener('touchend', function(e) {
                    this.style.transform = 'scale(1)';
                }, { passive: true });
                
                themeSwitcher.addEventListener('change', function() {
                    // Only standard is available; ensure standard stays active
                    if (this.value !== 'standard') {
                        this.value = 'standard';
                    }
                    if (currentBaseLayer !== tileLayers.standard) {
                        map.removeLayer(currentBaseLayer);
                        tileLayers.standard.addTo(map);
                        currentBaseLayer = tileLayers.standard;
                    }
                });
            }

            // Mobile FAB: Find Me triggers the same logic as the header button
            if (fabFindMe && findMeBtn) {
                fabFindMe.addEventListener('click', function() {
                    findMeBtn.click();
                });
            }

            // Mobile FAB: Cycle map theme (standard -> dark -> satellite -> standard)
            if (fabTheme) {
                var order = ['standard', 'dark', 'satellite'];
                fabTheme.addEventListener('click', function() {
                    var keys = Object.keys(tileLayers);
                    var currentKey = keys.find(function(k){ return tileLayers[k] === currentBaseLayer; }) || 'standard';
                    var idx = order.indexOf(currentKey);
                    var next = order[(idx + 1) % order.length];
                    if (themeSwitcher) {
                        themeSwitcher.value = next;
                        var evt = new Event('change');
                        themeSwitcher.dispatchEvent(evt);
                    } else {
                        map.removeLayer(currentBaseLayer);
                        tileLayers[next].addTo(map);
                        currentBaseLayer = tileLayers[next];
                    }
                });
            }

            // Mobile-optimized map controls
            function optimizeMapControlsForMobile() {
                if (window.innerWidth <= 768) {
                    // Enhance zoom controls for mobile
                    var zoomControl = map.zoomControl;
                    if (zoomControl) {
                        zoomControl.setPosition('topright');
                        
                        // Add touch-friendly styling
                        var zoomButtons = zoomControl.getContainer().querySelectorAll('a');
                        zoomButtons.forEach(function(btn) {
                            btn.style.width = '44px';
                            btn.style.height = '44px';
                            btn.style.lineHeight = '44px';
                            btn.style.fontSize = '20px';
                            btn.style.borderRadius = '12px';
                        });
                    }
                    
                    // Optimize layer control for mobile
                    var layerControl = document.querySelector('.leaflet-control-layers');
                    if (layerControl) {
                        layerControl.style.fontSize = '14px';
                        var toggle = layerControl.querySelector('.leaflet-control-layers-toggle');
                        if (toggle) {
                            toggle.style.width = '44px';
                            toggle.style.height = '44px';
                            toggle.style.borderRadius = '12px';
                        }
                    }
                    
                    // Add mobile-specific map interactions
                    map.options.tap = false; // Disable tap for better mobile performance
                    map.options.touchZoom = true;
                    map.options.dragging = true;
                    
                    // Enhanced mobile popup behavior
                    map.on('popupopen', function(e) {
                        if (window.innerWidth <= 768) {
                            var popup = e.popup;
                            var popupContent = popup.getContent();
                            
                            // Ensure popup is visible on mobile
                            setTimeout(function() {
                                var popupElement = popup.getElement();
                                if (popupElement) {
                                    var bounds = popupElement.getBoundingClientRect();
                                    var mapContainer = document.getElementById('leaflet-map');
                                    var mapBounds = mapContainer.getBoundingClientRect();
                                    
                                    // Adjust popup position if it goes off screen
                                    if (bounds.right > mapBounds.right) {
                                        popup.setLatLng(e.popup.getLatLng());
                                    }
                                    if (bounds.bottom > mapBounds.bottom) {
                                        popup.setLatLng(e.popup.getLatLng());
                                    }
                                }
                            }, 100);
                        }
                    });
                }
            }

            // Initialize mobile optimizations
            optimizeMapControlsForMobile();
            
            // Re-optimize on window resize/orientation change
            window.addEventListener('resize', function() {
                optimizeMapControlsForMobile();
                
                // Adjust map size for mobile
                if (window.innerWidth <= 768) {
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 100);
                }
            });
            
            window.addEventListener('orientationchange', function() {
                setTimeout(function() {
                    map.invalidateSize();
                    optimizeMapControlsForMobile();
                }, 200);
            });

            // Enhanced mobile marker interactions
            function enhanceMobileMarkers() {
                if (window.innerWidth <= 768) {
                    // Make markers larger on mobile
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            var icon = layer.getIcon();
                            if (icon) {
                                // If the marker uses a divIcon with HTML, upscale it safely.
                                var isDivIcon = typeof icon.options.html !== 'undefined';
                                if (isDivIcon && icon.options.html) {
                                    var mobileIcon = L.divIcon({
                                        className: (icon.options.className || '') + ' mobile-marker',
                                        iconSize: [32, 32],
                                        iconAnchor: [16, 32],
                                        popupAnchor: [0, -32],
                                        html: icon.options.html || ''
                                    });
                                    layer.setIcon(mobileIcon);
                                } else {
                                    // Leave default/image markers untouched to avoid broken icons/text
                                    return;
                                }
                            }
                        }
                    });
                }
            }

            // Apply mobile marker enhancements
            setTimeout(enhanceMobileMarkers, 1000);
            
            // Array of locations: [lat, lng, name, description, address, population, attractions, history]
            var locations = [
                [12.994886, 124.0087088, 'Jesus Is Lord Sorsogon City',
                    `<img src='{{ asset('jil-sorsogon-dark.png') }}' style='width:100px;display:block;margin-bottom:5px;'>Jesus Is Lord Sorsogon City<br>X2V5+XGG, Sorsogon Diversion Rd, Sorsogon City, 4700 Sorsogon<br><a href='https://www.google.com/maps/place/Jesus+Is+Lord+Church/@12.9941421,124.0101508,496m/data=!3m1!1e3!4m6!3m5!1s0x33a0efaf85eca82b:0x7fb846fffc665d98!8m2!3d12.9949433!4d124.0088341!16s%2Fg%2F11smqkrt15?entry=ttu&g_ep=EgoyMDI1MDkwOC4wIKXMDSoASAFQAw%3D%3D' target='_blank'>View on Google Maps</a>`,
                    'Sorsogon, Bicol',
                    'Founded in 1894, became a city in 2000. Named after the Sorsogon tree.'],
                [12.8906913,124.1058797, 'Jesus Is Lord Gubat',
                `<img src='{{ asset('jil-sorsogon-dark.png') }}' style='width:100px;display:block;margin-bottom:5px;'>Capital of Sorsogon province, gateway to Southern Luzon.<br><a href='https://en.wikipedia.org/wiki/Sorsogon_City' target='_blank'>More info</a>`,
                    'Known for its beautiful beaches and surfing spots.',
                    'Gubat, Sorsogon, Bicol',
                    'Population: ~59,000',
                    ' Rizal Beach (surfing),  Buenavista Beach,  Gubat Municipal Hall,  St. Anthony of Padua Church',
                    'Founded in 1764. "Gubat" means forest in Bicolano. Famous for surfing competitions.'],
                [12.667768, 123.880826, 'Jesus Is Lord Bulan',
                `<img src='{{ asset('jil-sorsogon-dark.png') }}' style='width:100px;display:block;margin-bottom:5px;'>Capital of Sorsogon province, gateway to Southern Luzon.<br><a href='https://en.wikipedia.org/wiki/Sorsogon_City' target='_blank'>More info</a>`,
                    'A coastal municipality and commercial hub in southwestern Sorsogon.',
                    'Bulan, Sorsogon, Bicol',
                    'Population: ~100,000',
                    ' Bulan Beach,  Public Market,  St. James the Apostle Church,  Bulan Municipal Hall',
                    'Founded in 1801. Major fishing and commercial center. "Bulan" means moon in Filipino.'],
                [12.9256, 123.6776, 'Jesus Is Lord Pilar',
                `<img src='{{ asset('jil-sorsogon-dark.png') }}' style='width:100px;display:block;margin-bottom:5px;'>Capital of Sorsogon province, gateway to Southern Luzon.<br><a href='https://en.wikipedia.org/wiki/Sorsogon_City' target='_blank'>More info</a>`,
                    'Famous for whale shark (butanding) interaction tours.',
                    'Pilar, Sorsogon, Bicol',
                    'Population: ~75,000',
                    ' Donsol Whale Shark Tours,  Donsol Beach,  Pilar Municipal Hall,  Our Lady of the Pillar Church',
                    'Founded in 1635. Home to the famous Donsol whale shark sanctuary.'],
                [12.8731, 124.0172, 'Jesus Is Lord Casiguran',
                `<img src='{{ asset('jil-sorsogon-dark.png') }}' style='width:100px;display:block;margin-bottom:5px;'>Capital of Sorsogon province, gateway to Southern Luzon.<br><a href='https://en.wikipedia.org/wiki/Sorsogon_City' target='_blank'>More info</a>`,
                    'A quiet town with scenic rural landscapes.',
                    'Casiguran, Sorsogon, Bicol',
                    'Population: ~35,000',
                    ' Rice Fields,  Casiguran Municipal Hall,  St. Michael the Archangel Church,  Casiguran Bay',
                    'Founded in 1600. Known for agriculture and fishing. "Casiguran" means "place of refuge."'],
                [12.7568619, 124.1299093, 'Jesus Is Lord Bulusan',
                `<img src='{{ asset('jil-sorsogon-dark.png') }}' style='width:100px;display:block;margin-bottom:5px;'>Capital of Sorsogon province, gateway to Southern Luzon.<br><a href='https://en.wikipedia.org/wiki/Sorsogon_City' target='_blank'>More info</a>`,
                    'Home to Bulusan Volcano and Bulusan Lake.',
                    'Bulusan, Sorsogon, Bicol',
                    'Population: ~23,000',
                    ' Bulusan Volcano,  Bulusan Lake,  Bulusan Municipal Hall,  St. James the Greater Church',
                    'Founded in 1631. Named after Mount Bulusan, an active volcano. Famous for eco-tourism.']
            ];

            // --- Marker group setup for filters ---
            var townsLayer = L.layerGroup();
            var chaptersLayer = L.layerGroup();
            var markerRefs = {};
            var locationNames = locations.map(function(loc) { return loc[2]; });
            locations.forEach(function(loc, idx) {
                var directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + loc[0] + ',' + loc[1];
                var popupId = 'popup-moreinfo-' + idx;
                var popupContent = `
                    <div style="max-width:300px;">
                        <b style="font-size:16px;color:#2563eb;">${loc[2]}</b><br>
                        <span style="color:#374151;">${loc[3]}</span><br><br>
                        <div style="background:#f3f4f6;padding:8px;border-radius:4px;margin:5px 0;">
                            <strong> Address:</strong> ${loc[4]}<br>
                            <strong> Population:</strong> ${loc[5]}<br>
                        </div>
                        <button class="more-info-btn" data-popupid="${popupId}" style="color:#2563eb;background:none;border:none;cursor:pointer;font-weight:600;padding:0;margin:0 0 8px 0;">More Info ▼</button>
                        <div id="${popupId}" class="more-info-content" style="display:none;margin-top:8px;">
                            <div style='margin-bottom:8px;'>
                                <img src='${loc[3].includes("<img") ? '' : ''}' style='width:100px;display:block;margin-bottom:5px;'>
                            </div>
                            <div style="background:#e0e7ff;padding:8px;border-radius:4px;margin-bottom:8px;">
                                <strong> Attractions:</strong><br>${loc[6]}
                            </div>
                            <div style="background:#f3f4f6;padding:8px;border-radius:4px;">
                                <strong> History:</strong><br>${loc[7]}
                            </div>
                            <a href="https://en.wikipedia.org/wiki/${loc[2].replace(' ', '_')},_Sorsogon" target="_blank" style="color:#2563eb;text-decoration:none;display:block;margin-top:8px;"> Learn More</a>
                            <br><a href="${directionsUrl}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:bold;display:inline-block;margin-top:8px;"> Open in Google Maps</a>
                            <button class="less-info-btn" data-popupid="${popupId}" style="color:#2563eb;background:none;border:none;cursor:pointer;font-weight:600;padding:0;margin:8px 0 0 0;display:block;">Less Info ▲</button>
                        </div>
                        <button class="get-directions-btn mt-2" data-lat="${loc[0]}" data-lng="${loc[1]}" style="margin-top:10px;background:linear-gradient(90deg,#6366f1,#06b6d4);color:#fff;border:none;padding:8px 18px;border-radius:8px;font-weight:700;box-shadow:0 2px 8px #6366f122;cursor:pointer;display:block;width:100%;">Get Directions</button>
                    </div>
                `;
                var marker = L.marker([loc[0], loc[1]]).bindPopup(popupContent);
                marker.addTo(townsLayer);
                markerRefs[loc[2].toLowerCase()] = marker;

                // --- Marker hover effect ---
                marker.on('mouseover', function(e) {
                    var icon = e.target._icon;
                    if (icon) icon.classList.add('marker-hovered');
                });
                marker.on('mouseout', function(e) {
                    var icon = e.target._icon;
                    if (icon) icon.classList.remove('marker-hovered');
                });
            });
            townsLayer.addTo(map);

            // Build chapters layer: labeled markers showing chapter name; clicking computes route
            locations.forEach(function(loc) {
                var lat = loc[0], lng = loc[1];
                var labelHtml = '<div class="chapter-label"><span class="chapter-label-badge">' + (loc[2] || 'Chapter') + '</span></div>';
                var labelIcon = L.divIcon({
                    className: 'chapter-label',
                    html: labelHtml,
                    iconSize: [0, 0],
                    iconAnchor: [0, 0]
                });
                var labelMarker = L.marker([lat, lng], { icon: labelIcon });
                labelMarker.on('click', function() {
                    computeRouteToDestination(lat, lng);
                });
                chaptersLayer.addLayer(labelMarker);
            });

            // --- Layer control (for future extensibility) ---
            var overlayMaps = {
                "Towns/Cities": townsLayer,
                "Chapters": chaptersLayer
            };
            L.control.layers(null, overlayMaps, {collapsed: false, position: 'topright'}).addTo(map);

            // Sample Sorsogon province boundary (approximate, not official)
            var sorsogonBoundary = {
                "type": "Feature",
                "geometry": {
                    "type": "Polygon",
                    "coordinates": [[
                        [123.7, 12.6], [124.3, 12.6], [124.3, 13.2], [123.7, 13.2], [123.7, 12.6]
                    ]]
                }
            };
            L.geoJSON(sorsogonBoundary, {
                style: {
                    color: '#3388ff',
                    weight: 3,
                    fill: false
                }
            }).addTo(map);

            // Search functionality with auto-suggest
            var searchInput = document.getElementById('map-search');
            var searchBtn = document.getElementById('map-search-btn');
            var suggestionsBox = document.getElementById('search-suggestions');

            function showSuggestions(val) {
                var valLower = val.trim().toLowerCase();
                if (!valLower) {
                    suggestionsBox.style.display = 'none';
                    return;
                }
                var matches = locationNames.filter(function(name) {
                    return name.toLowerCase().includes(valLower);
                });
                if (matches.length === 0) {
                    suggestionsBox.style.display = 'none';
                    return;
                }
                suggestionsBox.innerHTML = matches.map(function(name) {
                    return '<div class="search-suggestion">' + name + '</div>';
                }).join('');
                suggestionsBox.style.display = 'block';
            }

            searchInput.addEventListener('input', function() {
                showSuggestions(this.value);
            });

            suggestionsBox.addEventListener('mousedown', function(e) {
                if (e.target.classList.contains('search-suggestion')) {
                    searchInput.value = e.target.textContent;
                    suggestionsBox.style.display = 'none';
                    searchBtn.click();
                }
            });

            document.addEventListener('click', function(e) {
                if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                    suggestionsBox.style.display = 'none';
                }
            });

            searchBtn.onclick = function() {
                var val = searchInput.value.trim().toLowerCase();
                if (val && markerRefs[val]) {
                    var marker = markerRefs[val];
                    map.setView(marker.getLatLng(), 13);
                    marker.openPopup();
                } else {
                    alert('Location not found. Try: ' + locationNames.join(', '));
                }
            };
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchBtn.click();
                }
            });

            // Popup More Info/Less Info toggle logic
            map.on('popupopen', function(e) {
                var popupNode = e.popup.getElement();
                if (!popupNode) return;
                var moreBtn = popupNode.querySelector('.more-info-btn');
                var lessBtn = popupNode.querySelector('.less-info-btn');
                var moreContent = popupNode.querySelector('.more-info-content');
                if (moreBtn && moreContent) {
                    moreBtn.onclick = function() {
                        moreContent.style.display = 'block';
                        moreBtn.style.display = 'none';
                    };
                }
                if (lessBtn && moreContent && moreBtn) {
                    lessBtn.onclick = function() {
                        moreContent.style.display = 'none';
                        moreBtn.style.display = 'inline';
                    };
                }
            });

            var userLocation = null;
            var userMarker = null;
            var routingControl = null;

            // Start a route from current device location to a destination
            function computeRouteToDestination(destLat, destLng) {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    return;
                }

                // Clear any existing user location marker
                if (window.userLocationMarker) {
                    map.removeLayer(window.userLocationMarker);
                }

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        userLocation = [position.coords.latitude, position.coords.longitude];

                        // Remove previous route if any
                        if (routingControl) {
                            map.removeControl(routingControl);
                        }

                        // Ensure a marker for user's current location (distinct from window.userLocationMarker used elsewhere)
                        if (!userMarker) {
                            userMarker = L.marker(
                                [position.coords.latitude, position.coords.longitude],
                                {
                                    icon: L.icon({
                                        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                        iconSize: [30, 48],
                                        iconAnchor: [15, 48],
                                        className: 'user-location-marker'
                                    })
                                }
                            ).addTo(map);
                        } else {
                            userMarker.setLatLng([position.coords.latitude, position.coords.longitude]);
                        }

                        // Create route
                        routingControl = L.Routing.control({
                            waypoints: [
                                L.latLng(position.coords.latitude, position.coords.longitude),
                                L.latLng(destLat, destLng)
                            ],
                            routeWhileDragging: false,
                            draggableWaypoints: false,
                            addWaypoints: false,
                            show: true,
                            collapsible: true,
                            createMarker: function(i, wp, nWps) {
                                if (i === 0) {
                                    return L.marker(wp.latLng, {
                                        icon: L.icon({
                                            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                            iconSize: [30, 48],
                                            iconAnchor: [15, 48],
                                            className: 'user-location-marker'
                                        })
                                    });
                                } else {
                                    return L.marker(wp.latLng);
                                }
                            },
                            lineOptions: {
                                styles: [{color: '#6366f1', weight: 6, opacity: 0.85}]
                            },
                            altLineOptions: {
                                styles: [{color: '#818cf8', weight: 4, opacity: 0.5, dashArray: '8,12'}]
                            },
                            language: 'en',
                            showAlternatives: false
                        }).addTo(map);

                        routingControl.on('routesfound', function(e) {
                            var route = e.routes && e.routes[0];
                            if (route && route.bounds) {
                                map.fitBounds(route.bounds, {padding: [40, 40]});
                            }
                        });

                        routingControl.on('routingerror', function() {
                            alert('Could not calculate route. Please try again.');
                        });
                    },
                    function(error) {
                        var errorMessage = 'Could not get your location. ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'Please enable location services and try again.'; break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'Location information is unavailable.'; break;
                            case error.TIMEOUT:
                                errorMessage += 'The request to get your location timed out.'; break;
                            default:
                                errorMessage += 'An unknown error occurred.'; break;
                        }
                        alert(errorMessage);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            }

            // --- Geolocation logic (enhanced) ---
            if (findMeBtn) {
                findMeBtn.addEventListener('click', function() {
                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser.');
                        return;
                    }
                    
                    // Show loading state
                    findMeBtn.disabled = true;
                    findMeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Locating...';
                    
                    // Clear any existing user location marker
                    if (window.userLocationMarker) {
                        map.removeLayer(window.userLocationMarker);
                    }
                    
                    // Get current position
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLatLng = [position.coords.latitude, position.coords.longitude];
                            
                            // Create a custom user location marker with pulse effect
                            const userIcon = L.divIcon({
                                className: 'user-location-marker',
                                html: '<div style="background: #3b82f6; width: 16px; height: 16px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);"></div>',
                                iconSize: [22, 22],
                                iconAnchor: [11, 11],
                                popupAnchor: [0, -11]
                            });
                            
                            // Add marker for user's location
                            window.userLocationMarker = L.marker(userLatLng, {
                                icon: userIcon,
                                zIndexOffset: 1000
                            }).addTo(map);
                            
                            // Add popup with user's coordinates
                            window.userLocationMarker.bindPopup(
                                `Your Location<br>Latitude: ${userLatLng[0].toFixed(6)}<br>Longitude: ${userLatLng[1].toFixed(6)}`,
                                { closeButton: false }
                            ).openPopup();
                            
                            // Center map on user's location with a smooth animation
                            map.flyTo(userLatLng, 15, {
                                duration: 1.5,
                                easeLinearity: 0.25
                            });
                            
                            // Reset button state
                            findMeBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Find Me';
                            findMeBtn.disabled = false;
                        },
                        function(error) {
                            // Handle errors
                            let errorMessage = 'Unable to retrieve your location.';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage = 'Location access was denied. Please enable location services in your browser settings.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage = 'Location information is unavailable.';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage = 'The request to get your location timed out.';
                                    break;
                            }
                            alert(errorMessage);
                            
                            // Reset button state
                            findMeBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Find Me';
                            findMeBtn.disabled = false;
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000, // 10 seconds
                            maximumAge: 0 // Force fresh location
                        }
                    );
                });
            }

            // --- Add Directions button to each marker popup ---
            locations.forEach(function(loc, idx) {
                var directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + loc[0] + ',' + loc[1];
                var popupId = 'popup-moreinfo-' + idx;
                var popupContent = `
                    <div style="max-width:300px;">
                        <b style="font-size:16px;color:#2563eb;">${loc[2]}</b><br>
                        <span style="color:#374151;">${loc[3]}</span><br><br>
                        <div style="background:#f3f4f6;padding:8px;border-radius:4px;margin:5px 0;">
                            <strong> Address:</strong> ${loc[4]}<br>
                            <strong> Population:</strong> ${loc[5]}<br>
                        </div>
                        <button class="more-info-btn" data-popupid="${popupId}" style="color:#2563eb;background:none;border:none;cursor:pointer;font-weight:600;padding:0;margin:0 0 8px 0;">More Info ▼</button>
                        <div id="${popupId}" class="more-info-content" style="display:none;margin-top:8px;">
                            <div style='margin-bottom:8px;'>
                                <img src='${loc[3].includes("<img") ? '' : ''}' style='width:100px;display:block;margin-bottom:5px;'>
                            </div>
                            <div style="background:#e0e7ff;padding:8px;border-radius:4px;margin-bottom:8px;">
                                <strong> Attractions:</strong><br>${loc[6]}
                            </div>
                            <div style="background:#f3f4f6;padding:8px;border-radius:4px;">
                                <strong> History:</strong><br>${loc[7]}
                            </div>
                            <a href="https://en.wikipedia.org/wiki/${loc[2].replace(' ', '_')},_Sorsogon" target="_blank" style="color:#2563eb;text-decoration:none;display:block;margin-top:8px;"> Learn More</a>
                            <br><a href="${directionsUrl}" target="_blank" style="color:#16a34a;text-decoration:none;font-weight:bold;display:inline-block;margin-top:8px;"> Open in Google Maps</a>
                            <button class="less-info-btn" data-popupid="${popupId}" style="color:#2563eb;background:none;border:none;cursor:pointer;font-weight:600;padding:0;margin:8px 0 0 0;display:block;">Less Info ▲</button>
                        </div>
                        <button class="get-directions-btn mt-2" data-lat="${loc[0]}" data-lng="${loc[1]}" style="margin-top:10px;background:linear-gradient(90deg,#6366f1,#06b6d4);color:#fff;border:none;padding:8px 18px;border-radius:8px;font-weight:700;box-shadow:0 2px 8px #6366f122;cursor:pointer;display:block;width:100%;">Get Directions</button>
                    </div>
                `;
                var marker = markerRefs[loc[2].toLowerCase()];
                marker.unbindPopup();
                marker.bindPopup(popupContent);
            });

            // --- Listen for Get Directions button clicks in popups ---
            map.on('popupopen', function(e) {
                var popupNode = e.popup.getElement();
                if (!popupNode) return;
                var getDirectionsBtn = popupNode.querySelector('.get-directions-btn');
                if (getDirectionsBtn) {
                    getDirectionsBtn.onclick = function() {
                        var destLat = parseFloat(getDirectionsBtn.getAttribute('data-lat'));
                        var destLng = parseFloat(getDirectionsBtn.getAttribute('data-lng'));
                        var destinationName = getDirectionsBtn.getAttribute('data-name') || 'Destination';
                        
                        if (navigator.geolocation) {
                            // Show loading state
                            getDirectionsBtn.disabled = true;
                            getDirectionsBtn.textContent = 'Getting your location...';
                            getDirectionsBtn.style.opacity = '0.7';
                            
                            // Clear any existing user location marker
                            if (window.userLocationMarker) {
                                map.removeLayer(window.userLocationMarker);
                            }
                            
                            // Get current position
                            navigator.geolocation.getCurrentPosition(
                                function(position) {
                                    // Update user location
                                    userLocation = [position.coords.latitude, position.coords.longitude];
                                    
                                    // Remove previous route if any
                                    if (routingControl) {
                                        map.removeControl(routingControl);
                                    }
                                    
                                    // Add marker for user's current location if not exists
                                    if (!userMarker) {
                                        userMarker = L.marker(
                                            [position.coords.latitude, position.coords.longitude],
                                            {
                                                icon: L.icon({
                                                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                                    iconSize: [30, 48],
                                                    iconAnchor: [15, 48],
                                                    className: 'user-location-marker'
                                                })
                                            }
                                        ).addTo(map);
                                    }
                                    
                                    // Create route
                                    routingControl = L.Routing.control({
                                        waypoints: [
                                            L.latLng(position.coords.latitude, position.coords.longitude),
                                            L.latLng(destLat, destLng)
                                        ],
                                        routeWhileDragging: false,
                                        draggableWaypoints: false,
                                        addWaypoints: false,
                                        show: true,
                                        collapsible: true,
                                        createMarker: function(i, wp, nWps) {
                                            if (i === 0) {
                                                return L.marker(wp.latLng, {
                                                    icon: L.icon({
                                                        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                                        iconSize: [30, 48],
                                                        iconAnchor: [15, 48],
                                                        className: 'user-location-marker'
                                                    })
                                                });
                                            } else {
                                                return L.marker(wp.latLng);
                                            }
                                        },
                                        lineOptions: {
                                            styles: [{color: '#6366f1', weight: 6, opacity: 0.85}]
                                        },
                                        altLineOptions: {
                                            styles: [{color: '#818cf8', weight: 4, opacity: 0.5, dashArray: '8,12'}]
                                        },
                                        language: 'en',
                                        showAlternatives: false
                                    }).addTo(map);
                                    
                                    // Fit map to show the entire route
                                    routingControl.on('routesfound', function(e) {
                                        var route = e.routes[0];
                                        if (route && route.bounds) {
                                            map.fitBounds(route.bounds, {padding: [40, 40]});
                                        }
                                        // Update button state
                                        getDirectionsBtn.textContent = 'Directions Shown';
                                        getDirectionsBtn.style.background = 'linear-gradient(90deg,#10b981,#06b6d4)';
                                    });
                                    
                                    // Handle routing errors
                                    routingControl.on('routingerror', function(e) {
                                        alert('Could not calculate route. Please try again.');
                                        getDirectionsBtn.textContent = 'Get Directions';
                                        getDirectionsBtn.disabled = false;
                                        getDirectionsBtn.style.opacity = '1';
                                    });
                                },
                                function(error) {
                                    // Handle geolocation errors
                                    var errorMessage = 'Could not get your location. ';
                                    switch(error.code) {
                                        case error.PERMISSION_DENIED:
                                            errorMessage += 'Please enable location services and try again.';
                                            break;
                                        case error.POSITION_UNAVAILABLE:
                                            errorMessage += 'Location information is unavailable.';
                                            break;
                                        case error.TIMEOUT:
                                            errorMessage += 'The request to get your location timed out.';
                                            break;
                                        case error.UNKNOWN_ERROR:
                                            errorMessage += 'An unknown error occurred.';
                                            break;
                                    }
                                    alert(errorMessage);
                                    getDirectionsBtn.textContent = 'Get Directions';
                                    getDirectionsBtn.disabled = false;
                                    getDirectionsBtn.style.opacity = '1';
                                },
                                {
                                    enableHighAccuracy: true,
                                    timeout: 10000,
                                    maximumAge: 0
                                }
                            );
                        } else {
                            alert('Geolocation is not supported by your browser.');
                        }
                    };
                }
            });

            // --- Filter button logic ---
            var filterButtons = document.querySelectorAll('.filter-btn');
            function setActiveFilter(btn) {
                filterButtons.forEach(function(b) { b.classList.remove('active'); });
                btn.classList.add('active');
            }
            filterButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var label = btn.textContent.trim();
                    setActiveFilter(btn);
                    // Remove all marker groups
                    map.removeLayer(townsLayer);
                    map.removeLayer(chaptersLayer);
                    if (label === 'All' || label === 'Towns') {
                        townsLayer.addTo(map);
                    } else if (label === 'Chapters') {
                        chaptersLayer.addTo(map);
                    }
                });
            });
            // Set default filter
            setActiveFilter(filterButtons[0]);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ripple effect
            document.querySelectorAll('.filter-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple');
                    
                    // Position ripple
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    ripple.style.width = ripple.style.height = `${size}px`;
                    ripple.style.left = `${e.clientX - rect.left - size/2}px`;
                    ripple.style.top = `${e.clientY - rect.top - size/2}px`;
                    
                    // Add and remove ripple
                    this.appendChild(ripple);
                    ripple.addEventListener('animationend', () => ripple.remove());
                    
                    // Simulate loading state
                    this.classList.add('loading');
                    setTimeout(() => {
                        this.classList.remove('loading');
                    }, 800);
                });
            });
            
            // Filter button active state
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Add click effect
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'translateY(-1px)';
                    }, 100);
                });
            });
            
            // Set first filter as active by default
            if (!document.querySelector('.filter-btn.active') && filterButtons.length > 0) {
                filterButtons[0].classList.add('active');
            }
            
            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                    const activeBtn = document.querySelector('.filter-btn.active');
                    if (activeBtn) {
                        const btns = Array.from(filterButtons);
                        const currentIndex = btns.indexOf(activeBtn);
                        let nextIndex;
                        
                        if (e.key === 'ArrowRight') {
                            nextIndex = (currentIndex + 1) % btns.length;
                        } else {
                            nextIndex = (currentIndex - 1 + btns.length) % btns.length;
                        }
                        
                        activeBtn.classList.remove('active');
                        btns[nextIndex].classList.add('active');
                        btns[nextIndex].focus();
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const legendToggle = document.getElementById('legend-toggle');
            const legendContent = document.getElementById('legend-content');
            
            // Toggle legend visibility
            legendToggle.addEventListener('click', function() {
                this.classList.toggle('collapsed');
            });
            
            // Close legend when clicking outside
            document.addEventListener('click', function(e) {
                if (!legendToggle.contains(e.target) && !legendContent.contains(e.target)) {
                    legendToggle.classList.add('collapsed');
                }
            });
            
            // Prevent map interaction when clicking on legend
            legendContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('map-search');
            const searchBtn = document.getElementById('map-search-btn');
            const clearBtn = document.getElementById('clear-search-btn');
            const suggestionsBox = document.getElementById('search-suggestions');
            const suggestionsContainer = document.getElementById('suggestions-container');
            const searchLoading = document.getElementById('search-loading');
            
            let searchHistory = JSON.parse(localStorage.getItem('mapSearchHistory') || '[]');
            let searchTimeout;

            // Toggle search suggestions
            function toggleSuggestions(show) {
                if (show) {
                    suggestionsBox.classList.add('visible');
                } else {
                    // Small delay to allow click events to register
                    setTimeout(() => {
                        if (!document.activeElement.matches('#map-search, .search-suggestion')) {
                            suggestionsBox.classList.remove('visible');
                        }
                    }, 200);
                }
            }

            // Show search history
            function showSearchHistory() {
                if (searchHistory.length === 0) return '';
                
                let html = `
                    <div class="search-section-title">Recent Searches</div>
                    ${searchHistory.map(item => `
                        <div class="search-suggestion history-item" data-search="${item}">
                            <i class="far fa-clock"></i>
                            ${item}
                        </div>
                    `).join('')}
                `;
                
                return html;
            }

            // Add to search history
            function addToSearchHistory(term) {
                if (!term.trim()) return;
                
                // Remove if already exists
                searchHistory = searchHistory.filter(item => item.toLowerCase() !== term.toLowerCase());
                
                // Add to beginning
                searchHistory.unshift(term);
                
                // Keep only last 5 searches
                if (searchHistory.length > 5) {
                    searchHistory = searchHistory.slice(0, 5);
                }
                
                localStorage.setItem('mapSearchHistory', JSON.stringify(searchHistory));
            }

            // Show loading state
            function setLoading(loading) {
                if (loading) {
                    searchLoading.classList.add('visible');
                    suggestionsContainer.style.display = 'none';
                } else {
                    searchLoading.classList.remove('visible');
                    suggestionsContainer.style.display = 'block';
                }
            }

            // Show suggestions
            function showSuggestions(term) {
                if (!term.trim()) {
                    const historyHtml = showSearchHistory();
                    suggestionsContainer.innerHTML = historyHtml || `
                        <div class="search-section-title">Search Tips</div>
                        <div class="search-suggestion"><i class="fas fa-search"></i> Try searching for a church name</div>
                        <div class="search-suggestion"><i class="fas fa-map-marker-alt"></i> Or enter an address</div>
                    `;
                    toggleSuggestions(true);
                    return;
                }

                // Show loading state
                setLoading(true);
                
                // Simulate API call with timeout
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // This would be replaced with actual search logic
                    const filteredLocations = locations.filter(loc => 
                        loc[2].toLowerCase().includes(term.toLowerCase()) ||
                        loc[4].toLowerCase().includes(term.toLowerCase())
                    );
                    
                    let html = '';
                    
                    if (filteredLocations.length > 0) {
                        html += '<div class="search-section-title">Locations</div>';
                        filteredLocations.forEach(loc => {
                            html += `
                                <div class="search-suggestion" data-lat="${loc[0]}" data-lng="${loc[1]}" data-name="${loc[2]}">
                                    <i class="fas fa-church"></i>
                                    <div>
                                        <div class="font-medium">${loc[2]}</div>
                                        <div class="text-xs text-gray-500">${loc[4]}</div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = `
                            <div class="no-results">
                                No results found for "${term}"
                            </div>
                        `;
                    }
                    
                    suggestionsContainer.innerHTML = html;
                    setLoading(false);
                    toggleSuggestions(true);
                }, 300); // Simulated delay
            }

            // Event Listeners
            searchInput.addEventListener('focus', () => showSuggestions(searchInput.value));
            searchInput.addEventListener('input', (e) => {
                clearBtn.classList.toggle('visible', e.target.value.length > 0);
                showSuggestions(e.target.value);
            });
            
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (searchInput.value.trim()) {
                        addToSearchHistory(searchInput.value);
                        // Here you would trigger the actual search
                        console.log('Searching for:', searchInput.value);
                        // For now, just show suggestions
                        showSuggestions(searchInput.value);
                    }
                }
            });
            
            searchBtn.addEventListener('click', () => {
                if (searchInput.value.trim()) {
                    addToSearchHistory(searchInput.value);
                    // Here you would trigger the actual search
                    console.log('Searching for:', searchInput.value);
                    // For now, just show suggestions
                    showSuggestions(searchInput.value);
                }
            });
            
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchInput.focus();
                clearBtn.classList.remove('visible');
                showSuggestions('');
            });
            
            // Handle suggestion clicks
            suggestionsContainer.addEventListener('click', (e) => {
                const suggestion = e.target.closest('.search-suggestion');
                if (!suggestion) return;
                
                if (suggestion.dataset.search) {
                    // History item clicked
                    searchInput.value = suggestion.dataset.search;
                    searchInput.focus();
                    showSuggestions(searchInput.value);
                } else if (suggestion.dataset.lat) {
                    // Location suggestion clicked
                    const lat = parseFloat(suggestion.dataset.lat);
                    const lng = parseFloat(suggestion.dataset.lng);
                    const name = suggestion.dataset.name || 'Location';
                    
                    // Add to search history
                    addToSearchHistory(name);
                    
                    // Update search input
                    searchInput.value = name;
                    clearBtn.classList.add('visible');
                    
                    // Close suggestions
                    toggleSuggestions(false);
                    
                    // Pan to location on the map
                    map.setView([lat, lng], 15);
                    
                    // Here you would typically highlight the marker for this location
                    console.log('Selected location:', name, lat, lng);
                }
            });
            
            // Close suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.search-bar-wrapper')) {
                    toggleSuggestions(false);
                }
            });
            
            // Initial setup
            clearBtn.classList.toggle('visible', searchInput.value.length > 0);
        });
    </script>
</x-app-layout>