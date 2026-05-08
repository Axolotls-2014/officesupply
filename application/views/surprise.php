<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I'm Sorry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8cdda, #1f1c2c);
            color: white;
            text-align: center;
            font-family: 'Dancing Script', cursive;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .quote {
            font-size: 1.5rem;
            margin: 20px;
        }
        .btn-custom {
            background-color: #ff758c;
            border: none;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 30px;
            color: white;
            transition: 0.3s;
            cursor: pointer;
        }
        .btn-custom:hover {
            background-color: #ff5a78;
        }
    </style>
    <script>
        function showThankYou() {
            alert("Thank you for forgiving me! ❤️");
        }
    </script>
</head>
<body>
    <h1>I'm Truly Sorry ❤</h1>
    <img src="https://media.tenor.com/U6xEjwXk3SgAAAAC/sorry-bear.gif" alt="Sorry GIF" class="img-fluid" style="max-width: 300px; border-radius: 10px;">
    <p class="quote">"A simple sorry can be the most powerful word. But only if it's said with a heart full of regret."</p>
    <p class="quote">"I regret the pain I’ve caused. I only hope you find it in your heart to forgive me."</p>
    <button class="btn btn-custom" onclick="showThankYou()">Click Me to Forgive</button>
</body>
</html>
