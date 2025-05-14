<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meals</title>
</head>
<body>
    <h1>Meal Image</h1>
    <img src="{{ asset('storage/meals/' . $meal->image) }}" alt="meal image">
</body>
</html>
