@extends('layouts.admin_body_layout')

@section('title', 'Marking Scheme')

@section('page_styles')
    <style>
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-button {
            background-color: #6c757d;
        }

        .back-button:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            width: 90%;
            max-width: 900px;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .marking-input {
            width: 100%;
            padding: 8px;
        }

        .marking-title-cell {
            background-color: #fafafa;
            color: #333;
            font-weight: bold;
            vertical-align: top;
            padding-top: 15px;
        }
    </style>
@endsection

@section('content')
    <div class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px; margin-top: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-button">Back</a>
        </div>

        <div class="container"
            style="width: 90%; max-width: 900px; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin: 40px auto;">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('marking-scheme.update') }}" method="POST"
                onsubmit="return confirm('Are you sure you want to update the marking scheme?');">
                @csrf
                @method('PUT')

                <h2 style="text-align: center; color: #333; margin-bottom: 10px;">Update Marking Scheme Values</h2>
                <p style="text-align: center; color: #666; margin-bottom: 30px;">Adjust the marks awarded for each category
                    below.</p>

                <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th style="padding: 12px;">Title (Section)</th>
                            <th style="padding: 12px;">Option (Criteria)</th>
                            <th style="padding: 12px;">Defined Mark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($marking_schemes as $title => $schemes)
                            @foreach($schemes as $index => $scheme)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ count($schemes) }}" class="marking-title-cell">{{ $scheme->marking_title }}</td>
                                    @endif
                                    <td>{{ $scheme->marking_option }}</td>
                                    <td><input type="number" name="marks[{{ $scheme->marking_option }}]"
                                            value="{{ $scheme->defined_mark }}" class="marking-input"></td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit"
                        style="padding: 12px 25px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">
                        Update Marking Scheme
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection