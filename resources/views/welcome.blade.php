@extends('layouts.app')
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f5f5f5;
        }

        .gold {
            max-width: 800px;
            margin: auto;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            margin-bottom: 8px;
            background: white;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .key {
            font-weight: bold;
        }

        .value {
            direction: ltr;
        }
    </style>
</head>

<body>

<div class="gold">

    @if(isset($gold['items']) && is_array($gold['items']))

        @foreach($gold['items'] as $item)

            <div class="item">

                <span class="key">
                    {{ $item['date'] ?? '-' }}
                </span>

                <span class="value">
                    {{ $item['price'] ?? '-' }}
                </span>

            </div>

        @endforeach

    @else

        <p>اطلاعاتی دریافت نشد.</p>

    @endif

</div>

</body>

</html>