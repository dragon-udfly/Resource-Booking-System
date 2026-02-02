@extends('layouts.normal_body_layout')

@section('title', 'Help')

@section('page_styles')
    <style>
        .help-container {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
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
        <div class="page-header">
            <h2>How to Use</h2>
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