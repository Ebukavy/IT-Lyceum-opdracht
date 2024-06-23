<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar-brand {
            font-size: 1.5rem;
            color: #ffffff; 
        }

        .navbar-nav .nav-link {
            color: #ffffff; 
        }

        .navbar-toggler {
            border-color: #ffffff; 
        }

        .navbar-toggler-icon {
            background-color: #ffffff;
        }

        .navbar-collapse {
            background-color: #343a40; 
        }

        .nav-link:hover {
            color: #17a2b8 !important; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/itlyceum/home/Homepage.php">ITLyceum</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/itlyceum/login/register.php">Registreren</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/itlyceum/login/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/itlyceum/klas/klasinfo.php">Klassen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/rooster.php">Rooster</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/gebruikers.php">Gebruikers</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/itlyceum/login/logout.php">Logout</a>
                    </li>
            </ul>
        </div>
    </nav>
</body>
</html>
