@extends('layouts.normal_body_layout')

@section('title', 'Help')

@section('page_styles')
    <style>
        /* Page Header Styles */
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 2em;
            font-weight: bold;
            color: #0056b3;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            display: inline-block;
        }

        /* Button Bar Styles */
        .button-bar {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 1000px;
            /* Match help container width */
            margin-left: auto;
            margin-right: auto;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: bold;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease;
        }

        .home-btn {
            background-color: #6c757d;
        }

        .back-btn {
            background-color: #007bff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .lang-btn {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }

        .lang-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .help-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto 40px auto;
            /* Added bottom margin */
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .accordion-item {
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 10px;
        }

        .accordion-header {
            width: 100%;
            padding: 15px;
            background-color: #f8f9fa;
            border: none;
            text-align: left;
            outline: none;
            font-size: 1.1em;
            font-weight: bold;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 5px;
        }

        .accordion-header:hover {
            background-color: #e2e6ea;
        }

        .accordion-header.active {
            background-color: #007bff;
            color: white;
        }

        .accordion-content {
            padding: 0 18px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .accordion-content-inner {
            padding: 20px 0;
        }

        /* Markdown Content Styles */
        .accordion-content h1,
        .accordion-content h2,
        .accordion-content h3 {
            margin-top: 15px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .accordion-content ul,
        .accordion-content ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .accordion-content p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .accordion-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .icon {
            transition: transform 0.3s ease;
        }

        .accordion-header.active .icon {
            transform: rotate(180deg);
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin') }}" class="btn home-btn">Admin Panel</a>
            @elseif(Auth::check())
                <a href="{{ route('homepage') }}" class="btn home-btn">Home</a>
            @else
                <a href="{{ route('home') }}" class="btn home-btn">Home</a>
            @endif
        </div>

        <div class="page-header">
            <h2>How to Use</h2>
            <div class="button-bar" style="justify-content: center; margin-top: 10px; gap: 10px;">
                <a href="{{ route('help', ['lang' => 'en']) }}"
                    class="btn lang-btn {{ $currentLang === 'en' ? 'active' : '' }}">English</a>
                <a href="{{ route('help', ['lang' => 'ta']) }}"
                    class="btn lang-btn {{ $currentLang === 'ta' ? 'active' : '' }}">தமிழ்</a>
                <a href="{{ route('help', ['lang' => 'si']) }}"
                    class="btn lang-btn {{ $currentLang === 'si' ? 'active' : '' }}">සිංහල</a>
            </div>
        </div>

        <div class="help-container">
            @if(count($documents) > 0)
                @foreach($documents as $doc)
                    <div class="accordion-item">
                        <button class="accordion-header">
                            {{ $doc['title'] }}
                            <span class="icon">▼</span>
                        </button>
                        <div class="accordion-content">
                            <div class="accordion-content-inner">
                                {!! $doc['content'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    No help documentation found.
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accordions = document.querySelectorAll('.accordion-header');

            accordions.forEach(acc => {
                acc.addEventListener('click', function () {
                    this.classList.toggle('active');
                    const panel = this.nextElementSibling;

                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                    }
                });
            });
        });
    </script>
@endpush