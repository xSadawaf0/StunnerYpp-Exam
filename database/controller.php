<?php
require_once 'connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $title = $_POST['title'];
        $artist = $_POST['artist'];
        $lyrics = $_POST['lyrics'];
        addSong($title, $artist, $lyrics);
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        deleteSong($id);
    } elseif ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $artist = $_POST['artist'];
        $lyrics = $_POST['lyrics'];
        updateSong($id, $title, $artist, $lyrics);
    }
}

function listSongs(){
    global $conn;
    $sql = "SELECT * FROM songs WHERE active = 1";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error retrieving songs: " . $conn->error);
    }

    return $result;
}


function addSong($title, $artist, $lyrics, $active = true)
{
    global $conn;
    $sql = "INSERT INTO songs (title, artist, lyrics, active, date_created) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $date = date('Y-m-d');
    $stmt->bind_param("sssi", $title, $artist, $lyrics, $active);

    if ($stmt->execute()) {
        $_SESSION['message'] = "New entry created successfully";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    header("Location: ../index.php");
    exit();
}


function updateSong($id, $title = null, $artist = null, $lyrics = null)
{
    global $conn;
    $sql = "UPDATE songs SET";
    $updates = array();
    $params = array();

    if ($title !== null) {
        $updates[] = "title = ?";
        $params[] = $title;
    }
    if ($artist !== null) {
        $updates[] = "artist = ?";
        $params[] = $artist;
    }
    if ($lyrics !== null) {
        $updates[] = "lyrics = ?";
        $params[] = $lyrics;
    }

    $sql .= " " . implode(', ', $updates) . " WHERE id = ?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Entry updated successfully";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    header("Location: ../index.php");
    exit();
}

function deleteSong($id) {
    global $conn;
    $sql = "UPDATE songs SET active = 0 WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Entry deleted successfully";
    } else {
        $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    
    header("Location: ../index.php");
    exit();
}
?>
