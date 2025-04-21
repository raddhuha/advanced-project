<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $tutorial->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .step { margin-bottom: 20px; }
        .step-header { font-weight: bold; margin-bottom: 5px; }
        .code-block { background: #f4f4f4; padding: 10px; font-family: monospace; white-space: pre-wrap; }
        img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <h1>{{ $tutorial->title }}</h1>
    <p><strong>Kode Kelas:</strong> {{ $tutorial->course_code }}</p>
    <hr>

    @foreach ($tutorial->detailTutorials as $step)
        <div class="step">
            <div class="step-header">Langkah {{ $step->order }}</div>

            @if($step->type == 'text')
                <div>{{ $step->content }}</div>
            @elseif($step->type == 'code')
                <div class="code-block">{{ $step->content }}</div>
            @elseif($step->type == 'url')
                <div><a href="{{ $step->content }}">{{ $step->content }}</a></div>
            @elseif($step->type == 'image')
                <div>
                    <img src="{{ public_path('storage/' . $step->content) }}" alt="Gambar Langkah">
                </div>
            @endif
        </div>
    @endforeach
</body>
</html>
