<?php
#  show only fatal errors
error_reporting(E_ERROR);

////////////////////////////////////////////////////////////////////////////////
// Path: index-melt.php


# config
$path_tools = "tools/";

$scriptname = basename(__FILE__);
#$scriptname = basename(__FILE__ , '.php'); // remove .php

# Get URL for iframe
$frame_sc = $_GET['sc'];

# Set start page
if ($frame_sc == "") {
    $frame_sc = "tools/demo/hello-world.php";
}

# dir path in tools directory
$dir = "./" . $path_tools;

$file_array = make_array($dir);

# function that makes array of all files (php and html) in tools directory and subdirectories
function make_array($dir)
{
    $files = glob($dir . "*");
    foreach ($files as $file) {
        $file_name = basename($file);
        $file_url = $file;

        if (is_dir($file)) {

            $file_array[] = array($file_name, $file_url, "dir", $file);

            $files_child = glob($file . "/*.{php,html,htm}", GLOB_BRACE);

            foreach ($files_child as $file_child) {
                $file_name = basename($file_child);
                $file_url = $file . "/" . $file_name;
                $file_array[] = array($file_name, $file_url, "file", $file);
            }
        } else {

            $file_array[] = array($file_name, $file_url, "file", $file);
        }
    }
    return $file_array;
}

# structure the array so that all file names are grouped with their directory names
$structure_array = array();

foreach ($file_array as $file) {
    # group files by directory name
    $structure_array[$file[3]][] = $file;
}

# make_dropdown_menu
$drop_output = make_dropdown_menu($structure_array, $path_tools);

# function to loop through $structure_array and create dropdown menu
function make_dropdown_menu($structure_array, $path_tools)
{
    # Here you define the exceptions that should not be displayed (!)
    $haystack = array( './tools/dontshow1', './tools/dontshow2' );

    foreach ($structure_array as $key => $value) {
        if (!in_array_r($key, $haystack)) {
            $drop_output .= '<div class="dropdown">';
            # ucwords() capitalizes the first letter of each word in a string
            $dir_name = strtoupper(str_replace_first("./" . $path_tools, $replace, $key));
            $drop_output .= '<button class="dropbtn">' . $dir_name;
            $drop_output .= '<i class="fa fa-caret-down"></i>';
            $drop_output .= '</button>';
            $drop_output .= '<div class="dropdown-content">';
            foreach ($value as $file) {
                if ($file[2] == "file") {
                    $menu_link_tools = $scriptname . "?&sc=" . $file[1];
                    $drop_output .= "<a href='$menu_link_tools'>$file[0]</a>";
                } else {
                    $drop_output .= "<a href='$file[1]'><b>(</b> " . ucwords($file[0]) . " <b>)</b></a>";
                }
            }
            $drop_output .= '</div>';
            $drop_output .= '</div>';
        }
    }

    return $drop_output;
}

# dont' show these directories
# ->
# $haystack = array( './tools/functions', './tools/tinymce', './tools/htmlpurifier' );
#
# if string is not in array element then return false
function in_array_r($needle, $haystack)
{
    $found = false;
    foreach ($haystack as $item) {
        if ($item == $needle) {
            $found = true;
            break;
        } elseif (is_array($item)) {
            $found = in_array_r($needle, $item);
        }
    }
    return $found;
}


# delete matching part of string
function str_replace_first($search, $replace, $subject)
{
    $pos = strpos($subject, $search);
    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>*-> PHP Scripting iFramer <-> v1 <-*</title>

    <style>
        * {
            font-family: Arial, sans-serif;
        }

        html,
        body {
            display: flex;
            flex-direction: column;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        header {
            background: #f3f3f3;
            color: grey;
            margin: 1;
        }

        main {
            flex: 1;
        }

        footer {
            background: whitesmoke;
            color: grey;
            margin: 0;
        }

        .container {
            margin: 2em auto;
            max-width: 1200px;
            padding: 0 1em;
        }

        .containerfooter {
            margin: 0;
            max-width: 1200px;
            padding: 0 1em;
        }

        /* Navbar container */
        .navbar {
            overflow: hidden;
            background-color: #333;
            font-family: Arial;
        }

        /* Links inside the navbar */
        .navbar a {
            float: left;
            font-size: 16px;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        /* The dropdown container */
        .dropdown {
            float: left;
            overflow: hidden;
        }

        /* Dropdown button */
        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: grey;
            padding: 14px 16px;
            background-color: inherit;
            font-family: inherit;
            /* Important for vertical align on mobile phones */
            margin: 0;
            /* Important for vertical align on mobile phones */
        }

        /* Add a red background color to navbar links on hover */
        .navbar a:hover,
        .dropdown:hover .dropbtn {
            background-color: #ddd;
        }

        /* Dropdown content (hidden by default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        /* Add a grey background color to dropdown links on hover */
        .dropdown-content a:hover {
            background-color: #ddd;
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>

</head>

<body>

    <header>

        <div class="navbar">

            <a href="<?php echo $scriptname; ?>">Home</a>
            
            <div class="dropdown">
                <button class="dropbtn">SnapUp
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="<?php echo $scriptname . "?&sc="; ?>https://www.bing.com/">Bing</a>
                    <a href="https://www.deepl.com/translator" target="_blank">Deepl</a>
                    <a href="#">STATIC Link 3</a>
                </div>
            </div>
            
            <?php echo $drop_output; ?>

        </div>

    </header>

    <main>

        <iframe src="<?php echo $frame_sc; ?>" style="border:0px #ffffff none;" name="myiFrame" scrolling="yes" frameborder="0" marginheight="1px" marginwidth="10px" height="100%" width="100%" allowfullscreen></iframe>

    </main>

    
    <footer>
        <div class="containerfooter">
            <?php if ($frame_sc) { echo "Script loaded: " . $frame_sc; } else { echo "&copy; " . date("Y") . " iFramer - No script loaded"; } ?>
        </div>
    </footer>
    

</body>
</html>
