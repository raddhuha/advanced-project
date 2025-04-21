<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $tutorial->title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .course-code {
            color: #666;
            margin-bottom: 20px;
        }
        hr {
            border: 0;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }
        .step {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .step-header {
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }
        .code-block {
            background: #f5f5f5;
            padding: 10px;
            font-family: monospace;
            white-space: pre-wrap;
            border-radius: 4px;
            margin: 10px 0;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            overflow-x: auto;
            font-size: 12px;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin: 10px 0;
        }
        a {
            color: #2563eb;
            text-decoration: none;
        }
        .text-content {
            line-height: 1.5;
        }
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .page-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>{{ $tutorial->title }}</h1>
        <p class="course-code"><strong>Kode Kelas:</strong> {{ $tutorial->course_code }}</p>
    </div>

    <h2>Langkah-langkah:</h2>

    @foreach ($tutorial->detailTutorials as $step)
        <div class="step">
            <div class="step-header">Langkah {{ $step->order }} ({{ ucfirst($step->type) }})</div>

            @if($step->type == 'text')
                <div class="text-content">
                    {{ $step->content }}
                </div>
            @elseif($step->type == 'code')
                <div class="code-block">{{ $step->content }}</div>
            @elseif($step->type == 'url')
                <div>
                    <a href="{{ $step->content }}">{{ $step->content }}</a>
                </div>
            @elseif($step->type == 'image')
                <div>
                    @if(isset($step->absolute_image_path) && file_exists($step->absolute_image_path))
                        <img src="{{ $step->absolute_image_path }}" alt="Gambar Langkah {{ $step->order }}">
                    @else
                        <img src="file://{{ storage_path('app/public/' . $step->content) }}" alt="Gambar Langkah {{ $step->order }}">
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</body>
</html>
