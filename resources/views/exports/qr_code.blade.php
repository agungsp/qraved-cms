<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .text-center {
            text-align: center !important;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -0.75rem;
            margin-left: -0.75rem;
        }
        h4 {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="text-center" style="margin-right: -0.75rem; margin-left: -0.75rem;">
        <span style="display: block;">{!! QrCode::size(300)->generate($resto->qr_code->code); !!}</span>
        <h4>{{ $resto->name }}</h4>
    </div>
</body>
</html>
