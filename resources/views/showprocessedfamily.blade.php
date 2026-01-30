@extends('layouts.user_body_layout')

@section('title', 'Processed Family Quarter Application - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header { text-align: center; margin-bottom: 30px; color: #333; }
        .page-header h2 { font-size: 1.8em; margin-bottom: 10px; }
        .button-bar { display: flex; justify-content: flex-start; gap: 15px; margin-bottom: 20px; width: 90%; max-width: 1200px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; text-decoration: none; color: white; transition: background-color 0.3s ease; }
        .back-btn { background-color: #007bff; }
        .btn:hover { opacity: 0.9; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 90%; max-width: 1200px; margin-top: 20px; }
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .form-group { flex: 1; min-width: 280px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group p { width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em; background-color: #f8f9fa; min-height: 40px; margin: 0; }
        .form-section-title { font-size: 1.5em; font-weight: bold; margin-bottom: 20px; color: #0056b3; border-bottom: 2px solid #eee; padding-bottom: 10px; width: 100%; }
        .status-allocated { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .status-rejected { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .status-cancelled { color: #383d41; background-color: #e2e3e5; border-color: #d6d8db; }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="button-bar">
            <a href="{{ route('history') }}" class="btn back-btn">Back to History</a>
        </div>
        
        <div class="page-header">
            <h2>Processed Family Quarter Application</h2>
        </div>

        <div class="form-container">
            <h3 class="form-section-title">A) Officer Details</h3>
            <div class="form-row">
                <div class="form-group"><label>1. Name of Officer:</label><p>{{ $application->officer_name ?? 'N/A' }}</p></div>
                <div class="form-group"><label>2. NIC Number:</label><p>{{ $application->nic ?? 'N/A' }}</p></div>
            </div>
            <!-- Add all other fields from family application in a read-only format -->
             <div class="form-row">
                <div class="form-group">
                    <label>3. Date of Birth:</label>
                    <p>{{ $application->familyQuarterApplication?->f_dob ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>4. Designation:</label>
                    <p>{{ $application->designation ?? 'N/A' }}</p>
                </div>
            </div>
            <h3 class="form-section-title">B) Spouse Details</h3>
            <div class="form-row">
               <div class="form-group">
                    <label>1. Marital Status:</label>
                    <p>{{ $application->familyQuarterApplication?->f_marital_status ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>2. Is your spouse employed in government service?</label>
                    <p>{{ isset($application->familyQuarterApplication?->f_is_spouse_employed) ? ($application->familyQuarterApplication?->f_is_spouse_employed ? 'Yes' : 'No') : 'N/A' }}</p>
                </div>
            </div> 
        </div> 

        <div class="form-container">
            <h3 class="form-section-title">C) Allocation Details</h3>
            @php
                $allocation = $application->quarterAllocation;
                $statusClass = '';
                if ($allocation->allocation_status == 'allocated') $statusClass = 'status-allocated';
                if ($allocation->allocation_status == 'rejected') $statusClass = 'status-rejected';
                if ($allocation->allocation_status == 'cancelled') $statusClass = 'status-cancelled';
            @endphp
            <div class="form-row">
                <div class="form-group">
                    <label>Final Allocation Status:</label>
                    <p class="{{ $statusClass }}" style="font-weight: bold; text-transform: capitalize;">{{ $allocation->allocation_status ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>Allocation/Rejection Date:</label>
                    <p>{{ $allocation->updated_at ? $allocation->updated_at->format('Y-m-d') : 'N/A' }}</p>
                </div>
            </div>

            @if($allocation->allocation_status == 'allocated')
                <div class="form-row">
                    <div class="form-group">
                        <label>Allocated Quarter No (New):</label>
                        <p>{{ $allocation->quarter->new_quarter_no ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label>Allocated Quarter Location:</label>
                        <p>{{ $allocation->quarter->location ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Allocation Date:</label>
                        <p>{{ $allocation->allocation_date ? \Carbon\Carbon::parse($allocation->allocation_date)->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label>Expected Vacate Date:</label>
                        <p>{{ $allocation->vacate_date ? \Carbon\Carbon::parse($allocation->vacate_date)->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label>Administrative Officer Note:</label>
                    <p>{{ $allocation->ao_note ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>Additional Government Agent Note:</label>
                    <p>{{ $allocation->aga_note ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Government Agent Note:</label>
                    <p>{{ $allocation->ga_note ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
