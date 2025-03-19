<?php
// Connect to SQLite database
$db = new SQLite3('lp_simple_rank.sq3');

// Get players ranked by score
$results = $db->query('SELECT name, CIKills, SIKills, Headshots, HeadshotDamage, PlayTime FROM lp_simple_rank ORDER BY Score DESC LIMIT 100');

// Check for query errors
if (!$results) {
    die("Database error: " . $db->lastErrorMsg());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esquerda4Morto2 - Player Rankings</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Creepster&family=Special+Elite&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            height: 100vh;
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #0f0f0f;
            color: #e0e0e0;
            font-family: 'Special Elite', cursive;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("logoMotd.png");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            filter: brightness(0.4) contrast(1.2);
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Navigation */
        .nav {
            position: sticky;
            top: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            padding: 10px 0;
            z-index: 100;
            border-bottom: 1px solid #333;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo {
            font-family: 'Creepster', cursive;
            font-size: 28px;
            color: #ff3333;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.7);
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
            text-shadow: 0 0 20px rgba(255, 0, 0, 0.9);
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-link {
            color: #aaa;
            text-decoration: none;
            font-size: 16px;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Header */
        .header {
            padding: 60px 0 30px;
            text-align: center;
        }

        .header h1 {
            font-family: 'Creepster', cursive;
            font-size: 48px;
            color: #ff3333;
            letter-spacing: 3px;
            margin-bottom: 15px;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.7);
            animation: pulse 4s infinite;
        }

        .header p {
            font-size: 18px;
            color: #aaa;
            max-width: 600px;
            margin: 0 auto;
        }

        .card {
            background: rgba(20, 20, 20, 0.8);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px;
            background: rgba(0, 0, 0, 0.4);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-header h2 {
            font-family: 'Creepster', cursive;
            font-size: 24px;
            color: #ff9900;
            letter-spacing: 1px;
            text-align: center;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .rank-table {
            width: 100%;
            border-collapse: collapse;
        }

        .rank-table th, .rank-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .rank-table th {
            background-color: rgba(0, 0, 0, 0.3);
            color: #ff9900;
        }

        .rank-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Top 3 players highlighting */
        .rank-table tr:nth-child(1) td {
            background-color: rgba(255, 215, 0, 0.2);
            color: #ffd700;
        }

        .rank-table tr:nth-child(2) td {
            background-color: rgba(192, 192, 192, 0.2);
            color: #c0c0c0;
        }

        .rank-table tr:nth-child(3) td {
            background-color: rgba(205, 127, 50, 0.2);
            color: #cd7f32;
        }

        .footer {
            margin-top: 50px;
            padding: 20px 0;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .steam-link {
            color: #66c0f4;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .steam-link:hover {
            background-color: rgba(102, 192, 244, 0.1);
            color: #ffffff;
        }

        /* Animations */
        @keyframes pulse {
            0% { text-shadow: 0 0 10px rgba(255, 0, 0, 0.7); }
            50% { text-shadow: 0 0 20px rgba(255, 0, 0, 0.9), 0 0 30px rgba(255, 0, 0, 0.5); }
            100% { text-shadow: 0 0 10px rgba(255, 0, 0, 0.7); }
        }
    </style>
</head>
<body>
    <div class="nav">
        <div class="nav-container">
            <div class="logo">L4D2</div>
            <div class="nav-links">
                <a href="index.html" class="nav-link">Home</a>
                <a href="index.html#comandos" class="nav-link">Comandos</a>
                <a href="index.html#infectados" class="nav-link">Infectados</a>
                <a href="index.html#dicas" class="nav-link">Dicas</a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="header">
            <h1>Player Rankings</h1>
            <p>Os melhores sobreviventes do apocalipse zumbi</p>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>Top Players</h2>
            </div>
            <div class="card-body">
                <table class="rank-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Nome</th>
                            <th>Zombies Comuns</th>
                            <th>Zombies Especiais</th>
                            <th>Headshots</th>
                            <th>Dano Headshot</th>
                            <th>Tempo (horas)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rank = 1;
                        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                            // Convert playtime from seconds to hours
                            $hoursPlayed = round($row['PlayTime'] / 3600, 1);

                            echo "<tr>";
                            echo "<td>" . $rank++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . number_format($row['CIKills']) . "</td>";
                            echo "<td>" . number_format($row['SIKills']) . "</td>";
                            echo "<td>" . number_format($row['Headshots']) . "</td>";
                            echo "<td>" . number_format($row['HeadshotDamage']) . "</td>";
                            echo "<td>" . $hoursPlayed . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Esquerda 4 Morto 2 - Sobreviva se puder! Meu Discord: #marvin9541</p>
        <p>
            <a href="https://steamcommunity.com/groups/YOUR_GROUP_ID_HERE" target="_blank" class="steam-link">
                <img src="steam-icon.png" alt="Steam Group" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 5px;">
                Junte-se ao nosso grupo Steam
            </a>
        </p>
    </div>
</body>
</html>