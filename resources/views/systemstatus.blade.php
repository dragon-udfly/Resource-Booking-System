@extends('layouts.admin_body_layout')

@section('title', 'System Status - District Secretariat Vavuniya')

@section('content')
    <section class="banner">
        <div class="settings-container">
            <div class="settings-header">
                <h2>System Status</h2>
                <p>Real-time view of system logs and health status.</p>
            </div>

            <div class="settings-group">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <h3>System Logs</h3>

                    <div class="filter-group">
                        <a href="{{ route('system.status', ['filter' => 'all']) }}"
                            class="filter-btn {{ $currentFilter == 'all' ? 'active' : '' }}">All</a>
                        <a href="{{ route('system.status', ['filter' => 'month']) }}"
                            class="filter-btn {{ $currentFilter == 'month' ? 'active' : '' }}">Current Month</a>
                        <a href="{{ route('system.status', ['filter' => 'week']) }}"
                            class="filter-btn {{ $currentFilter == 'week' ? 'active' : '' }}">Current Week</a>
                        <a href="{{ route('system.status', ['filter' => 'today']) }}"
                            class="filter-btn {{ $currentFilter == 'today' ? 'active' : '' }}">Today</a>
                    </div>

                    <a href="{{ route('system.status', ['filter' => $currentFilter]) }}" class="btn-primary"
                        style="padding: 8px 15px; text-decoration: none; border-radius: 4px; background-color: #007bff; color: white;">Refresh</a>
                </div>

                <div
                    style="overflow-x: auto; max-height: 600px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px;">
                    <table class="history-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="position: sticky; top: 0; background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Timestamp</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Level</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $rowColor = match (strtoupper($log['level'])) {
                                        'ERROR' => '#ffebee',
                                        'WARNING' => '#fff3e0',
                                        'DEBUG' => '#f3e5f5',
                                        default => '#ffffff',
                                    };
                                    $textColor = match (strtoupper($log['level'])) {
                                        'ERROR' => '#c62828',
                                        'WARNING' => '#ef6c00',
                                        'DEBUG' => '#6a1b9a',
                                        default => '#333333',
                                    };
                                @endphp
                                <tr style="background-color: {{ $rowColor }}; border-bottom: 1px solid #eee;">
                                    <td style="padding: 10px; white-space: nowrap; font-family: monospace; font-size: 0.9em;">
                                        {{ $log['timestamp'] }}
                                    </td>
                                    <td style="padding: 10px; font-weight: bold; color: {{ $textColor }};">
                                        {{ strtoupper($log['level']) }}
                                    </td>
                                    <td style="padding: 10px; font-family: monospace; font-size: 0.9em;">
                                        {{ $log['message'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="padding: 20px; text-align: center; color: #666;">
                                        No logs found or log file is empty.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Reusing settings styles */
        .banner {
            padding: 40px 0;
            background-color: #f4f6f9;
            min-height: 80vh;
        }

        .settings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .settings-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .settings-header h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .settings-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .settings-group {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }
    
    .filter-group {
        display: flex;
        background: #f1f3f5;
        border-radius: 6px;
        padding: 4px;
        gap: 5px;
    }
    
    .filter-btn {
        padding: 6px 12px;
        text-decoration: none;
        color: #495057;
        border-radius: 4px;
        font-size: 0.9em;
        transition: all 0.2s;
    }
    
    .filter-btn:hover {
        background-color: #e9ecef;
        color: #212529;
    }
    
    .filter-btn.active {
        background-color: #6c757d;
        color: white;
        font-weight: bold;
    }
</style>
@endsection